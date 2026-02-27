<?php
declare(strict_types=1);

namespace tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use app\admin\enum\MenuTypeEnum;

/**
 * 菜单类型枚举测试
 */
class MenuTypeEnumTest extends TestCase
{
    /**
     * 测试枚举值
     */
    public function testEnumValues(): void
    {
        $this->assertEquals(1, MenuTypeEnum::DIRECTORY->value);
        $this->assertEquals(2, MenuTypeEnum::MENU->value);
        $this->assertEquals(3, MenuTypeEnum::BUTTON->value);
    }

    /**
     * 测试获取标签
     */
    public function testLabel(): void
    {
        $this->assertEquals('目录', MenuTypeEnum::DIRECTORY->label());
        $this->assertEquals('菜单', MenuTypeEnum::MENU->label());
        $this->assertEquals('按钮', MenuTypeEnum::BUTTON->label());
    }

    /**
     * 测试是否为按钮
     */
    public function testIsButton(): void
    {
        $this->assertTrue(MenuTypeEnum::BUTTON->isButton());
        $this->assertFalse(MenuTypeEnum::MENU->isButton());
        $this->assertFalse(MenuTypeEnum::DIRECTORY->isButton());
    }

    /**
     * 测试是否为菜单
     */
    public function testIsMenu(): void
    {
        $this->assertTrue(MenuTypeEnum::MENU->isMenu());
        $this->assertFalse(MenuTypeEnum::BUTTON->isMenu());
        $this->assertFalse(MenuTypeEnum::DIRECTORY->isMenu());
    }

    /**
     * 测试获取所有选项
     */
    public function testOptions(): void
    {
        $options = MenuTypeEnum::options();
        
        $this->assertCount(3, $options);
    }
}
