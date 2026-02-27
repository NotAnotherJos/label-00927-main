<?php
declare(strict_types=1);

namespace tests\Unit\Validate;

use PHPUnit\Framework\TestCase;
use app\admin\validate\RoleValidate;

/**
 * 角色验证器测试
 */
class RoleValidateTest extends TestCase
{
    private RoleValidate $validate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validate = new RoleValidate();
    }

    /**
     * 测试创建场景验证通过
     */
    public function testCreateScenePass(): void
    {
        $data = [
            'name' => '测试角色',
            'code' => 'test_role',
            'data_scope' => 1,
            'sort' => 0,
            'remark' => '测试角色备注',
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertTrue($result);
    }

    /**
     * 测试角色名称为空
     */
    public function testNameRequired(): void
    {
        $data = [
            'name' => '',
            'code' => 'test_role',
            'data_scope' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('角色名称', $this->validate->getError());
    }

    /**
     * 测试角色编码为空
     */
    public function testCodeRequired(): void
    {
        $data = [
            'name' => '测试角色',
            'code' => '',
            'data_scope' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('角色编码', $this->validate->getError());
    }

    /**
     * 测试数据权限值
     */
    public function testDataScopeValue(): void
    {
        $data = [
            'name' => '测试角色',
            'code' => 'test_role',
            'data_scope' => 6, // 无效值
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('数据权限', $this->validate->getError());
    }

    /**
     * 测试排序值
     */
    public function testSortValue(): void
    {
        $data = [
            'name' => '测试角色',
            'code' => 'test_role',
            'data_scope' => 1,
            'sort' => -1, // 负数
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('排序', $this->validate->getError());
    }

    /**
     * 测试备注长度
     */
    public function testRemarkLength(): void
    {
        $data = [
            'name' => '测试角色',
            'code' => 'test_role',
            'data_scope' => 1,
            'remark' => str_repeat('a', 300), // 超过255
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('备注', $this->validate->getError());
    }
}
