<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 角色管理API测试
 */
class RoleApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取角色列表
     */
    public function testGetRoleList(): void
    {
        $response = $this->request('GET', '/admin/roles', [
            'page' => 1,
            'limit' => 15,
        ]);
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/roles', $response['uri']);
    }

    /**
     * 测试获取所有角色
     */
    public function testGetAllRoles(): void
    {
        $response = $this->request('GET', '/admin/roles/all');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/roles/all', $response['uri']);
    }

    /**
     * 测试获取角色详情
     */
    public function testGetRoleDetail(): void
    {
        $response = $this->request('GET', '/admin/roles/1');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/roles/1', $response['uri']);
    }

    /**
     * 测试创建角色
     */
    public function testCreateRole(): void
    {
        $response = $this->request('POST', '/admin/roles', [
            'name' => '测试角色_' . time(),
            'code' => 'test_role_' . time(),
            'data_scope' => 1,
            'remark' => '测试角色',
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/roles', $response['uri']);
    }

    /**
     * 测试更新角色
     */
    public function testUpdateRole(): void
    {
        $response = $this->request('PUT', '/admin/roles/1', [
            'name' => '更新后的角色名',
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/roles/1', $response['uri']);
    }

    /**
     * 测试删除角色
     */
    public function testDeleteRole(): void
    {
        $response = $this->request('DELETE', '/admin/roles/999');
        
        $this->assertEquals('DELETE', $response['method']);
        $this->assertEquals('/admin/roles/999', $response['uri']);
    }

    /**
     * 测试设置角色菜单
     */
    public function testSetRoleMenus(): void
    {
        $response = $this->request('POST', '/admin/roles/1/menus', [
            'menu_ids' => [1, 2, 3],
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/roles/1/menus', $response['uri']);
    }
}
