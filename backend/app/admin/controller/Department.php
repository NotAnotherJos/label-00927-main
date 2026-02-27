<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\DepartmentService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 部门管理控制器
 * @OA\Tag(name="部门管理", description="后台部门管理接口")
 */
class Department extends BaseController
{
    /**
     * 部门列表
     * @OA\Get(
     *     path="/admin/departments",
     *     tags={"部门管理"},
     *     summary="获取部门列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="部门名称", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", description="状态", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $params = $this->request->get();
        $list = DepartmentService::getList($params);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $list,
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取部门树
     * @OA\Get(
     *     path="/admin/departments/tree",
     *     tags={"部门管理"},
     *     summary="获取部门树形结构",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getTree()
    {
        $tree = DepartmentService::getTree();
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $tree,
            'timestamp' => time(),
        ]);
    }

    /**
     * 部门详情
     * @OA\Get(
     *     path="/admin/departments/{id}",
     *     tags={"部门管理"},
     *     summary="获取部门详情",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function read($id)
    {
        $dept = DepartmentService::getDetail((int)$id);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $dept,
            'timestamp' => time(),
        ]);
    }

    /**
     * 创建部门
     * @OA\Post(
     *     path="/admin/departments",
     *     tags={"部门管理"},
     *     summary="创建部门",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", description="父部门ID"),
     *             @OA\Property(property="name", type="string", description="部门名称"),
     *             @OA\Property(property="code", type="string", description="部门编码"),
     *             @OA\Property(property="leader", type="string", description="负责人"),
     *             @OA\Property(property="phone", type="string", description="联系电话"),
     *             @OA\Property(property="email", type="string", description="邮箱"),
     *             @OA\Property(property="sort", type="integer", description="排序")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function save()
    {
        $params = $this->request->post();
        
        $validate = Validate::rule([
            'name' => 'require|max:50',
            'email' => 'email',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $dept = DepartmentService::create($params);
        
        return json([
            'code' => 200,
            'msg' => '创建成功',
            'data' => $dept,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新部门
     * @OA\Put(
     *     path="/admin/departments/{id}",
     *     tags={"部门管理"},
     *     summary="更新部门",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="部门名称"),
     *             @OA\Property(property="leader", type="string", description="负责人"),
     *             @OA\Property(property="sort", type="integer", description="排序")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update($id)
    {
        $params = $this->request->put();
        $dept = DepartmentService::update((int)$id, $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $dept,
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除部门
     * @OA\Delete(
     *     path="/admin/departments/{id}",
     *     tags={"部门管理"},
     *     summary="删除部门",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function delete($id)
    {
        DepartmentService::delete((int)$id);
        
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
