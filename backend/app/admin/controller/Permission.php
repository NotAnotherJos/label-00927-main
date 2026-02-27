<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\PermissionService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 权限管理控制器
 * @OA\Tag(name="权限管理", description="后台权限管理接口")
 */
class Permission extends BaseController
{
    /**
     * 权限列表
     * @OA\Get(
     *     path="/admin/permissions",
     *     tags={"权限管理"},
     *     summary="获取权限列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="权限名称", @OA\Schema(type="string")),
     *     @OA\Parameter(name="type", in="query", description="类型：1-菜单，2-按钮", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $params = $this->request->get();
        $list = PermissionService::getList($params);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $list,
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取权限树
     * @OA\Get(
     *     path="/admin/permissions/tree",
     *     tags={"权限管理"},
     *     summary="获取权限树形结构",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getTree()
    {
        $tree = PermissionService::getTree();
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $tree,
            'timestamp' => time(),
        ]);
    }

    /**
     * 创建权限
     * @OA\Post(
     *     path="/admin/permissions",
     *     tags={"权限管理"},
     *     summary="创建权限",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name", "code"},
     *             @OA\Property(property="pid", type="integer", description="父权限ID"),
     *             @OA\Property(property="name", type="string", description="权限名称"),
     *             @OA\Property(property="code", type="string", description="权限编码"),
     *             @OA\Property(property="type", type="integer", description="类型：1-菜单，2-按钮"),
     *             @OA\Property(property="path", type="string", description="路由路径"),
     *             @OA\Property(property="component", type="string", description="组件路径"),
     *             @OA\Property(property="icon", type="string", description="图标"),
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
            'code' => 'require|max:100',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $permission = PermissionService::create($params);
        
        return json([
            'code' => 200,
            'msg' => '创建成功',
            'data' => $permission,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新权限
     * @OA\Put(
     *     path="/admin/permissions/{id}",
     *     tags={"权限管理"},
     *     summary="更新权限",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="权限名称"),
     *             @OA\Property(property="code", type="string", description="权限编码"),
     *             @OA\Property(property="sort", type="integer", description="排序")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update($id)
    {
        $params = $this->request->put();
        $permission = PermissionService::update((int)$id, $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $permission,
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除权限
     * @OA\Delete(
     *     path="/admin/permissions/{id}",
     *     tags={"权限管理"},
     *     summary="删除权限",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function delete($id)
    {
        PermissionService::delete((int)$id);
        
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取用户权限
     * @OA\Get(
     *     path="/admin/permissions/user",
     *     tags={"权限管理"},
     *     summary="获取当前用户权限列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getUserPermissions()
    {
        $userId = $this->request->userId ?? 0;
        $permissions = PermissionService::getUserPermissions($userId);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $permissions,
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取角色权限
     * @OA\Get(
     *     path="/admin/permissions/role/{roleId}",
     *     tags={"权限管理"},
     *     summary="获取角色权限ID列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="roleId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function getRolePermissions($roleId)
    {
        $permissionIds = PermissionService::getRolePermissionIds((int)$roleId);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $permissionIds,
            'timestamp' => time(),
        ]);
    }

    /**
     * 设置角色权限
     * @OA\Post(
     *     path="/admin/permissions/role/{roleId}",
     *     tags={"权限管理"},
     *     summary="设置角色权限",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="roleId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"permission_ids"},
     *             @OA\Property(property="permission_ids", type="array", @OA\Items(type="integer"), description="权限ID列表")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function setRolePermissions($roleId)
    {
        $permissionIds = $this->request->post('permission_ids', []);
        
        PermissionService::setRolePermissions((int)$roleId, $permissionIds);
        
        return json([
            'code' => 200,
            'msg' => '设置成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
