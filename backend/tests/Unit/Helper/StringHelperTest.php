<?php
declare(strict_types=1);

namespace tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use app\common\helper\StringHelper;

/**
 * 字符串助手测试
 */
class StringHelperTest extends TestCase
{
    /**
     * 测试生成随机字符串
     */
    public function testRandom(): void
    {
        $str1 = StringHelper::random(16);
        $str2 = StringHelper::random(16);
        
        $this->assertEquals(16, strlen($str1));
        $this->assertNotEquals($str1, $str2);
    }

    /**
     * 测试生成指定长度的随机字符串
     */
    public function testRandomWithLength(): void
    {
        $str = StringHelper::random(32);
        
        $this->assertEquals(32, strlen($str));
    }

    /**
     * 测试生成UUID
     */
    public function testUuid(): void
    {
        $uuid1 = StringHelper::uuid();
        $uuid2 = StringHelper::uuid();
        
        $this->assertMatchesRegularExpression('/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/', $uuid1);
        $this->assertNotEquals($uuid1, $uuid2);
    }

    /**
     * 测试驼峰转下划线
     */
    public function testToSnakeCase(): void
    {
        $this->assertEquals('user_name', StringHelper::toSnakeCase('userName'));
        $this->assertEquals('get_user_list', StringHelper::toSnakeCase('getUserList'));
        $this->assertEquals('id', StringHelper::toSnakeCase('id'));
    }

    /**
     * 测试下划线转驼峰
     */
    public function testToCamelCase(): void
    {
        $this->assertEquals('userName', StringHelper::toCamelCase('user_name'));
        $this->assertEquals('getUserList', StringHelper::toCamelCase('get_user_list'));
        $this->assertEquals('UserName', StringHelper::toCamelCase('user_name', true));
    }

    /**
     * 测试脱敏手机号
     */
    public function testMaskPhone(): void
    {
        $this->assertEquals('138****8000', StringHelper::maskPhone('13800138000'));
        $this->assertEquals('12345', StringHelper::maskPhone('12345')); // 非11位不处理
    }

    /**
     * 测试脱敏邮箱
     */
    public function testMaskEmail(): void
    {
        $this->assertEquals('te***@example.com', StringHelper::maskEmail('test@example.com'));
        $this->assertEquals('ad***@qq.com', StringHelper::maskEmail('admin@qq.com'));
    }

    /**
     * 测试截取字符串
     */
    public function testTruncate(): void
    {
        $this->assertEquals('Hello...', StringHelper::truncate('Hello World', 5));
        $this->assertEquals('Hello', StringHelper::truncate('Hello', 10));
        $this->assertEquals('你好世...', StringHelper::truncate('你好世界', 3));
    }
}
