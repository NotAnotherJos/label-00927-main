<?php
declare(strict_types=1);

namespace app\home\controller;

use app\BaseController;

/**
 * 前台文章控制器
 * @OA\Tag(name="前台-文章", description="前台文章接口")
 */
class Article extends BaseController
{
    /**
     * 文章列表
     * @OA\Get(
     *     path="/home/articles",
     *     tags={"前台-文章"},
     *     summary="获取文章列表",
     *     @OA\Parameter(name="page", in="query", description="页码", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="limit", in="query", description="每页数量", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="category_id", in="query", description="分类ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $page = (int)$this->request->get('page', 1);
        $limit = (int)$this->request->get('limit', 15);
        
        // 示例数据，实际项目中从数据库获取
        $list = [
            [
                'id' => 1,
                'title' => '欢迎使用ThinkPHP8多应用系统',
                'summary' => '这是一个基于ThinkPHP8框架开发的多应用分层架构系统',
                'category' => '系统公告',
                'author' => '管理员',
                'views' => 100,
                'create_time' => date('Y-m-d H:i:s'),
            ],
        ];
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'list' => $list,
                'total' => count($list),
                'page' => $page,
                'limit' => $limit,
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 文章详情
     * @OA\Get(
     *     path="/home/articles/{id}",
     *     tags={"前台-文章"},
     *     summary="获取文章详情",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function read($id)
    {
        // 示例数据
        $article = [
            'id' => (int)$id,
            'title' => '欢迎使用ThinkPHP8多应用系统',
            'content' => '<p>这是一个基于ThinkPHP8框架开发的多应用分层架构系统，支持RBAC权限管理、数据权限控制、JWT认证等功能。</p>',
            'category' => '系统公告',
            'author' => '管理员',
            'views' => 100,
            'create_time' => date('Y-m-d H:i:s'),
        ];
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $article,
            'timestamp' => time(),
        ]);
    }
}
