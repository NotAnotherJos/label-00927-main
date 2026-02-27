<?php
declare(strict_types=1);

namespace tests\Unit\Dto;

use PHPUnit\Framework\TestCase;
use app\admin\dto\RoleDTO;

/**
 * 角色DTO测试
 */
class RoleDTOTest extends TestCase
{
    /**
     * 测试从模型创建DTO
     */
    public function testFromModel(): void
    {
        $data = [
            'id' => 1,
            'name' => '超级管理员',
            'code' => 'admin',
            'data_scope' => 1,
            'remark' => '系统管理员',
            'sort' => 0,
            'status' => 1,
            'create_time' => '2026-01-27 10:00:00',
            'permissions' => [1, 2, 3],
            'menus' => [1, 2, 3, 4],
        ];
        
        $dto = RoleDTO::fromModel($data);
        
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('超级管理员', $dto->name);
        $this->assertEquals('admin', $dto->code);
        $this->assertEquals(1, $dto->dataScope);
        $this->assertCount(3, $dto->permissions);
        $this->assertCount(4, $dto->menus);
    }

    /**
     * 测试转换为数组
     */
    public function testToArray(): void
    {
        $dto = new RoleDTO(
            id: 1,
            name: '测试角色',
            code: 'test',
            dataScope: 2,
            remark: '测试',
            sort: 1,
            status: 1,
        );
        
        $array = $dto->toArray();
        
        $this->assertIsArray($array);
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('测试角色', $array['name']);
        $this->assertEquals(2, $array['data_scope']);
    }

    /**
     * 测试默认值
     */
    public function testDefaultValues(): void
    {
        $data = [
            'id' => 1,
            'name' => '角色',
        ];
        
        $dto = RoleDTO::fromModel($data);
        
        $this->assertEquals('', $dto->code);
        $this->assertEquals(1, $dto->dataScope);
        $this->assertEquals('', $dto->remark);
        $this->assertNull($dto->permissions);
    }
}
