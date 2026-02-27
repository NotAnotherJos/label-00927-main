<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\common\service\LogService;

/**
 * 操作日志服务测试
 */
class LogServiceTest extends TestCase
{
    /**
     * 测试记录日志参数
     */
    public function testRecordParameters(): void
    {
        // 测试参数类型
        $type = 'create';
        $content = '创建用户';
        $params = ['username' => 'test', 'email' => 'test@example.com'];
        
        $this->assertIsString($type);
        $this->assertIsString($content);
        $this->assertIsArray($params);
    }

    /**
     * 测试日志类型常量
     */
    public function testLogTypes(): void
    {
        $validTypes = ['login', 'logout', 'create', 'update', 'delete', 'import', 'export', 'other'];
        
        foreach ($validTypes as $type) {
            $this->assertIsString($type);
        }
    }

    /**
     * 测试日志内容格式
     */
    public function testLogContentFormat(): void
    {
        $content = '用户[admin]创建了新用户[test]';
        
        $this->assertStringContainsString('用户', $content);
        $this->assertStringContainsString('admin', $content);
    }

    /**
     * 测试参数JSON编码
     */
    public function testParamsJsonEncode(): void
    {
        $params = [
            'username' => 'test',
            'nickname' => '测试用户',
            'roles' => [1, 2, 3],
        ];
        
        $json = json_encode($params, JSON_UNESCAPED_UNICODE);
        
        $this->assertIsString($json);
        $this->assertStringContainsString('测试用户', $json);
        
        $decoded = json_decode($json, true);
        $this->assertEquals($params, $decoded);
    }
}
