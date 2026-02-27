<?php
declare(strict_types=1);

namespace app\admin\enum;

/**
 * 数据权限枚举
 */
enum DataScopeEnum: int
{
    /** 全部数据权限 */
    case ALL = 1;
    
    /** 本部门数据权限 */
    case DEPT = 2;
    
    /** 本部门及子部门数据权限 */
    case DEPT_AND_CHILD = 3;
    
    /** 本人数据权限 */
    case SELF = 4;
    
    /** 自定义数据权限 */
    case CUSTOM = 5;

    /**
     * 获取描述
     */
    public function label(): string
    {
        return match($this) {
            self::ALL => '全部数据',
            self::DEPT => '本部门数据',
            self::DEPT_AND_CHILD => '本部门及子部门数据',
            self::SELF => '本人数据',
            self::CUSTOM => '自定义数据',
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
     * 根据值获取枚举
     */
    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::ALL,
            2 => self::DEPT,
            3 => self::DEPT_AND_CHILD,
            4 => self::SELF,
            5 => self::CUSTOM,
            default => null,
        };
    }
}
