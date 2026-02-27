<?php
declare(strict_types=1);

namespace app\home\controller;

use app\BaseController;

/**
 * 前台分类控制器
 * @OA\Tag(name="前台-分类", description="前台分类接口")
 */
class Category extends BaseController
{
    /**
     * 分类列表
     * @OA\Get(
     *     path="/home/categories",
     *     tags={"前台-分类"},
     *     summary="获取分类列表",
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        // 示例数据
        $list = [
            ['id' => 1, 'name' => '系统公告', 'sort' => 1],
            ['id' => 2, 'name' => '技术文章', 'sort' => 2],
            ['id' => 3, 'name' => '使用教程', 'sort' => 3],
        ];
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $list,
            'timestamp' => time(),
        ]);
    }
}
