<?php
declare(strict_types=1);

namespace app\admin\enum;

/**
 * 状态枚举
 */
enum StatusEnum: int
{
    /** 禁用 */
    case DISABLED = 0;
    
    /** 启用 */
    case ENABLED = 1;

    /**
     * 获取描述
     */
    public function label(): string
    {
        return match($this) {
            self::DISABLED => '禁用',
            self::ENABLED => '启用',
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
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this === self::ENABLED;
    }
}
