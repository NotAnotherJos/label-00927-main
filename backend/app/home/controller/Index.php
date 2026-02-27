<?php
declare(strict_types=1);

namespace app\home\controller;

use app\BaseController;

/**
 * 前台首页控制器
 * @OA\Tag(name="前台-首页", description="前台首页接口")
 */
class Index extends BaseController
{
    /**
     * 首页信息
     * @OA\Get(
     *     path="/",
     *     tags={"前台-首页"},
     *     summary="获取首页信息",
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'name' => 'ThinkPHP8多应用系统',
                'version' => '1.0.0',
                'app' => 'home',
                'description' => '基于ThinkPHP8框架的多应用分层架构通用系统',
                'features' => [
                    'JWT认证',
                    'RBAC权限',
                    '数据权限',
                    'Swagger文档',
                    'Redis缓存',
                    '定时任务',
                ],
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 系统状态
     * @OA\Get(
     *     path="/home/status",
     *     tags={"前台-首页"},
     *     summary="获取系统状态",
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function status()
    {
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'status' => 'running',
                'php_version' => PHP_VERSION,
                'framework' => 'ThinkPHP 8.0',
                'server_time' => date('Y-m-d H:i:s'),
            ],
            'timestamp' => time(),
        ]);
    }
}
