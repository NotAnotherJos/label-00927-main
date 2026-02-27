<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\common\service\AppLogService;

/**
 * 应用日志服务测试
 */
class AppLogServiceTest extends TestCase
{
    /**
     * 测试日志级别常量
     */
    public function testLogLevelConstants(): void
    {
        $this->assertEquals('debug', AppLogService::LEVEL_DEBUG);
        $this->assertEquals('info', AppLogService::LEVEL_INFO);
        $this->assertEquals('notice', AppLogService::LEVEL_NOTICE);
        $this->assertEquals('warning', AppLogService::LEVEL_WARNING);
        $this->assertEquals('error', AppLogService::LEVEL_ERROR);
        $this->assertEquals('critical', AppLogService::LEVEL_CRITICAL);
        $this->assertEquals('alert', AppLogService::LEVEL_ALERT);
        $this->assertEquals('emergency', AppLogService::LEVEL_EMERGENCY);
    }

    /**
     * 测试日志类型常量
     */
    public function testLogTypeConstants(): void
    {
        $this->assertEquals('request', AppLogService::TYPE_REQUEST);
        $this->assertEquals('response', AppLogService::TYPE_RESPONSE);
        $this->assertEquals('sql', AppLogService::TYPE_SQL);
        $this->assertEquals('cache', AppLogService::TYPE_CACHE);
        $this->assertEquals('auth', AppLogService::TYPE_AUTH);
        $this->assertEquals('business', AppLogService::TYPE_BUSINESS);
        $this->assertEquals('exception', AppLogService::TYPE_EXCEPTION);
        $this->assertEquals('cron', AppLogService::TYPE_CRON);
        $this->assertEquals('api', AppLogService::TYPE_API);
    }

    /**
     * 测试敏感数据过滤
     */
    public function testFilterSensitiveData(): void
    {
        $data = [
            'username' => 'admin',
            'password' => 'secret123',
            'token' => 'jwt_token_here',
            'api_key' => 'key123',
        ];
        
        // 使用反射测试私有方法
        $reflection = new \ReflectionClass(AppLogService::class);
        $method = $reflection->getMethod('filterSensitiveData');
        $method->setAccessible(true);
        
        $filtered = $method->invoke(null, $data);
        
        $this->assertEquals('admin', $filtered['username']);
        $this->assertEquals('***FILTERED***', $filtered['password']);
        $this->assertEquals('***FILTERED***', $filtered['token']);
        $this->assertEquals('***FILTERED***', $filtered['api_key']);
    }

    /**
     * 测试堆栈跟踪格式化
     */
    public function testFormatTrace(): void
    {
        $trace = [
            [
                'file' => '/app/test.php',
                'line' => 10,
                'function' => 'testMethod',
                'class' => 'TestClass',
            ],
            [
                'file' => '/app/main.php',
                'line' => 20,
                'function' => 'main',
            ],
        ];
        
        $reflection = new \ReflectionClass(AppLogService::class);
        $method = $reflection->getMethod('formatTrace');
        $method->setAccessible(true);
        
        $formatted = $method->invoke(null, $trace);
        
        $this->assertCount(2, $formatted);
        $this->assertEquals('/app/test.php', $formatted[0]['file']);
        $this->assertEquals(10, $formatted[0]['line']);
        $this->assertEquals('testMethod', $formatted[0]['function']);
    }

    /**
     * 测试请求头过滤
     */
    public function testFilterHeaders(): void
    {
        $headers = [
            'content-type' => 'application/json',
            'authorization' => 'Bearer token123',
            'user-agent' => 'Mozilla/5.0',
            'x-request-id' => 'req_123',
            'cookie' => 'session=abc',
        ];
        
        $reflection = new \ReflectionClass(AppLogService::class);
        $method = $reflection->getMethod('filterHeaders');
        $method->setAccessible(true);
        
        $filtered = $method->invoke(null, $headers);
        
        $this->assertArrayHasKey('content-type', $filtered);
        $this->assertArrayHasKey('user-agent', $filtered);
        $this->assertArrayHasKey('x-request-id', $filtered);
        $this->assertArrayNotHasKey('authorization', $filtered);
        $this->assertArrayNotHasKey('cookie', $filtered);
    }
}
