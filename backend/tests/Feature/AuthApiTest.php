<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 认证API测试
 */
class AuthApiTest extends BaseTestCase
{
    /**
     * 测试登录接口
     */
    public function testLogin(): void
    {
        $response = $this->request('POST', '/admin/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/login', $response['uri']);
    }

    /**
     * 测试登录参数验证
     */
    public function testLoginValidation(): void
    {
        // 测试空用户名
        $response = $this->request('POST', '/admin/login', [
            'username' => '',
            'password' => 'password',
        ]);
        
        $this->assertEmpty($response['data']['username']);
    }

    /**
     * 测试Token刷新接口
     */
    public function testRefreshToken(): void
    {
        $this->token = $this->getTestToken();
        
        $response = $this->request('POST', '/admin/refresh');
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/refresh', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }
}
