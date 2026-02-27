<?php
declare(strict_types=1);

namespace app\admin\exception;

use app\common\exception\BusinessException;

/**
 * 部门异常
 */
class DepartmentException extends BusinessException
{
    /**
     * 部门不存在
     */
    public static function notFound(): self
    {
        return new self('部门不存在', 404);
    }

    /**
     * 部门编码已存在
     */
    public static function codeExists(): self
    {
        return new self('部门编码已存在', 400);
    }

    /**
     * 存在子部门
     */
    public static function hasChildren(): self
    {
        return new self('存在子部门，无法删除', 400);
    }

    /**
     * 部门下存在用户
     */
    public static function hasUsers(): self
    {
        return new self('部门下存在用户，无法删除', 400);
    }

    /**
     * 不能设置自己为父部门
     */
    public static function cannotSetSelfAsParent(): self
    {
        return new self('不能设置自己为父部门', 400);
    }
}
