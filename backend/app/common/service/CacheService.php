<?php
declare(strict_types=1);

namespace app\common\service;

use think\facade\Cache;
use think\facade\Db;

/**
 * 缓存服务
 */
class CacheService
{
    // 缓存Key前缀
    const PREFIX_PERMISSION = 'admin_permission_';
    const PREFIX_MENU = 'admin_menu_tree';
    const PREFIX_ROLE = 'admin_role_';
    const PREFIX_USER_INFO = 'admin_user_info_';

    /**
     * 获取用户权限列表
     *
     * @param int $userId
     * @return array
     */
    public static function getUserPermissions(int $userId): array
    {
        $key = self::PREFIX_PERMISSION . $userId;
        
        return Cache::remember($key, function () use ($userId) {
            $user = \app\admin\model\AdminUser::find($userId);
            if (!$user) {
                return [];
            }
            
            // 超级管理员返回所有权限
            if ($user->role_id == 1) {
                return \app\admin\model\Permission::where('status', 1)->column('code');
            }
            
            // 获取角色权限
            $permissionIds = Db::table('tp_role_permission')
                ->where('role_id', $user->role_id)
                ->column('permission_id');
            
            if (empty($permissionIds)) {
                return [];
            }
            
            return \app\admin\model\Permission::whereIn('id', $permissionIds)
                ->where('status', 1)
                ->column('code');
        }, 3600); // 1小时
    }

    /**
     * 获取菜单树
     *
     * @param int|null $roleId 角色ID，null表示获取所有菜单
     * @return array
     */
    public static function getMenuTree(?int $roleId = null): array
    {
        $key = $roleId ? self::PREFIX_MENU . '_' . $roleId : self::PREFIX_MENU;
        
        return Cache::remember($key, function () use ($roleId) {
            $query = \app\admin\model\Menu::where('status', 1)
                ->order('sort asc, id asc');
            
            // 如果指定了角色，只获取角色拥有的菜单
            if ($roleId !== null) {
                $menuIds = Db::table('tp_role_menu')
                    ->where('role_id', $roleId)
                    ->column('menu_id');
                if (!empty($menuIds)) {
                    $query->whereIn('id', $menuIds);
                } else {
                    return [];
                }
            }
            
            $list = $query->select()->toArray();
            return self::buildTree($list);
        }, 86400); // 24小时
    }

    /**
     * 获取角色信息
     *
     * @param int $roleId
     * @return array|null
     */
    public static function getRoleInfo(int $roleId): ?array
    {
        $key = self::PREFIX_ROLE . $roleId;
        
        return Cache::remember($key, function () use ($roleId) {
            $role = \app\admin\model\Role::find($roleId);
            return $role ? $role->toArray() : null;
        }, 3600); // 1小时
    }

    /**
     * 获取用户信息
     *
     * @param int $userId
     * @return array|null
     */
    public static function getUserInfo(int $userId): ?array
    {
        $key = self::PREFIX_USER_INFO . $userId;
        
        return Cache::remember($key, function () use ($userId) {
            $user = \app\admin\model\AdminUser::alias('u')
                ->leftJoin('department d', 'u.dept_id = d.id')
                ->leftJoin('role r', 'u.role_id = r.id')
                ->field('u.id, u.username, u.nickname, u.avatar, u.email, u.phone, u.dept_id, u.role_id, u.data_scope, d.name as dept_name, r.name as role_name')
                ->where('u.id', $userId)
                ->find();
            return $user ? $user->toArray() : null;
        }, 3600); // 1小时
    }

    /**
     * 清除用户权限缓存
     *
     * @param int $userId
     * @return bool
     */
    public static function clearUserPermission(int $userId): bool
    {
        $key = self::PREFIX_PERMISSION . $userId;
        return Cache::delete($key);
    }

    /**
     * 清除菜单树缓存
     *
     * @param int|null $roleId 角色ID，null表示清除所有菜单缓存
     * @return bool
     */
    public static function clearMenuTree(?int $roleId = null): bool
    {
        if ($roleId !== null) {
            return Cache::delete(self::PREFIX_MENU . '_' . $roleId);
        }
        
        // 清除所有菜单缓存
        Cache::delete(self::PREFIX_MENU);
        
        // 清除所有角色的菜单缓存
        $roleIds = \app\admin\model\Role::column('id');
        foreach ($roleIds as $id) {
            Cache::delete(self::PREFIX_MENU . '_' . $id);
        }
        
        return true;
    }

    /**
     * 清除角色缓存
     *
     * @param int $roleId
     * @return bool
     */
    public static function clearRoleCache(int $roleId): bool
    {
        return Cache::delete(self::PREFIX_ROLE . $roleId);
    }

    /**
     * 清除用户信息缓存
     *
     * @param int $userId
     * @return bool
     */
    public static function clearUserInfo(int $userId): bool
    {
        return Cache::delete(self::PREFIX_USER_INFO . $userId);
    }

    /**
     * 清理过期缓存
     * 用于定时任务
     *
     * @return bool
     */
    public static function clearExpiredCache(): bool
    {
        // Redis会自动清理过期key
        // 这里可以添加其他清理逻辑，如清理临时文件等
        
        // 清理runtime目录下的临时文件
        $runtimePath = runtime_path();
        $tempPath = $runtimePath . 'temp/';
        
        if (is_dir($tempPath)) {
            $files = glob($tempPath . '*');
            $now = time();
            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file)) > 86400) {
                    @unlink($file);
                }
            }
        }
        
        return true;
    }

    /**
     * 刷新所有缓存
     *
     * @return bool
     */
    public static function refreshAll(): bool
    {
        // 清除所有菜单缓存
        self::clearMenuTree();
        
        // 清除所有用户权限缓存
        $userIds = \app\admin\model\AdminUser::column('id');
        foreach ($userIds as $userId) {
            self::clearUserPermission($userId);
            self::clearUserInfo($userId);
        }
        
        // 清除所有角色缓存
        $roleIds = \app\admin\model\Role::column('id');
        foreach ($roleIds as $roleId) {
            self::clearRoleCache($roleId);
        }
        
        return true;
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
}
