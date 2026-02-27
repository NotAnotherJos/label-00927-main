<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\AuthService;
use app\common\exception\BusinessException;

/**
 * 认证服务测试
 */
class AuthServiceTest extends TestCase
{
    /**
     * 测试登录用户名为空
     */
    public function testLoginWithEmptyUsername(): void
    {
        $this->expectException(BusinessException::class);
        
        AuthService::login('', 'password');
    }

    /**
     * 测试登录密码为空
     */
    public function testLoginWithEmptyPassword(): void
    {
        $this->expectException(BusinessException::class);
        
        AuthService::login('admin', '');
    }

    /**
     * 测试登录用户不存在
     */
    public function testLoginWithNonExistentUser(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('用户名或密码错误');
        
        AuthService::login('nonexistent_user_' . time(), 'password');
    }

    /**
     * 测试登录密码错误
     */
    public function testLoginWithWrongPassword(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('用户名或密码错误');
        
        AuthService::login('admin', 'wrong_password');
    }

    /**
     * 测试成功登录
     */
    public function testLoginSuccess(): void
    {
        $result = AuthService::login('admin', 'password');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertNotEmpty($result['token']);
        $this->assertIsArray($result['user']);
        $this->assertArrayNotHasKey('password', $result['user']);
    }

    /**
     * 测试登录返回的Token有效
     */
    public function testLoginTokenIsValid(): void
    {
        $result = AuthService::login('admin', 'password');
        
        $payload = \extend\JwtUtil::verifyToken($result['token']);
        
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('user_id', $payload);
        $this->assertEquals($result['user']['id'], $payload['user_id']);
    }
}
