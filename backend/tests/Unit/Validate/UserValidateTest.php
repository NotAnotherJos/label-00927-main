<?php
declare(strict_types=1);

namespace tests\Unit\Validate;

use PHPUnit\Framework\TestCase;
use app\admin\validate\UserValidate;

/**
 * 用户验证器测试
 */
class UserValidateTest extends TestCase
{
    private UserValidate $validate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validate = new UserValidate();
    }

    /**
     * 测试创建场景验证通过
     */
    public function testCreateScenePass(): void
    {
        $data = [
            'username' => 'testuser',
            'password' => 'password123',
            'nickname' => '测试用户',
            'email' => 'test@example.com',
            'phone' => '13800138000',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertTrue($result);
    }

    /**
     * 测试用户名为空
     */
    public function testUsernameRequired(): void
    {
        $data = [
            'username' => '',
            'password' => 'password123',
            'nickname' => '测试用户',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('用户名', $this->validate->getError());
    }

    /**
     * 测试用户名长度
     */
    public function testUsernameLength(): void
    {
        $data = [
            'username' => 'ab', // 太短
            'password' => 'password123',
            'nickname' => '测试用户',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
    }

    /**
     * 测试密码长度
     */
    public function testPasswordLength(): void
    {
        $data = [
            'username' => 'testuser',
            'password' => '12345', // 太短
            'nickname' => '测试用户',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('密码', $this->validate->getError());
    }

    /**
     * 测试邮箱格式
     */
    public function testEmailFormat(): void
    {
        $data = [
            'username' => 'testuser',
            'password' => 'password123',
            'nickname' => '测试用户',
            'email' => 'invalid-email',
            'dept_id' => 1,
            'role_id' => 1,
        ];
        
        $result = $this->validate->scene('create')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('邮箱', $this->validate->getError());
    }

    /**
     * 测试更新场景
     */
    public function testUpdateScene(): void
    {
        $data = [
            'nickname' => '新昵称',
            'email' => 'new@example.com',
            'dept_id' => 2,
            'role_id' => 2,
            'status' => 1,
        ];
        
        $result = $this->validate->scene('update')->check($data);
        
        $this->assertTrue($result);
    }

    /**
     * 测试状态值
     */
    public function testStatusValue(): void
    {
        $data = [
            'nickname' => '测试',
            'dept_id' => 1,
            'role_id' => 1,
            'status' => 2, // 无效状态
        ];
        
        $result = $this->validate->scene('update')->check($data);
        
        $this->assertFalse($result);
        $this->assertStringContainsString('状态', $this->validate->getError());
    }
}
