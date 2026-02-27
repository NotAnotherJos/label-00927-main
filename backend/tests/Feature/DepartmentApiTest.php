<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 部门API测试
 */
class DepartmentApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取部门列表
     */
    public function testGetDepartmentList(): void
    {
        $response = $this->request('GET', '/admin/departments');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/departments', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }

    /**
     * 测试获取部门树
     */
    public function testGetDepartmentTree(): void
    {
        $response = $this->request('GET', '/admin/departments/tree');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/departments/tree', $response['uri']);
    }

    /**
     * 测试创建部门
     */
    public function testCreateDepartment(): void
    {
        $response = $this->request('POST', '/admin/departments', [
            'pid' => 0,
            'name' => '测试部门',
            'code' => 'TEST_DEPT',
            'leader' => '张三',
            'phone' => '13800138000',
            'email' => 'test@example.com',
            'sort' => 1,
        ]);
        
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/admin/departments', $response['uri']);
    }

    /**
     * 测试更新部门
     */
    public function testUpdateDepartment(): void
    {
        $response = $this->request('PUT', '/admin/departments/1', [
            'name' => '更新后的部门名称',
            'leader' => '李四',
        ]);
        
        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/admin/departments/1', $response['uri']);
    }

    /**
     * 测试删除部门
     */
    public function testDeleteDepartment(): void
    {
        $response = $this->request('DELETE', '/admin/departments/999');
        
        $this->assertEquals('DELETE', $response['method']);
        $this->assertEquals('/admin/departments/999', $response['uri']);
    }

    /**
     * 测试获取部门详情
     */
    public function testGetDepartmentDetail(): void
    {
        $response = $this->request('GET', '/admin/departments/1');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/departments/1', $response['uri']);
    }
}
