<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\admin\service\DepartmentService;
use app\common\exception\BusinessException;

/**
 * 部门服务测试
 */
class DepartmentServiceTest extends TestCase
{
    /**
     * 测试获取部门列表
     */
    public function testGetList(): void
    {
        $result = DepartmentService::getList([]);
        
        $this->assertIsArray($result);
    }

    /**
     * 测试获取部门树
     */
    public function testGetTree(): void
    {
        $result = DepartmentService::getTree();
        
        $this->assertIsArray($result);
    }

    /**
     * 测试部门不存在异常
     */
    public function testDepartmentNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('部门不存在');
        
        DepartmentService::getDetail(999999);
    }

    /**
     * 测试更新部门不存在异常
     */
    public function testUpdateDepartmentNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('部门不存在');
        
        DepartmentService::update(999999, ['name' => 'test']);
    }

    /**
     * 测试删除部门不存在异常
     */
    public function testDeleteDepartmentNotFoundThrowsException(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('部门不存在');
        
        DepartmentService::delete(999999);
    }

    /**
     * 测试不能将部门设置为自己的子部门
     */
    public function testCannotSetDepartmentAsOwnChild(): void
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage('不能将部门设置为自己的子部门');
        
        // 假设部门ID为1存在
        DepartmentService::update(1, ['pid' => 1]);
    }
}
