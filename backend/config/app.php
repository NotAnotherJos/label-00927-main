<?php
// 应用配置文件
return [
    // 应用地址
    'app_host'         => env('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 应用类库后缀
    'class_suffix'     => false,
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'home',
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 是否启用控制器类后缀
    'controller_suffix' => false,
    // 操作方法后缀
    'action_suffix'    => '',
    // 默认的应用访问地址
    'app_express'      => true,
    // 应用映射（可选）
    'app_map'          => [],
    // 域名绑定（可选）
    'domain_bind'      => [],
    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',
    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => 'app\common\exception\ExceptionHandle',
];
