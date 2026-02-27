<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\admin\service\AuthService;
use app\common\exception\BusinessException;
use think\facade\Validate;

/**
 * 认证控制器
 * @OA\Tag(name="认证", description="后台登录认证接口")
 */
class Auth extends BaseController
{
    /**
     * 用户登录
     * @OA\Post(
     *     path="/admin/login",
     *     tags={"认证"},
     *     summary="用户登录",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", description="用户名"),
     *             @OA\Property(property="password", type="string", description="密码")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="登录成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="user", type="object")
     *             ),
     *             @OA\Property(property="timestamp", type="integer")
     *         )
     *     )
     * )
     */
    public function login()
    {
        $params = $this->request->post();
        
        // 参数验证
        $validate = Validate::rule([
            'username' => 'require|max:50',
            'password' => 'require|min:6',
        ])->message([
            'username.require' => '用户名不能为空',
            'username.max' => '用户名长度不能超过50个字符',
            'password.require' => '密码不能为空',
            'password.min' => '密码长度不能少于6个字符',
        ]);
        
        if (!$validate->check($params)) {
            throw new BusinessException($validate->getError(), 400);
        }
        
        $result = AuthService::login($params['username'], $params['password']);
        
        return json([
            'code' => 200,
            'msg' => '登录成功',
            'data' => $result,
            'timestamp' => time(),
        ]);
    }

    /**
     * 刷新Token
     * @OA\Post(
     *     path="/admin/refresh",
     *     tags={"认证"},
     *     summary="刷新Token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="刷新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string")
     *             ),
     *             @OA\Property(property="timestamp", type="integer")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        $token = $this->request->header('Authorization', '');
        $token = str_replace('Bearer ', '', $token);
        
        $newToken = \extend\JwtUtil::refreshToken($token);
        if ($newToken === false) {
            throw new BusinessException('Token刷新失败', 401);
        }
        
        return json([
            'code' => 200,
            'msg' => '刷新成功',
            'data' => ['token' => $newToken],
            'timestamp' => time(),
        ]);
    }
}
