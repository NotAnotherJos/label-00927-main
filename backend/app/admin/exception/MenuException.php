<?php
declare(strict_types=1);

namespace app\admin\exception;

use app\common\exception\BusinessException;

/**
 * 菜单异常
 */
class MenuException extends BusinessException
{
    /**
     * 菜单不存在
     */
    public static function notFound(): self
    {
        return new self('菜单不存在', 404);
    }

    /**
     * 存在子菜单
     */
    public static function hasChildren(): self
    {
        return new self('存在子菜单，无法删除', 400);
    }

    /**
     * 菜单已被角色使用
     */
    public static function usedByRole(): self
    {
        return new self('菜单已被角色使用，无法删除', 400);
    }

    /**
     * 不能设置自己为父菜单
     */
    public static function cannotSetSelfAsParent(): self
    {
        return new self('不能设置自己为父菜单', 400);
    }
}
