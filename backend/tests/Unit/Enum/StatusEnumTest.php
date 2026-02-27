<?php
declare(strict_types=1);

namespace tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use app\admin\enum\StatusEnum;

/**
 * 状态枚举测试
 */
class StatusEnumTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertEquals(0, StatusEnum::DISABLED->value);
        $this->assertEquals(1, StatusEnum::ENABLED->value);
    }

    /**
     * 测试获取标签
     */
    public function testLabel(): void
    {
        $this->assertEquals('禁用', StatusEnum::DISABLED->label());
        $this->assertEquals('启用', StatusEnum::ENABLED->label());
    }

    /**
     * 测试是否启用
     */
    public function testIsEnabled(): void
    {
        $this->assertTrue(StatusEnum::ENABLED->isEnabled());
        $this->assertFalse(StatusEnum::DISABLED->isEnabled());
    }

    /**
     * 测试获取所有选项
     */
    public function testOptions(): void
    {
        $options = StatusEnum::options();
        
        $this->assertCount(2, $options);
    }
}
