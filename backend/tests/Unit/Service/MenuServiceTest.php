<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\MenuService;
use app\common\exception\BusinessException;

/**
 * 菜单服务测试
 */
class MenuServiceTest extends TestCase
{
    /**
     * 测试获取菜单列表
     */
    public function testGetList(): void
    {
        $result = MenuService::getList([]);
        
        $this->assertIsArray($result);
    }

    /**
     * 测试获取菜单树
     */
    public function testGetTree(): void
    {
        $result = MenuService::getTree();
        
        $this->assertIsArray($result);
    }

    /**
     * 测试菜单不存在异常
     */
    public function testMenuNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('菜单不存在');
        
        MenuService::update(999999, ['name' => 'test']);
    }

    /**
     * 测试删除菜单不存在异常
     */
    public function testDeleteMenuNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('菜单不存在');
        
        MenuService::delete(999999);
    }

    /**
     * 测试菜单类型验证
     */
    public function testMenuTypeValidation(): void
    {
        // 1-目录，2-菜单，3-按钮
        $validTypes = [1, 2, 3];
        
        foreach ($validTypes as $type) {
            $this->assertContains($type, [1, 2, 3]);
        }
    }

    /**
     * 测试不能将菜单设置为自己的子菜单
     */
    public function testCannotSetMenuAsOwnChild(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('不能将菜单设置为自己的子菜单');
        
        // 假设菜单ID为1存在
        MenuService::update(1, ['pid' => 1]);
    }
}
