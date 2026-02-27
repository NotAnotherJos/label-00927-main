<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 菜单API测试
 */
class MenuApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取菜单列表
     */
    public function testGetMenuList(): void
    {
        $response = $this->request('GET', '/admin/menus');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/menus', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }

    /**
     * 测试获取菜单树
     */
    public function testGetMenuTree(): void
    {
        $response = $this->request('GET', '/admin/menus/tree');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/menus/tree', $response['uri']);
    }

    /**
     * 测试获取用户菜单
     */
    public function testGetUserMenus(): void
    {
        $response = $this->request('GET', '/admin/menus/user');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/menus/user', $response['uri']);
    }

    /**
     * 测试创建菜单
     */
    public function testCreateMenu(): void
    {
        $response = $this->request('POST', '/admin/menus', [
            'pid' => 0,
            'name' => '测试菜单',
            'path' => '/test',
            'component' => 'test/index',
            'icon' => 'test',
            'type' => 2,
            'permission' => 'test:list',
            'sort' => 1,
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/menus', $response['uri']);
    }

    /**
     * 测试更新菜单
     */
    public function testUpdateMenu(): void
    {
        $response = $this->request('PUT', '/admin/menus/1', [
            'name' => '更新后的菜单名称',
            'sort' => 2,
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/menus/1', $response['uri']);
    }

    /**
     * 测试删除菜单
     */
    public function testDeleteMenu(): void
    {
        $response = $this->request('DELETE', '/admin/menus/999');
        
        $this->assertEquals('DELETE', $response['method']);
        $this->assertEquals('/admin/menus/999', $response['uri']);
    }
}
