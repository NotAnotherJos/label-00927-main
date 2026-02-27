<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\Permission;
use app\common\exception\BusinessException;
use app\common\service\CacheService;
use app\common\service\LogService;
use think\facade\Db;

/**
 * 权限服务
 */
class PermissionService
{
    /**
     * 获取权限列表
     *
     * @param array $params
     * @return array
     */
    public static function getList(array $params = []): array
    {
        $query = Permission::order('sort asc, id asc');
        
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }
        
        return $query->select()->toArray();
    }

    /**
     * 获取权限树形结构
     *
     * @return array
     */
    public static function getTree(): array
    {
        $list = Permission::where('status', 1)
            ->order('sort asc, id asc')
            ->select()
            ->toArray();
        
        return self::buildTree($list);
    }

    /**
     * 构建树形结构
     *
     * @param array $list
     * @param int $pid
     * @return array
     */
    private static function buildTree(array $list, int $pid = 0): array
    {
        $tree = [];
        foreach ($list as $item) {
            if ($item['pid'] == $pid) {
                $children = self::buildTree($list, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * 创建权限
     *
     * @param array $params
     * @return Permission
     */
    public static function create(array $params): Permission
    {
        Db::startTrans();
        try {
            $permission = new Permission();
            $permission->pid = $params['pid'] ?? 0;
            $permission->name = $params['name'];
            $permission->code = $params['code'] ?? '';
            $permission->type = $params['type'] ?? 1;
            $permission->path = $params['path'] ?? '';
            $permission->component = $params['component'] ?? '';
            $permission->icon = $params['icon'] ?? '';
            $permission->sort = $params['sort'] ?? 0;
            $permission->status = $params['status'] ?? 1;
            $permission->save();
            
            LogService::record('create', "新增权限：{$permission->name}", $params);
            
            Db::commit();
            return $permission;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('创建权限失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 更新权限
     *
     * @param int $id
     * @param array $params
     * @return Permission
     */
    public static function update(int $id, array $params): Permission
    {
        $permission = Permission::find($id);
        if (!$permission) {
            throw new BusinessException('权限不存在', 404);
        }
        
        if (isset($params['pid']) && $params['pid'] == $id) {
            throw new BusinessException('不能将权限设置为自己的子权限', 400);
        }
        
        Db::startTrans();
        try {
            if (isset($params['pid'])) {
                $permission->pid = $params['pid'];
            }
            if (isset($params['name'])) {
                $permission->name = $params['name'];
            }
            if (isset($params['code'])) {
                $permission->code = $params['code'];
            }
            if (isset($params['type'])) {
                $permission->type = $params['type'];
            }
            if (isset($params['path'])) {
                $permission->path = $params['path'];
            }
            if (isset($params['component'])) {
                $permission->component = $params['component'];
            }
            if (isset($params['icon'])) {
                $permission->icon = $params['icon'];
            }
            if (isset($params['sort'])) {
                $permission->sort = $params['sort'];
            }
            if (isset($params['status'])) {
                $permission->status = $params['status'];
            }
            $permission->save();
            
            LogService::record('update', "修改权限：{$permission->name}", $params);
            
            Db::commit();
            return $permission;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('更新权限失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 删除权限
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $permission = Permission::find($id);
        if (!$permission) {
            throw new BusinessException('权限不存在', 404);
        }
        
        // 检查是否有子权限
        $childCount = Permission::where('pid', $id)->count();
        if ($childCount > 0) {
            throw new BusinessException('存在子权限，无法删除', 400);
        }
        
        LogService::record('delete', "删除权限：{$permission->name}", ['id' => $id]);
        
        return $permission->delete();
    }

    /**
     * 获取用户权限列表
     *
     * @param int $userId
     * @return array
     */
    public static function getUserPermissions(int $userId): array
    {
        $cacheKey = "admin_permission_{$userId}";
        
        return \think\facade\Cache::remember($cacheKey, function () use ($userId) {
            $user = \app\admin\model\AdminUser::find($userId);
            if (!$user) {
                return [];
            }
            
            // 超级管理员返回所有权限
            if ($user->role_id == 1) {
                return Permission::where('status', 1)->column('code');
            }
            
            // 获取角色权限
            $permissionIds = Db::table('tp_role_permission')
                ->where('role_id', $user->role_id)
                ->column('permission_id');
            
            if (empty($permissionIds)) {
                return [];
            }
            
            return Permission::whereIn('id', $permissionIds)
                ->where('status', 1)
                ->column('code');
        }, 3600);
    }

    /**
     * 检查用户是否有权限
     *
     * @param int $userId
     * @param string $permissionCode
     * @return bool
     */
    public static function hasPermission(int $userId, string $permissionCode): bool
    {
        $permissions = self::getUserPermissions($userId);
        return in_array($permissionCode, $permissions);
    }

    /**
     * 获取角色权限ID列表
     *
     * @param int $roleId
     * @return array
     */
    public static function getRolePermissionIds(int $roleId): array
    {
        return Db::table('tp_role_permission')
            ->where('role_id', $roleId)
            ->column('permission_id');
    }

    /**
     * 设置角色权限
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return bool
     */
    public static function setRolePermissions(int $roleId, array $permissionIds): bool
    {
        Db::startTrans();
        try {
            // 删除原有权限
            Db::table('tp_role_permission')->where('role_id', $roleId)->delete();
            
            // 添加新权限
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'create_time' => date('Y-m-d H:i:s'),
                ];
            }
            if (!empty($data)) {
                Db::table('tp_role_permission')->insertAll($data);
            }
            
            // 清除相关用户的权限缓存
            $userIds = \app\admin\model\AdminUser::where('role_id', $roleId)->column('id');
            foreach ($userIds as $userId) {
                CacheService::clearUserPermission($userId);
            }
            
            LogService::record('update', "设置角色权限，角色ID：{$roleId}", ['permission_ids' => $permissionIds]);
            
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('设置角色权限失败：' . $e->getMessage(), 500);
        }
    }
}
