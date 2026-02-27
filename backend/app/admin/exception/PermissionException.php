<?php
declare(strict_types=1);

namespace app\admin\exception;

use app\common\exception\BusinessException;

/**
 * 权限异常
 */
class PermissionException extends BusinessException
{
    /**
     * 无权限访问
     */
    public static function noPermission(): self
    {
        return new self('无权限访问', 403);
    }

    /**
     * 无数据权限
     */
    public static function noDataPermission(): self
    {
        return new self('无数据访问权限', 403);
    }

    /**
     * 权限不存在
     */
    public static function notFound(): self
    {
        return new self('权限不存在', 404);
    }

    /**
     * 角色不存在
     */
    public static function roleNotFound(): self
    {
        return new self('角色不存在', 404);
    }

    /**
     * 不能删除有用户的角色
     */
    public static function roleHasUsers(): self
    {
        return new self('该角色下存在用户，无法删除', 400);
    }

    /**
     * 不能删除有子权限的权限
     */
    public static function hasChildren(): self
    {
        return new self('存在子权限，无法删除', 400);
    }
}
