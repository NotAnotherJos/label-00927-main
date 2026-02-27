<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\model\AdminUser;
use app\common\exception\BusinessException;
use app\common\service\CacheService;
use app\common\service\LogService;
use think\facade\Validate;

/**
 * 个人信息控制器
 * @OA\Tag(name="个人中心", description="个人信息管理接口")
 */
class Profile extends BaseController
{
    /**
     * 获取个人信息
     * @OA\Get(
     *     path="/admin/profile",
     *     tags={"个人中心"},
     *     summary="获取当前用户信息",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $userId = $this->request->userId ?? null;
        
        if (!$userId) {
            throw new BusinessException('未登录', 401);
        }
        
        $user = AdminUser::alias('u')
            ->leftJoin('department d', 'u.dept_id = d.id')
            ->leftJoin('role r', 'u.role_id = r.id')
            ->field('u.id, u.username, u.nickname, u.avatar, u.email, u.phone, u.dept_id, u.role_id, u.data_scope, u.last_login_time, u.last_login_ip, d.name as dept_name, r.name as role_name')
            ->where('u.id', $userId)
            ->find();
        
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        // 获取用户权限
        $permissions = CacheService::getUserPermissions($userId);
        
        $data = $user->toArray();
        $data['permissions'] = $permissions;
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $data,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新个人信息
     * @OA\Put(
     *     path="/admin/profile",
     *     tags={"个人中心"},
     *     summary="更新个人信息",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nickname", type="string", description="昵称"),
     *             @OA\Property(property="email", type="string", description="邮箱"),
     *             @OA\Property(property="phone", type="string", description="手机号"),
     *             @OA\Property(property="avatar", type="string", description="头像URL")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update()
    {
        $userId = $this->request->userId ?? null;
        
        if (!$userId) {
            throw new BusinessException('未登录', 401);
        }
        
        $user = AdminUser::find($userId);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        $params = $this->request->put();
        
        $validate = Validate::rule([
            'nickname' => 'max:50',
            'email' => 'email',
            'phone' => 'mobile',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        // 只允许更新部分字段
        $allowFields = ['nickname', 'email', 'phone', 'avatar'];
        foreach ($allowFields as $field) {
            if (isset($params[$field])) {
                $user->$field = $params[$field];
            }
        }
        
        $user->save();
        
        // 清除用户信息缓存
        CacheService::clearUserInfo($userId);
        
        LogService::record('update', '更新个人信息', $params);
        
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $user->toArray(),
            'timestamp' => time(),
        ]);
    }

    /**
     * 修改密码
     * @OA\Post(
     *     path="/admin/profile/password",
     *     tags={"个人中心"},
     *     summary="修改密码",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"old_password", "new_password", "confirm_password"},
     *             @OA\Property(property="old_password", type="string", description="原密码"),
     *             @OA\Property(property="new_password", type="string", description="新密码"),
     *             @OA\Property(property="confirm_password", type="string", description="确认密码")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function changePassword()
    {
        $userId = $this->request->userId ?? null;
        
        if (!$userId) {
            throw new BusinessException('未登录', 401);
        }
        
        $user = AdminUser::find($userId);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        $params = $this->request->post();
        
        $validate = Validate::rule([
            'old_password' => 'require',
            'new_password' => 'require|min:6|max:32',
            'confirm_password' => 'require|confirm:new_password',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        // 验证原密码
        if (!password_verify($params['old_password'], $user->password)) {
            throw new BusinessException('原密码错误', 400);
        }
        
        // 更新密码
        $user->password = password_hash($params['new_password'], PASSWORD_DEFAULT);
        $user->save();
        
        LogService::record('update', '修改密码');
        
        return json([
            'code' => 200,
            'msg' => '密码修改成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 获取用户菜单
     * @OA\Get(
     *     path="/admin/profile/menus",
     *     tags={"个人中心"},
     *     summary="获取当前用户菜单",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function menus()
    {
        $userId = $this->request->userId ?? null;
        
        if (!$userId) {
            throw new BusinessException('未登录', 401);
        }
        
        $user = AdminUser::find($userId);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        // 超级管理员获取所有菜单
        $roleId = $user->role_id == 1 ? null : $user->role_id;
        $menus = CacheService::getMenuTree($roleId);
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $menus,
            'timestamp' => time(),
        ]);
    }
}
