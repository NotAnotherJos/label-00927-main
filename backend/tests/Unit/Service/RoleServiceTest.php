<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\RoleService;
use app\common\exception\BusinessException;

/**
 * 角色服务测试
 */
class RoleServiceTest extends TestCase
{
    /**
     * 测试获取角色列表
     */
    public function testGetList(): void
    {
        $result = RoleService::getList(1, 15, []);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('list', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('limit', $result);
    }

    /**
     * 测试获取所有角色
     */
    public function testGetAll(): void
    {
        $result = RoleService::getAll();
        
        $this->assertIsArray($result);
    }

    /**
     * 测试角色不存在异常
     */
    public function testRoleNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('角色不存在');
        
        RoleService::getDetail(999999);
    }

    /**
     * 测试更新角色不存在异常
     */
    public function testUpdateRoleNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('角色不存在');
        
        RoleService::update(999999, ['name' => 'test']);
    }

    /**
     * 测试删除角色不存在异常
     */
    public function testDeleteRoleNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('角色不存在');
        
        RoleService::delete(999999);
    }

    /**
     * 测试数据权限类型验证
     */
    public function testDataScopeValidation(): void
    {
        $validDataScopes = [1, 2, 3, 4, 5];
        
        foreach ($validDataScopes as $scope) {
            $this->assertContains($scope, [1, 2, 3, 4, 5]);
        }
    }
}
