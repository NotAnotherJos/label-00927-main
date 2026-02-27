<?php
// 全局中间件定义文件
return [
    // 多应用模式（必须放在最前面）
    \think\app\MultiApp::class,
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class,
    // 跨域处理
    \middleware\Cors::class,
    // 请求日志
    // \middleware\RequestLog::class,
];
