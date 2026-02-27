<?php
declare(strict_types=1);

namespace app\admin\exception;

use app\common\exception\BusinessException;

/**
 * 用户异常
 */
class UserException extends BusinessException
{
    /**
     * 用户不存在
     */
    public static function notFound(): self
    {
        return new self('用户不存在', 404);
    }

    /**
     * 用户名已存在
     */
    public static function usernameExists(): self
    {
        return new self('用户名已存在', 400);
    }

    /**
     * 不能删除自己
     */
    public static function cannotDeleteSelf(): self
    {
        return new self('不能删除当前登录用户', 400);
    }

    /**
     * 不能禁用自己
     */
    public static function cannotDisableSelf(): self
    {
        return new self('不能禁用当前登录用户', 400);
    }

    /**
     * 原密码错误
     */
    public static function oldPasswordError(): self
    {
        return new self('原密码错误', 400);
    }

    /**
     * 两次密码不一致
     */
    public static function passwordNotMatch(): self
    {
        return new self('两次输入的密码不一致', 400);
    }
}
