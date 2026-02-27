<?php
declare(strict_types=1);

namespace app\admin\enum;

/**
 * 菜单类型枚举
 */
enum MenuTypeEnum: int
{
    /** 目录 */
    case DIRECTORY = 1;
    
    /** 菜单 */
    case MENU = 2;
    
    /** 按钮 */
    case BUTTON = 3;

    /**
     * 获取描述
     */
    public function label(): string
    {
        return match($this) {
            self::DIRECTORY => '目录',
            self::MENU => '菜单',
            self::BUTTON => '按钮',
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

    /**
     * 是否为按钮
     */
    public function isButton(): bool
    {
        return $this === self::BUTTON;
    }

    /**
     * 是否为菜单
     */
    public function isMenu(): bool
    {
        return $this === self::MENU;
    }
}
