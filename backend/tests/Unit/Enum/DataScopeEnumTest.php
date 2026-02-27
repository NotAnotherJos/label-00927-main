<?php
declare(strict_types=1);

namespace tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use app\admin\enum\DataScopeEnum;

/**
 * 数据权限枚举测试
 */
class DataScopeEnumTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertEquals(1, DataScopeEnum::ALL->value);
        $this->assertEquals(2, DataScopeEnum::DEPT->value);
        $this->assertEquals(3, DataScopeEnum::DEPT_AND_CHILD->value);
        $this->assertEquals(4, DataScopeEnum::SELF->value);
        $this->assertEquals(5, DataScopeEnum::CUSTOM->value);
    }

    /**
     * 测试获取标签
     */
    public function testLabel(): void
    {
        $this->assertEquals('全部数据', DataScopeEnum::ALL->label());
        $this->assertEquals('本部门数据', DataScopeEnum::DEPT->label());
        $this->assertEquals('本部门及子部门数据', DataScopeEnum::DEPT_AND_CHILD->label());
        $this->assertEquals('本人数据', DataScopeEnum::SELF->label());
        $this->assertEquals('自定义数据', DataScopeEnum::CUSTOM->label());
    }

    /**
     * 测试获取所有选项
     */
    public function testOptions(): void
    {
        $options = DataScopeEnum::options();
        
        $this->assertCount(5, $options);
        $this->assertEquals(1, $options[0]['value']);
        $this->assertEquals('全部数据', $options[0]['label']);
    }

    /**
     * 测试根据值获取枚举
     */
    public function testFromValue(): void
    {
        $this->assertEquals(DataScopeEnum::ALL, DataScopeEnum::fromValue(1));
        $this->assertEquals(DataScopeEnum::DEPT, DataScopeEnum::fromValue(2));
        $this->assertNull(DataScopeEnum::fromValue(99));
    }
}
