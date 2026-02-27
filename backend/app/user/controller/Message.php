<?php
declare(strict_types=1);

namespace app\user\controller;

use app\BaseController;

/**
 * 用户消息控制器
 * @OA\Tag(name="用户控制台-消息", description="用户消息管理接口")
 */
class Message extends BaseController
{
    /**
     * 消息列表
     * @OA\Get(
     *     path="/user/messages",
     *     tags={"用户控制台-消息"},
     *     summary="获取消息列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="page", in="query", description="页码", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="limit", in="query", description="每页数量", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="is_read", in="query", description="是否已读", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $page = (int)$this->request->get('page', 1);
        $limit = (int)$this->request->get('limit', 15);
        
        // 示例数据
        $list = [];
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'list' => $list,
                'total' => 0,
                'page' => $page,
                'limit' => $limit,
                'unread_count' => 0,
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 消息详情
     * @OA\Get(
     *     path="/user/messages/{id}",
     *     tags={"用户控制台-消息"},
     *     summary="获取消息详情",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function read($id)
    {
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'id' => (int)$id,
                'title' => '',
                'content' => '',
                'is_read' => 1,
                'create_time' => date('Y-m-d H:i:s'),
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 标记已读
     * @OA\Post(
     *     path="/user/messages/{id}/read",
     *     tags={"用户控制台-消息"},
     *     summary="标记消息为已读",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function markRead($id)
    {
        return json([
            'code' => 200,
            'msg' => '标记成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 全部标记已读
     * @OA\Post(
     *     path="/user/messages/read-all",
     *     tags={"用户控制台-消息"},
     *     summary="全部标记为已读",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function markAllRead()
    {
        return json([
            'code' => 200,
            'msg' => '全部标记成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除消息
     * @OA\Delete(
     *     path="/user/messages/{id}",
     *     tags={"用户控制台-消息"},
     *     summary="删除消息",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function delete($id)
    {
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
