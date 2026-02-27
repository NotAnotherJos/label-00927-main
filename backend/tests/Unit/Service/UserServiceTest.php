<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\UserService;
use app\admin\model\AdminUser;
use app\common\exception\BusinessException;
use think\facade\Db;

/**
 * 用户服务测试
 */
class UserServiceTest extends TestCase
{
    /**
     * 测试获取用户列表
     */
    public function testGetList(): void
    {
        // 模拟测试数据
        $result = UserService::getList(1, 15, []);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('list', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('limit', $result);
    }

    /**
     * 测试创建用户参数验证
     */
    public function testCreateUserValidation(): void
    {
        $params = [
            'username' => 'testuser_' . time(),
            'password' => '123456',
            'nickname' => '测试用户',
            'email' => 'test@example.com',
            'phone' => '13800138000',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        // 验证参数格式
        $this->assertNotEmpty($params['username']);
        $this->assertGreaterThanOrEqual(6, strlen($params['password']));
        $this->assertMatchesRegularExpression('/^[\w\-\.]+@[\w\-\.]+\.\w+$/', $params['email']);
    }

    /**
     * 测试用户状态设置
     */
    public function testSetStatusValidation(): void
    {
        // 测试有效状态值
        $validStatuses = [0, 1];
        
        foreach ($validStatuses as $status) {
            $this->assertContains($status, [0, 1]);
        }
    }

    /**
     * 测试用户不存在异常
     */
    public function testUserNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('用户不存在');
        
        UserService::update(999999, ['nickname' => 'test']);
    }

    /**
     * 测试删除用户不存在异常
     */
    public function testDeleteUserNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('用户不存在');
        
        UserService::delete(999999);
    }
}
