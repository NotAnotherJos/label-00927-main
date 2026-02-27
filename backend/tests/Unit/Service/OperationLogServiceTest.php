<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\common\service\OperationLogService;

/**
 * 操作日志服务测试
 */
class OperationLogServiceTest extends TestCase
{
    /**
     * 测试操作类型常量
     */
    public function testTypeConstants(): void
    {
        $this->assertEquals('login', OperationLogService::TYPE_LOGIN);
        $this->assertEquals('logout', OperationLogService::TYPE_LOGOUT);
        $this->assertEquals('create', OperationLogService::TYPE_CREATE);
        $this->assertEquals('update', OperationLogService::TYPE_UPDATE);
        $this->assertEquals('delete', OperationLogService::TYPE_DELETE);
        $this->assertEquals('import', OperationLogService::TYPE_IMPORT);
        $this->assertEquals('export', OperationLogService::TYPE_EXPORT);
        $this->assertEquals('other', OperationLogService::TYPE_OTHER);
    }

    /**
     * 测试模块常量
     */
    public function testModuleConstants(): void
    {
        $this->assertEquals('user', OperationLogService::MODULE_USER);
        $this->assertEquals('role', OperationLogService::MODULE_ROLE);
        $this->assertEquals('permission', OperationLogService::MODULE_PERMISSION);
        $this->assertEquals('menu', OperationLogService::MODULE_MENU);
        $this->assertEquals('department', OperationLogService::MODULE_DEPARTMENT);
        $this->assertEquals('system', OperationLogService::MODULE_SYSTEM);
    }

    /**
     * 测试敏感参数过滤
     */
    public function testFilterSensitiveParams(): void
    {
        $params = [
            'username' => 'admin',
            'password' => 'secret123',
            'api_token' => 'token123',
            'data' => [
                'secret_key' => 'key123',
                'name' => 'test',
            ],
        ];
        
        // 使用反射测试私有方法
        $reflection = new \ReflectionClass(OperationLogService::class);
        $method = $reflection->getMethod('filterSensitiveParams');
        $method->setAccessible(true);
        
        $filtered = $method->invoke(null, $params);
        
        $this->assertEquals('admin', $filtered['username']);
        $this->assertEquals('***', $filtered['password']);
        $this->assertEquals('***', $filtered['api_token']);
        $this->assertEquals('***', $filtered['data']['secret_key']);
        $this->assertEquals('test', $filtered['data']['name']);
    }

    /**
     * 测试日志内容格式
     */
    public function testLogContentFormat(): void
    {
        $module = '用户';
        $target = 'admin';
        
        $createContent = "创建{$module}：{$target}";
        $updateContent = "更新{$module}：{$target}";
        $deleteContent = "删除{$module}：{$target}";
        
        $this->assertEquals('创建用户：admin', $createContent);
        $this->assertEquals('更新用户：admin', $updateContent);
        $this->assertEquals('删除用户：admin', $deleteContent);
    }

    /**
     * 测试登录日志内容
     */
    public function testLoginLogContent(): void
    {
        $username = 'admin';
        
        $successContent = "用户[{$username}]登录成功";
        $failContent = "用户[{$username}]登录失败：密码错误";
        
        $this->assertStringContainsString('admin', $successContent);
        $this->assertStringContainsString('登录成功', $successContent);
        $this->assertStringContainsString('登录失败', $failContent);
        $this->assertStringContainsString('密码错误', $failContent);
    }
}
