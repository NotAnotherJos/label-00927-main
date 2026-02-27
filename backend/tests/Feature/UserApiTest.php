<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 用户管理API测试
 */
class UserApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取用户列表
     */
    public function testGetUserList(): void
    {
        $response = $this->request('GET', '/admin/users', [
            'page' => 1,
            'limit' => 15,
        ]);
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/users', $response['uri']);
    }

    /**
     * 测试创建用户
     */
    public function testCreateUser(): void
    {
        $response = $this->request('POST', '/admin/users', [
            'username' => 'testuser_' . time(),
            'password' => '123456',
            'nickname' => '测试用户',
            'email' => 'test@example.com',
            'phone' => '13800138000',
            'dept_id' => 1,
            'role_id' => 1,
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/users', $response['uri']);
    }

    /**
     * 测试更新用户
     */
    public function testUpdateUser(): void
    {
        $response = $this->request('PUT', '/admin/users/1', [
            'nickname' => '更新后的昵称',
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/users/1', $response['uri']);
    }

    /**
     * 测试删除用户
     */
    public function testDeleteUser(): void
    {
        $response = $this->request('DELETE', '/admin/users/999');
        
        $this->assertEquals('DELETE', $response['method']);
        $this->assertEquals('/admin/users/999', $response['uri']);
    }

    /**
     * 测试设置用户状态
     */
    public function testSetUserStatus(): void
    {
        $response = $this->request('POST', '/admin/users/1/status', [
            'status' => 1,
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/users/1/status', $response['uri']);
    }

    /**
     * 测试用户列表搜索
     */
    public function testSearchUsers(): void
    {
        $response = $this->request('GET', '/admin/users', [
            'username' => 'admin',
            'status' => 1,
        ]);
        
        $this->assertEquals('GET', $response['method']);
        $this->assertArrayHasKey('username', $response['data']);
    }
}
