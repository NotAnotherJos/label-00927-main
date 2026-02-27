<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\RoleService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 角色管理控制器
 * @OA\Tag(name="角色管理", description="后台角色管理接口")
 */
class Role extends BaseController
{
    /**
     * 角色列表
     * @OA\Get(
     *     path="/admin/roles",
     *     tags={"角色管理"},
     *     summary="获取角色列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="page", in="query", description="页码", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", description="每页数量", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="name", in="query", description="角色名称", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $params = $this->request->get();
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;
        
        $result = RoleService::getList((int)$page, (int)$limit, $params);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $result,
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取所有角色（下拉选择用）
     * @OA\Get(
     *     path="/admin/roles/all",
     *     tags={"角色管理"},
     *     summary="获取所有角色",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function all()
    {
        $list = RoleService::getAll();
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $list,
            'timestamp' => time(),
        ]);
    }

    /**
     * 角色详情
     * @OA\Get(
     *     path="/admin/roles/{id}",
     *     tags={"角色管理"},
     *     summary="获取角色详情",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function read($id)
    {
        $role = RoleService::getDetail((int)$id);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $role,
            'timestamp' => time(),
        ]);
    }

    /**
     * 创建角色
     * @OA\Post(
     *     path="/admin/roles",
     *     tags={"角色管理"},
     *     summary="创建角色",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name", "code"},
     *             @OA\Property(property="name", type="string", description="角色名称"),
     *             @OA\Property(property="code", type="string", description="角色编码"),
     *             @OA\Property(property="data_scope", type="integer", description="数据权限：1-全部，2-本部门，3-本部门及子部门，4-本人，5-自定义"),
     *             @OA\Property(property="remark", type="string", description="备注"),
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
            'code' => 'require|max:50|unique:role',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $role = RoleService::create($params);
        
        return json([
            'code' => 200,
            'msg' => '创建成功',
            'data' => $role,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新角色
     * @OA\Put(
     *     path="/admin/roles/{id}",
     *     tags={"角色管理"},
     *     summary="更新角色",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="角色名称"),
     *             @OA\Property(property="data_scope", type="integer", description="数据权限"),
     *             @OA\Property(property="remark", type="string", description="备注"),
     *             @OA\Property(property="sort", type="integer", description="排序")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update($id)
    {
        $params = $this->request->put();
        $role = RoleService::update((int)$id, $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $role,
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除角色
     * @OA\Delete(
     *     path="/admin/roles/{id}",
     *     tags={"角色管理"},
     *     summary="删除角色",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function delete($id)
    {
        RoleService::delete((int)$id);
        
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 设置角色菜单
     * @OA\Post(
     *     path="/admin/roles/{id}/menus",
     *     tags={"角色管理"},
     *     summary="设置角色菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"menu_ids"},
     *             @OA\Property(property="menu_ids", type="array", @OA\Items(type="integer"), description="菜单ID列表")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function setMenus($id)
    {
        $menuIds = $this->request->post('menu_ids', []);
        
        RoleService::setRoleMenus((int)$id, $menuIds);
        
        return json([
            'code' => 200,
            'msg' => '设置成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
