<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\Menu;
use app\common\exception\BusinessException;
use app\common\service\CacheService;
use app\common\service\LogService;
use think\facade\Db;

/**
 * 菜单服务
 */
class MenuService
{
    /**
     * 获取菜单列表
     *
     * @param array $params
     * @return array
     */
    public static function getList(array $params = []): array
    {
        $query = Menu::order('sort asc, id asc');
        
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
     * 获取菜单树形结构
     *
     * @param int|null $roleId 角色ID，用于获取角色菜单
     * @return array
     */
    public static function getTree(?int $roleId = null): array
    {
        $query = Menu::where('status', 1)->order('sort asc, id asc');
        
        // 如果指定了角色，只获取角色拥有的菜单
        if ($roleId !== null) {
            $menuIds = Db::table('tp_role_menu')
                ->where('role_id', $roleId)
                ->column('menu_id');
            if (!empty($menuIds)) {
                $query->whereIn('id', $menuIds);
            }
        }
        
        $list = $query->select()->toArray();
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
     * 创建菜单
     *
     * @param array $params
     * @return Menu
     */
    public static function create(array $params): Menu
    {
        Db::startTrans();
        try {
            $menu = new Menu();
            $menu->pid = $params['pid'] ?? 0;
            $menu->name = $params['name'];
            $menu->path = $params['path'] ?? '';
            $menu->component = $params['component'] ?? '';
            $menu->icon = $params['icon'] ?? '';
            $menu->type = $params['type'] ?? 1;
            $menu->permission = $params['permission'] ?? '';
            $menu->sort = $params['sort'] ?? 0;
            $menu->status = $params['status'] ?? 1;
            $menu->save();
            
            // 清除菜单缓存
            CacheService::clearMenuTree();
            
            LogService::record('create', "新增菜单：{$menu->name}", $params);
            
            Db::commit();
            return $menu;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('创建菜单失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 更新菜单
     *
     * @param int $id
     * @param array $params
     * @return Menu
     */
    public static function update(int $id, array $params): Menu
    {
        $menu = Menu::find($id);
        if (!$menu) {
            throw new BusinessException('菜单不存在', 404);
        }
        
        if (isset($params['pid']) && $params['pid'] == $id) {
            throw new BusinessException('不能将菜单设置为自己的子菜单', 400);
        }
        
        Db::startTrans();
        try {
            if (isset($params['pid'])) {
                $menu->pid = $params['pid'];
            }
            if (isset($params['name'])) {
                $menu->name = $params['name'];
            }
            if (isset($params['path'])) {
                $menu->path = $params['path'];
            }
            if (isset($params['component'])) {
                $menu->component = $params['component'];
            }
            if (isset($params['icon'])) {
                $menu->icon = $params['icon'];
            }
            if (isset($params['type'])) {
                $menu->type = $params['type'];
            }
            if (isset($params['permission'])) {
                $menu->permission = $params['permission'];
            }
            if (isset($params['sort'])) {
                $menu->sort = $params['sort'];
            }
            if (isset($params['status'])) {
                $menu->status = $params['status'];
            }
            $menu->save();
            
            // 清除菜单缓存
            CacheService::clearMenuTree();
            
            LogService::record('update', "修改菜单：{$menu->name}", $params);
            
            Db::commit();
            return $menu;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('更新菜单失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 删除菜单
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $menu = Menu::find($id);
        if (!$menu) {
            throw new BusinessException('菜单不存在', 404);
        }
        
        // 检查是否有子菜单
        $childCount = Menu::where('pid', $id)->count();
        if ($childCount > 0) {
            throw new BusinessException('存在子菜单，无法删除', 400);
        }
        
        // 清除菜单缓存
        CacheService::clearMenuTree();
        
        LogService::record('delete', "删除菜单：{$menu->name}", ['id' => $id]);
        
        return $menu->delete();
    }

    /**
     * 获取用户菜单
     *
     * @param int $userId
     * @return array
     */
    public static function getUserMenus(int $userId): array
    {
        $user = \app\admin\model\AdminUser::find($userId);
        if (!$user) {
            return [];
        }
        
        // 超级管理员返回所有菜单
        if ($user->role_id == 1) {
            return self::getTree();
        }
        
        return self::getTree($user->role_id);
    }
}
