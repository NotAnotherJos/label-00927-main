<?php
declare(strict_types=1);

namespace app\user\controller;

use app\BaseController;
use app\common\exception\BusinessException;

/**
 * 用户个人信息控制器
 * @OA\Tag(name="用户控制台-个人信息", description="用户个人信息管理接口")
 */
class Profile extends BaseController
{
    /**
     * 获取个人信息
     * @OA\Get(
     *     path="/user/profile",
     *     tags={"用户控制台-个人信息"},
     *     summary="获取当前用户个人信息",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function index()
    {
        $userId = $this->request->userId ?? 0;
        
        // 示例数据，实际从数据库获取
        $profile = [
            'id' => $userId,
            'username' => 'user',
            'nickname' => '普通用户',
            'email' => 'user@example.com',
            'phone' => '13800138000',
            'avatar' => '',
            'create_time' => date('Y-m-d H:i:s'),
        ];
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $profile,
            'timestamp' => time(),
        ]);
    }

    /**
     * 更新个人信息
     * @OA\Put(
     *     path="/user/profile",
     *     tags={"用户控制台-个人信息"},
     *     summary="更新个人信息",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nickname", type="string", description="昵称"),
     *             @OA\Property(property="email", type="string", description="邮箱"),
     *             @OA\Property(property="phone", type="string", description="手机号")
     *         )
     *     ),
     *     @OA\Response(response=200, description="成功")
     * )
     */
    public function update()
    {
        $params = $this->request->put();
        
        // 实际项目中更新数据库
        return json([
            'code' => 200,
            'msg' => '更新成功',
            'data' => $params,
            'timestamp' => time(),
        ]);
    }

    /**
     * 修改密码
     * @OA\Post(
     *     path="/user/profile/password",
     *     tags={"用户控制台-个人信息"},
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
        $params = $this->request->post();
        
        if (empty($params['old_password']) || empty($params['new_password'])) {
            throw new BusinessException('请输入密码', 400);
        }
        
        if ($params['new_password'] !== ($params['confirm_password'] ?? '')) {
            throw new BusinessException('两次输入的密码不一致', 400);
        }
        
        // 实际项目中验证原密码并更新
        return json([
            'code' => 200,
            'msg' => '密码修改成功',
            'data' => [],
            'timestamp' => time(),
        ]);
    }
}
