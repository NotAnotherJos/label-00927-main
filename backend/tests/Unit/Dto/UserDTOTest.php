<?php
declare(strict_types=1);

namespace tests\Unit\Dto;

use PHPUnit\Framework\TestCase;
use app\admin\dto\UserDTO;

/**
 * 用户DTO测试
 */
class UserDTOTest extends TestCase
{
    /**
     * 测试从模型创建DTO
     */
    public function testFromModel(): void
    {
        $data = [
            'id' => 1,
            'username' => 'admin',
            'nickname' => '管理员',
            'email' => 'admin@example.com',
            'phone' => '13800138000',
            'dept_id' => 1,
            'role_id' => 1,
            'data_scope' => 1,
            'status' => 1,
            'avatar' => '/avatar.png',
            'dept_name' => '总公司',
            'role_name' => '超级管理员',
            'create_time' => '2026-01-27 10:00:00',
        ];
        
        $dto = UserDTO::fromModel($data);
        
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('admin', $dto->username);
        $this->assertEquals('管理员', $dto->nickname);
        $this->assertEquals('admin@example.com', $dto->email);
        $this->assertEquals(1, $dto->deptId);
        $this->assertEquals('总公司', $dto->deptName);
    }

    /**
     * 测试转换为数组
     */
    public function testToArray(): void
    {
        $dto = new UserDTO(
            id: 1,
            username: 'test',
            nickname: '测试用户',
            email: 'test@example.com',
            phone: '13800138000',
            deptId: 1,
            roleId: 1,
            dataScope: 1,
            status: 1,
        );
        
        $array = $dto->toArray();
        
        $this->assertIsArray($array);
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('test', $array['username']);
        $this->assertEquals(1, $array['dept_id']);
        $this->assertArrayHasKey('create_time', $array);
    }

    /**
     * 测试默认值
     */
    public function testDefaultValues(): void
    {
        $data = [
            'id' => 1,
            'username' => 'test',
        ];
        
        $dto = UserDTO::fromModel($data);
        
        $this->assertEquals('', $dto->nickname);
        $this->assertEquals('', $dto->email);
        $this->assertEquals(0, $dto->deptId);
        $this->assertEquals(1, $dto->status);
    }
}
