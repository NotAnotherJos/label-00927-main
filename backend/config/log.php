<?php
// 日志配置文件
return [
    // 默认日志记录通道
    'default' => env('log.channel', 'file'),
    
    // 日志记录级别
    'level' => [],
    
    // 日志类型记录的通道
    'type_channel' => [
        // 'sql' => 'sql',
    ],
    
    // 关闭全局日志写入
    'close' => false,
    
    // 全局日志处理 支持闭包
    'processor' => null,
    
    // 日志通道列表
    'channels' => [
        'file' => [
            // 日志记录方式
            'type' => 'File',
            // 日志保存目录
            'path' => '',
            // 单文件日志写入
            'single' => false,
            // 独立日志级别
            'apart_level' => ['error', 'warning', 'info', 'debug'],
            // 最大日志文件数量
            'max_files' => 30,
            // 使用JSON格式记录
            'json' => true,
            // 日志输出格式化
            'format' => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],
        
        // 控制台日志通道
        'console' => [
            'type' => 'Console',
        ],
        
        // SQL日志通道
        'sql' => [
            'type' => 'File',
            'path' => runtime_path() . 'log/sql/',
            'single' => false,
            'apart_level' => [],
            'max_files' => 7,
            'json' => true,
        ],
        
        // 错误日志通道
        'error' => [
            'type' => 'File',
            'path' => runtime_path() . 'log/error/',
            'single' => false,
            'apart_level' => [],
            'max_files' => 30,
            'json' => true,
        ],
        
        // 业务日志通道
        'business' => [
            'type' => 'File',
            'path' => runtime_path() . 'log/business/',
            'single' => false,
            'apart_level' => [],
            'max_files' => 30,
            'json' => true,
        ],
        
        // 认证日志通道
        'auth' => [
            'type' => 'File',
            'path' => runtime_path() . 'log/auth/',
            'single' => false,
            'apart_level' => [],
            'max_files' => 30,
            'json' => true,
        ],
        
        // 定时任务日志通道
        'cron' => [
            'type' => 'File',
            'path' => runtime_path() . 'log/cron/',
            'single' => false,
            'apart_level' => [],
            'max_files' => 30,
            'json' => true,
        ],
    ],
];
