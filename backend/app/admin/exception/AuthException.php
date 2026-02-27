<?php
declare(strict_types=1);

namespace app\admin\exception;

use app\common\exception\BusinessException;

/**
 * 认证异常
 */
class AuthException extends BusinessException
{
    /**
     * Token无效
     */
    public static function invalidToken(): self
    {
        return new self('Token无效或已过期', 401);
    }

    /**
     * Token已过期
     */
    public static function tokenExpired(): self
    {
        return new self('Token已过期，请重新登录', 401);
    }

    /**
     * 未登录
     */
    public static function notLogin(): self
    {
        return new self('请先登录', 401);
    }

    /**
     * 用户名或密码错误
     */
    public static function invalidCredentials(): self
    {
        return new self('用户名或密码错误', 401);
    }

    /**
     * 账号已禁用
     */
    public static function accountDisabled(): self
    {
        return new self('账号已被禁用，请联系管理员', 403);
    }
}
