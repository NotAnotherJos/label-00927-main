<?php
declare(strict_types=1);

namespace app\admin\enum;

/**
 * 操作日志类型枚举
 */
enum LogTypeEnum: string
{
    /** 登录 */
    case LOGIN = 'login';
    
    /** 登出 */
    case LOGOUT = 'logout';
    
    /** 新增 */
    case CREATE = 'create';
    
    /** 更新 */
    case UPDATE = 'update';
    
    /** 删除 */
    case DELETE = 'delete';
    
    /** 导入 */
    case IMPORT = 'import';
    
    /** 导出 */
    case EXPORT = 'export';
    
    /** 其他 */
    case OTHER = 'other';

    /**
     * 获取描述
     */
    public function label(): string
    {
        return match($this) {
            self::LOGIN => '登录',
            self::LOGOUT => '登出',
            self::CREATE => '新增',
            self::UPDATE => '更新',
            self::DELETE => '删除',
            self::IMPORT => '导入',
            self::EXPORT => '导出',
            self::OTHER => '其他',
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
