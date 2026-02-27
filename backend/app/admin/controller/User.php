<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\UserService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 用户管理控制器
 * @OA\Tag(name="用户管理", description="后台用户管理接口")
 */
class User extends BaseController
{
    /**
     * 用户列表
     * @OA\Get(
     *     path="/admin/users",
     *     tags={"用户管理"},
     *     summary="获取用户列表",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="page", in="query", description="页码", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", description="每页数量", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $params = $this->request->get();
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;
        
        $result = UserService::getList($page, $limit, $params);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $result,
            'timestamp' => time(),
        ]);
    }

    /**
     * 创建用户
     */
    public function save()
    {
        $params = $this->request->post();
        
        $validate = Validate::rule([
            'username' => 'require|max:50|unique:admin_user',
            'password' => 'require|min:6',
            'nickname' => 'require|max:50',
            'email' => 'email',
            'phone' => 'mobile',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $user = UserService::create($params);
        
        return json([
            'code' => 200,
            'msg' => '创建成功',
            'data' => $user,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新用户
     */
    public function update($id)
    {
        $params = $this->request->put();
        
        $validate = Validate::rule([
            'nickname' => 'max:50',
            'email' => 'email',
            'phone' => 'mobile',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $user = UserService::update($id, $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $user,
            'timestamp' => time(),
        ]);
    }

    /**
     * 删除用户
     */
    public function delete($id)
    {
        UserService::delete($id);
        
        return json([
            'code' => 200,
            'msg' => '删除成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 设置用户状态
     */
    public function setStatus($id)
    {
        $status = $this->request->post('status', 1);
        
        UserService::setStatus($id, $status);
        
        return json([
            'code' => 200,
            'msg' => '操作成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
