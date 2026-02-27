<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use think\App;
use think\facade\Db;

/**
 * 测试基类
 */
abstract class BaseTestCase extends TestCase
{
    protected App $app;
    protected string $token = '';
    protected int $userId = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = new App();
        $this->app->initialize();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * 获取测试Token
     */
    protected function getTestToken(): string
    {
        if (empty($this->token)) {
            $this->token = \extend\JwtUtil::generateToken([
                'user_id' => $this->userId,
                'role_id' => 1,
                'data_scope' => 1,
            ]);
        }
        return $this->token;
    }

    /**
     * 模拟HTTP请求
     */
    protected function request(string $method, string $uri, array $data = [], array $headers = []): array
    {
        // 设置默认headers
        if (!isset($headers['Authorization']) && $this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        
        // 这里简化处理，实际测试中可以使用更完整的HTTP客户端
        return [
            'method' => $method,
            'uri' => $uri,
            'data' => $data,
            'headers' => $headers,
        ];
    }

    /**
     * 断言响应成功
     */
    protected function assertSuccess(array $response): void
    {
        $this->assertEquals(200, $response['code'] ?? 0);
    }

    /**
     * 断言响应失败
     */
    protected function assertFail(array $response, int $code = 400): void
    {
        $this->assertEquals($code, $response['code'] ?? 0);
    }
}
