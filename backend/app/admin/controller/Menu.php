<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\MenuService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 菜单管理控制器
 * @OA\Tag(name="菜单管理", description="后台菜单管理接口")
 */
class Menu extends BaseController
{
    /**
     * 菜单列表
     * @OA\Get(
     *     path="/admin/menus",
     *     tags={"菜单管理"},
     *     summary="获取菜单列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="菜单名称", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", description="状态", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $params = $this->request->get();
        $list = MenuService::getList($params);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $list,
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取菜单树
     * @OA\Get(
     *     path="/admin/menus/tree",
     *     tags={"菜单管理"},
     *     summary="获取菜单树形结构",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getTree()
    {
        $tree = MenuService::getTree();
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $tree,
            'timestamp' => time(),
        ]);
    }

    /**
     * 创建菜单
     * @OA\Post(
     *     path="/admin/menus",
     *     tags={"菜单管理"},
     *     summary="创建菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="pid", type="integer", description="父菜单ID"),
     *             @OA\Property(property="name", type="string", description="菜单名称"),
     *             @OA\Property(property="path", type="string", description="路由路径"),
     *             @OA\Property(property="component", type="string", description="组件路径"),
     *             @OA\Property(property="icon", type="string", description="图标"),
     *             @OA\Property(property="type", type="integer", description="类型：1-目录，2-菜单，3-按钮"),
     *             @OA\Property(property="permission", type="string", description="权限标识"),
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
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $menu = MenuService::create($params);
        
        return json([
            'code' => 200,
            'msg' => '创建成功',
            'data' => $menu,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新菜单
     * @OA\Put(
     *     path="/admin/menus/{id}",
     *     tags={"菜单管理"},
     *     summary="更新菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="菜单名称"),
     *             @OA\Property(property="path", type="string", description="路由路径"),
     *             @OA\Property(property="sort", type="integer", description="排序")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update($id)
    {
        $params = $this->request->put();
        $menu = MenuService::update((int)$id, $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $menu,
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除菜单
     * @OA\Delete(
     *     path="/admin/menus/{id}",
     *     tags={"菜单管理"},
     *     summary="删除菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function delete($id)
    {
        MenuService::delete((int)$id);
        
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取用户菜单
     * @OA\Get(
     *     path="/admin/menus/user",
     *     tags={"菜单管理"},
     *     summary="获取当前用户菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getUserMenus()
    {
        $userId = $this->request->userId ?? 0;
        $menus = MenuService::getUserMenus($userId);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $menus,
            'timestamp' => time(),
        ]);
    }
}
