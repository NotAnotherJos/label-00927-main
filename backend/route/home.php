<?php
// 前台路由
use think\facade\Route;

// 首页
Route::get('/', 'home/Index/index');
Route::get('home', 'home/Index/index');
Route::get('home/status', 'home/Index/status');

// 文章
Route::get('home/articles', 'home/Article/index');
Route::get('home/articles/:id', 'home/Article/read');

// 分类
Route::get('home/categories', 'home/Category/index');
