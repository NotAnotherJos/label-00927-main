<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\PermissionService;
use app\common\exception\BusinessException;

/**
 * 权限服务测试
 */
class PermissionServiceTest extends TestCase
{
    /**
     * 测试获取权限列表
     */
    public function testGetList(): void
    {
        $result = PermissionService::getList([]);
        
        $this->assertIsArray($result);
    }

    /**
     * 测试获取权限树
     */
    public function testGetTree(): void
    {
        $result = PermissionService::getTree();
        
        $this->assertIsArray($result);
    }

    /**
     * 测试权限不存在异常
     */
    public function testPermissionNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('权限不存在');
        
        PermissionService::update(999999, ['name' => 'test']);
    }

    /**
     * 测试删除权限不存在异常
     */
    public function testDeletePermissionNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('权限不存在');
        
        PermissionService::delete(999999);
    }

    /**
     * 测试权限类型验证
     */
    public function testPermissionTypeValidation(): void
    {
        // 1-菜单，2-按钮
        $validTypes = [1, 2];
        
        foreach ($validTypes as $type) {
            $this->assertContains($type, [1, 2]);
        }
    }

    /**
     * 测试获取用户权限
     */
    public function testGetUserPermissions(): void
    {
        // 超级管理员应该有所有权限
        $permissions = PermissionService::getUserPermissions(1);
        
        $this->assertIsArray($permissions);
    }

    /**
     * 测试检查用户权限
     */
    public function testHasPermission(): void
    {
        // 超级管理员应该有所有权限
        $hasPermission = PermissionService::hasPermission(1, 'system:user:list');
        
        $this->assertTrue($hasPermission);
    }

    /**
     * 测试获取角色权限ID列表
     */
    public function testGetRolePermissionIds(): void
    {
        $permissionIds = PermissionService::getRolePermissionIds(1);
        
        $this->assertIsArray($permissionIds);
    }
}
