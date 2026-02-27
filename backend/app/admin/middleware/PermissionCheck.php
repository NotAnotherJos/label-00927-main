<?php
declare(strict_types=1);

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use app\admin\service\PermissionService;
use app\admin\exception\AuthException;
use app\admin\exception\PermissionException;

/**
 * 权限验证中间件
 * 支持菜单权限和按钮级权限校验
 */
class PermissionCheck
{
    /**
     * 路由权限映射表
     * 格式：'路由规则' => '权限标识'
     */
    protected array $routePermissions = [
        // 用户管理
        'GET:admin/users' => 'system:user:list',
        'POST:admin/users' => 'system:user:add',
        'PUT:admin/users/*' => 'system:user:edit',
        'DELETE:admin/users/*' => 'system:user:delete',
        'POST:admin/users/*/status' => 'system:user:edit',
        
        // 角色管理
        'GET:admin/roles' => 'system:role:list',
        'POST:admin/roles' => 'system:role:add',
        'PUT:admin/roles/*' => 'system:role:edit',
        'DELETE:admin/roles/*' => 'system:role:delete',
        'POST:admin/roles/*/menus' => 'system:role:edit',
        
        // 权限管理
        'GET:admin/permissions' => 'system:permission:list',
        'POST:admin/permissions' => 'system:permission:add',
        'PUT:admin/permissions/*' => 'system:permission:edit',
        'DELETE:admin/permissions/*' => 'system:permission:delete',
        
        // 菜单管理
        'GET:admin/menus' => 'system:menu:list',
        'POST:admin/menus' => 'system:menu:add',
        'PUT:admin/menus/*' => 'system:menu:edit',
        'DELETE:admin/menus/*' => 'system:menu:delete',
        
        // 部门管理
        'GET:admin/departments' => 'system:dept:list',
        'POST:admin/departments' => 'system:dept:add',
        'PUT:admin/departments/*' => 'system:dept:edit',
        'DELETE:admin/departments/*' => 'system:dept:delete',
        
        // 日志管理
        'GET:admin/logs' => 'system:log:list',
    ];

    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permission 权限标识（可选，优先使用）
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $permission = ''): Response
    {
        $userId = $request->userId ?? null;
        
        if (!$userId) {
            throw AuthException::notLogin();
        }
        
        // 超级管理员跳过权限检查
        if ($request->roleId == 1) {
            return $next($request);
        }
        
        // 获取需要检查的权限标识
        $requiredPermission = $permission ?: $this->getRoutePermission($request);
        
        // 如果没有找到对应的权限标识，则跳过检查
        if (empty($requiredPermission)) {
            return $next($request);
        }
        
        // 检查权限
        if (!PermissionService::hasPermission($userId, $requiredPermission)) {
            throw PermissionException::noPermission();
        }
        
        return $next($request);
    }

    /**
     * 根据路由获取权限标识
     *
     * @param Request $request
     * @return string
     */
    protected function getRoutePermission(Request $request): string
    {
        $method = strtoupper($request->method());
        $path = trim($request->pathinfo(), '/');
        
        // 精确匹配
        $routeKey = "{$method}:{$path}";
        if (isset($this->routePermissions[$routeKey])) {
            return $this->routePermissions[$routeKey];
        }
        
        // 通配符匹配
        foreach ($this->routePermissions as $pattern => $perm) {
            if ($this->matchRoute($routeKey, $pattern)) {
                return $perm;
            }
        }
        
        return '';
    }

    /**
     * 路由匹配
     *
     * @param string $route 实际路由
     * @param string $pattern 匹配模式
     * @return bool
     */
    protected function matchRoute(string $route, string $pattern): bool
    {
        // 将通配符转换为正则表达式
        $regex = str_replace(['*', '/'], ['[^/]+', '\/'], $pattern);
        return (bool)preg_match("/^{$regex}$/", $route);
    }
}
