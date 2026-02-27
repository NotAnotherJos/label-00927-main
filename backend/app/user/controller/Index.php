<?php
declare(strict_types=1);

namespace app\user\controller;

use app\BaseController;

/**
 * 用户控制台首页控制器
 * @OA\Tag(name="用户控制台-首页", description="用户控制台首页接口")
 */
class Index extends BaseController
{
    /**
     * 控制台首页
     * @OA\Get(
     *     path="/user",
     *     tags={"用户控制台-首页"},
     *     summary="获取控制台首页信息",
     *     security={{"bearerAuth": {}}},
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
                'app' => 'user',
                'description' => '用户控制台',
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 控制台统计
     * @OA\Get(
     *     path="/user/dashboard",
     *     tags={"用户控制台-首页"},
     *     summary="获取控制台统计数据",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function dashboard()
    {
        // 示例统计数据
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'today_visits' => 0,
                'total_visits' => 0,
                'messages' => 0,
                'notifications' => 0,
            ],
            'timestamp' => time(),
        ]);
    }
}
