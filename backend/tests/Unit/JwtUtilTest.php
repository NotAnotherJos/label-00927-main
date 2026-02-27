<?php
declare(strict_types=1);

namespace tests\Unit;

use PHPUnit\Framework\TestCase;
use extend\JwtUtil;
use think\facade\Config;

/**
 * JWT工具类测试
 */
class JwtUtilTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // 模拟配置
        Config::set([
            'secret' => 'test-secret-key',
            'ttl' => 7200,
            'refresh_ttl' => 604800,
            'algorithm' => 'HS256',
        ], 'jwt');
    }

    /**
     * 测试生成Token
     */
    public function testGenerateToken(): void
    {
        $payload = [
            'user_id' => 1,
            'role_id' => 1,
            'data_scope' => 1,
        ];
        
        $token = JwtUtil::generateToken($payload);
        
        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        // JWT格式：header.payload.signature
        $this->assertCount(3, explode('.', $token));
    }

    /**
     * 测试验证Token
     */
    public function testVerifyToken(): void
    {
        $payload = [
            'user_id' => 1,
            'role_id' => 2,
            'data_scope' => 3,
        ];
        
        $token = JwtUtil::generateToken($payload);
        $decoded = JwtUtil::verifyToken($token);
        
        $this->assertIsArray($decoded);
        $this->assertEquals(1, $decoded['user_id']);
        $this->assertEquals(2, $decoded['role_id']);
        $this->assertEquals(3, $decoded['data_scope']);
    }

    /**
     * 测试无效Token验证
     */
    public function testVerifyInvalidToken(): void
    {
        $result = JwtUtil::verifyToken('invalid.token.here');
        
        $this->assertFalse($result);
    }

    /**
     * 测试刷新Token
     */
    public function testRefreshToken(): void
    {
        $payload = [
            'user_id' => 1,
            'role_id' => 1,
            'data_scope' => 1,
        ];
        
        $token = JwtUtil::generateToken($payload);
        $newToken = JwtUtil::refreshToken($token);
        
        $this->assertNotEmpty($newToken);
        $this->assertNotEquals($token, $newToken);
        
        // 验证新Token包含相同的payload
        $decoded = JwtUtil::verifyToken($newToken);
        $this->assertEquals(1, $decoded['user_id']);
    }

    /**
     * 测试解析Token（不验证）
     */
    public function testParseToken(): void
    {
        $payload = [
            'user_id' => 100,
            'role_id' => 5,
            'data_scope' => 2,
        ];
        
        $token = JwtUtil::generateToken($payload);
        $parsed = JwtUtil::parseToken($token);
        
        $this->assertIsArray($parsed);
        $this->assertEquals(100, $parsed['user_id']);
        $this->assertEquals(5, $parsed['role_id']);
    }
}
