<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 权限API测试
 */
class PermissionApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取权限列表
     */
    public function testGetPermissionList(): void
    {
        $response = $this->request('GET', '/admin/permissions');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/permissions', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }

    /**
     * 测试获取权限树
     */
    public function testGetPermissionTree(): void
    {
        $response = $this->request('GET', '/admin/permissions/tree');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/permissions/tree', $response['uri']);
    }

    /**
     * 测试获取用户权限
     */
    public function testGetUserPermissions(): void
    {
        $response = $this->request('GET', '/admin/permissions/user');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/permissions/user', $response['uri']);
    }

    /**
     * 测试获取角色权限
     */
    public function testGetRolePermissions(): void
    {
        $response = $this->request('GET', '/admin/permissions/role/1');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/permissions/role/1', $response['uri']);
    }

    /**
     * 测试创建权限
     */
    public function testCreatePermission(): void
    {
        $response = $this->request('POST', '/admin/permissions', [
            'pid' => 0,
            'name' => '测试权限',
            'code' => 'test:permission',
            'type' => 1,
            'path' => '/test',
            'component' => 'test/index',
            'sort' => 1,
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/permissions', $response['uri']);
    }

    /**
     * 测试设置角色权限
     */
    public function testSetRolePermissions(): void
    {
        $response = $this->request('POST', '/admin/permissions/role/1', [
            'permission_ids' => [1, 2, 3, 4, 5],
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/permissions/role/1', $response['uri']);
    }

    /**
     * 测试更新权限
     */
    public function testUpdatePermission(): void
    {
        $response = $this->request('PUT', '/admin/permissions/1', [
            'name' => '更新后的权限名称',
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/permissions/1', $response['uri']);
    }

    /**
     * 测试删除权限
     */
    public function testDeletePermission(): void
    {
        $response = $this->request('DELETE', '/admin/permissions/999');
        
        $this->assertEquals('DELETE', $response['method']);
        $this->assertEquals('/admin/permissions/999', $response['uri']);
    }
}
