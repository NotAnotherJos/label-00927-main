<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 个人信息API测试
 */
class ProfileApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取个人信息
     */
    public function testGetProfile(): void
    {
        $response = $this->request('GET', '/admin/profile');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/profile', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }

    /**
     * 测试更新个人信息
     */
    public function testUpdateProfile(): void
    {
        $response = $this->request('PUT', '/admin/profile', [
            'nickname' => '新昵称',
            'email' => 'new@example.com',
            'phone' => '13900139000',
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/profile', $response['uri']);
    }

    /**
     * 测试修改密码
     */
    public function testChangePassword(): void
    {
        $response = $this->request('POST', '/admin/profile/password', [
            'old_password' => 'password',
            'new_password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/profile/password', $response['uri']);
    }

    /**
     * 测试获取用户菜单
     */
    public function testGetUserMenus(): void
    {
        $response = $this->request('GET', '/admin/profile/menus');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/profile/menus', $response['uri']);
    }
}
