<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\AdminUser;
use app\common\exception\BusinessException;
use app\common\service\LogService;
use app\common\service\AppLogService;
use extend\JwtUtil;

/**
 * 认证服务
 */
class AuthService
{
    /**
     * 用户登录
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @return array
     * @throws BusinessException
     */
    public static function login(string $username, string $password): array
    {
        // 查找用户
        $user = AdminUser::where('username', $username)->find();
        if (!$user) {
            // 记录登录失败日志
            AppLogService::auth('login', 0, false, '用户不存在');
            throw new BusinessException('用户名或密码错误', 401);
        }
        
        // 验证密码
        if (!password_verify($password, $user->password)) {
            // 记录登录失败日志
            AppLogService::auth('login', $user->id, false, '密码错误');
            throw new BusinessException('用户名或密码错误', 401);
        }
        
        // 检查状态
        if ($user->status != 1) {
            // 记录登录失败日志
            AppLogService::auth('login', $user->id, false, '账号已禁用');
            throw new BusinessException('账号已被禁用', 403);
        }
        
        // 生成Token
        $payload = [
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'data_scope' => $user->data_scope ?? 1,
        ];
        $token = JwtUtil::generateToken($payload);
        
        // 更新最后登录时间
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->last_login_ip = \think\facade\Request::ip();
        $user->save();
        
        // 记录登录成功日志
        AppLogService::auth('login', $user->id, true);
        LogService::record('login', "用户{$username}登录系统", ['username' => $username], $user->id);
        
        // 返回用户信息（不包含密码）
        $userData = $user->toArray();
        unset($userData['password']);
        
        return [
            'token' => $token,
            'user' => $userData,
        ];
    }

    /**
     * 用户登出
     *
     * @param int $userId
     * @return bool
     */
    public static function logout(int $userId): bool
    {
        AppLogService::auth('logout', $userId, true);
        LogService::record('logout', '用户登出系统', [], $userId);
        return true;
    }
}
