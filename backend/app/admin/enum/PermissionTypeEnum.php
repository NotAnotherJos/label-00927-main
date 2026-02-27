<?php
declare(strict_types=1);

namespace app\admin\enum;

/**
 * 权限类型枚举
 */
enum PermissionTypeEnum: int
{
    /** 菜单权限 */
    case MENU = 1;
    
    /** 按钮权限 */
    case BUTTON = 2;

    /**
     * 获取描述
     */
    public function label(): string
    {
        return match($this) {
            self::MENU => '菜单权限',
            self::BUTTON => '按钮权限',
        };
    }

    /**
     * 获取所有选项
     */
    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
