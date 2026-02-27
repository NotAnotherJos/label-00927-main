<?php
// Swagger配置文件
return [
    // Swagger文档标题
    'title' => 'ThinkPHP8 API文档',
    // 文档描述
    'description' => '基于ThinkPHP8的多应用分层架构系统API文档',
    // API版本
    'version' => '1.0.0',
    // 访问路径
    'path' => '/swagger',
    // 扫描目录
    'scan_paths' => [
        app_path('admin'),
        app_path('home'),
        app_path('user'),
    ],
];
