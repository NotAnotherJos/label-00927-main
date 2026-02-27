<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\Role;
use app\common\exception\BusinessException;
use app\common\service\CacheService;
use app\common\service\LogService;
use think\facade\Db;

/**
 * 角色服务
 */
class RoleService
{
    /**
     * 获取角色列表
     */
    public static function getList(int $page, int $limit, array $params = []): array
    {
        $query = Role::order('sort asc, id desc');
        
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
        
        $total = $query->count();
        $list = $query->page($page, $limit)->select();
        
        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 获取所有角色（下拉选择用）
     */
    public static function getAll(): array
    {
        return Role::where('status', 1)
            ->order('sort asc, id asc')
            ->field('id, name, code')
            ->select()
            ->toArray();
    }

    /**
     * 获取角色详情
     */
    public static function getDetail(int $id): array
    {
        $role = Role::find($id);
        if (!$role) {
            throw new BusinessException('角色不存在', 404);
        }
        
        $data = $role->toArray();
        
        // 获取角色菜单ID
        $data['menu_ids'] = Db::table('tp_role_menu')
            ->where('role_id', $id)
            ->column('menu_id');
        
        // 获取角色权限ID
        $data['permission_ids'] = Db::table('tp_role_permission')
            ->where('role_id', $id)
            ->column('permission_id');
        
        return $data;
    }

    /**
     * 创建角色
     */
    public static function create(array $params): Role
    {
        Db::startTrans();
        try {
            $role = new Role();
            $role->name = $params['name'];
            $role->code = $params['code'];
            $role->data_scope = $params['data_scope'] ?? 1;
            $role->remark = $params['remark'] ?? '';
            $role->sort = $params['sort'] ?? 0;
            $role->status = $params['status'] ?? 1;
            $role->save();
            
            // 记录日志
            LogService::record('create', "新增角色：{$role->name}", $params);
            
            Db::commit();
            return $role;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('创建角色失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 更新角色
     */
    public static function update(int $id, array $params): Role
    {
        $role = Role::find($id);
        if (!$role) {
            throw new BusinessException('角色不存在', 404);
        }
        
        Db::startTrans();
        try {
            if (isset($params['name'])) {
                $role->name = $params['name'];
            }
            if (isset($params['data_scope'])) {
                $role->data_scope = $params['data_scope'];
            }
            if (isset($params['remark'])) {
                $role->remark = $params['remark'];
            }
            if (isset($params['sort'])) {
                $role->sort = $params['sort'];
            }
            if (isset($params['status'])) {
                $role->status = $params['status'];
            }
            $role->save();
            
            // 记录日志
            LogService::record('update', "修改角色：{$role->name}", $params);
            
            Db::commit();
            return $role;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('更新角色失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 删除角色
     */
    public static function delete(int $id): bool
    {
        $role = Role::find($id);
        if (!$role) {
            throw new BusinessException('角色不存在', 404);
        }
        
        // 检查是否有用户使用该角色
        $userCount = \app\admin\model\AdminUser::where('role_id', $id)->count();
        if ($userCount > 0) {
            throw new BusinessException('该角色下存在用户，无法删除', 400);
        }
        
        Db::startTrans();
        try {
            // 删除角色菜单关联
            Db::table('tp_role_menu')->where('role_id', $id)->delete();
            // 删除角色权限关联
            Db::table('tp_role_permission')->where('role_id', $id)->delete();
            // 删除角色部门关联
            Db::table('tp_role_dept')->where('role_id', $id)->delete();
            
            // 记录日志
            LogService::record('delete', "删除角色：{$role->name}", ['id' => $id]);
            
            $role->delete();
            
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('删除角色失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 设置角色菜单
     *
     * @param int $roleId
     * @param array $menuIds
     * @return bool
     */
    public static function setRoleMenus(int $roleId, array $menuIds): bool
    {
        $role = Role::find($roleId);
        if (!$role) {
            throw new BusinessException('角色不存在', 404);
        }
        
        Db::startTrans();
        try {
            // 删除原有菜单关联
            Db::table('tp_role_menu')->where('role_id', $roleId)->delete();
            
            // 添加新菜单关联
            $data = [];
            foreach ($menuIds as $menuId) {
                $data[] = [
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'create_time' => date('Y-m-d H:i:s'),
                ];
            }
            if (!empty($data)) {
                Db::table('tp_role_menu')->insertAll($data);
            }
            
            // 清除菜单缓存
            CacheService::clearMenuTree();
            
            LogService::record('update', "设置角色菜单，角色：{$role->name}", ['menu_ids' => $menuIds]);
            
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('设置角色菜单失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 获取角色菜单ID列表
     *
     * @param int $roleId
     * @return array
     */
    public static function getRoleMenuIds(int $roleId): array
    {
        return Db::table('tp_role_menu')
            ->where('role_id', $roleId)
            ->column('menu_id');
    }
}
