<?php
// 用户控制台路由
use think\facade\Route;

// 首页（不需要认证）
Route::get('user', 'user/Index/index');

// 需要JWT验证的路由组
Route::group('user', function () {
    // 控制台
    Route::get('dashboard', 'user/Index/dashboard');
    
    // 个人信息
    Route::get('profile', 'user/Profile/index');
    Route::put('profile', 'user/Profile/update');
    Route::post('profile/password', 'user/Profile/changePassword');
    
    // 消息
    Route::get('messages', 'user/Message/index');
    Route::get('messages/:id', 'user/Message/read');
    Route::post('messages/:id/read', 'user/Message/markRead');
    Route::post('messages/read-all', 'user/Message/markAllRead');
    Route::delete('messages/:id', 'user/Message/delete');
    
})->middleware(\app\admin\middleware\JwtAuth::class);
