<?php
// JWT配置文件
return [
    // JWT密钥
    'secret' => env('jwt.secret', 'your-secret-key-change-in-production'),
    // Token有效期（秒）2小时
    'ttl' => env('jwt.ttl', 7200),
    // Token刷新有效期（秒）7天
    'refresh_ttl' => env('jwt.refresh_ttl', 604800),
    // 算法
    'algorithm' => 'HS256',
    // Token前缀
    'prefix' => 'Bearer ',
];
