<?php
// 后台管理路由
use think\facade\Route;

// 登录接口（不需要JWT验证）
Route::post('admin/login', 'admin/Auth/login');

// Swagger文档
Route::get('admin/swagger', 'admin/Swagger/index');
Route::get('admin/swagger/ui', 'admin/Swagger/ui');

// 需要JWT验证的路由组
Route::group('admin', function () {
    // 用户管理
    Route::get('users', 'admin/User/index');
    Route::post('users', 'admin/User/save');
    Route::put('users/:id', 'admin/User/update');
    Route::delete('users/:id', 'admin/User/delete');
    Route::post('users/:id/status', 'admin/User/setStatus');
    
    // 角色管理
    Route::get('roles', 'admin/Role/index');
    Route::get('roles/all', 'admin/Role/all');
    Route::post('roles', 'admin/Role/save');
    Route::get('roles/:id', 'admin/Role/read');
    Route::put('roles/:id', 'admin/Role/update');
    Route::delete('roles/:id', 'admin/Role/delete');
    Route::post('roles/:id/menus', 'admin/Role/setMenus');
    
    // 权限管理
    Route::get('permissions', 'admin/Permission/index');
    Route::get('permissions/tree', 'admin/Permission/getTree');
    Route::get('permissions/user', 'admin/Permission/getUserPermissions');
    Route::get('permissions/role/:roleId', 'admin/Permission/getRolePermissions');
    Route::post('permissions', 'admin/Permission/save');
    Route::put('permissions/:id', 'admin/Permission/update');
    Route::delete('permissions/:id', 'admin/Permission/delete');
    Route::post('permissions/role/:roleId', 'admin/Permission/setRolePermissions');
    
    // 菜单管理
    Route::get('menus', 'admin/Menu/index');
    Route::get('menus/tree', 'admin/Menu/getTree');
    Route::get('menus/user', 'admin/Menu/getUserMenus');
    Route::post('menus', 'admin/Menu/save');
    Route::put('menus/:id', 'admin/Menu/update');
    Route::delete('menus/:id', 'admin/Menu/delete');
    
    // 部门管理
    Route::get('departments', 'admin/Department/index');
    Route::get('departments/tree', 'admin/Department/getTree');
    Route::get('departments/:id', 'admin/Department/read');
    Route::post('departments', 'admin/Department/save');
    Route::put('departments/:id', 'admin/Department/update');
    Route::delete('departments/:id', 'admin/Department/delete');
    
    // 操作日志
    Route::get('logs', 'admin/Log/index');
    Route::get('logs/:id', 'admin/Log/read');
    
    // 个人信息
    Route::get('profile', 'admin/Profile/index');
    Route::put('profile', 'admin/Profile/update');
    Route::post('profile/password', 'admin/Profile/changePassword');
    Route::get('profile/menus', 'admin/Profile/menus');
    
    // Token刷新
    Route::post('refresh', 'admin/Auth/refresh');
    
})->middleware([
    \app\admin\middleware\JwtAuth::class,
    \app\admin\middleware\PermissionCheck::class,
]);
