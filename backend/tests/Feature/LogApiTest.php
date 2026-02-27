<?php
declare(strict_types=1);

namespace tests\Feature;

use tests\BaseTestCase;

/**
 * 日志API测试
 */
class LogApiTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getTestToken();
    }

    /**
     * 测试获取日志列表
     */
    public function testGetLogList(): void
    {
        $response = $this->request('GET', '/admin/logs');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/logs', $response['uri']);
        $this->assertArrayHasKey('Authorization', $response['headers']);
    }

    /**
     * 测试获取日志列表带分页
     */
    public function testGetLogListWithPagination(): void
    {
        $response = $this->request('GET', '/admin/logs', [
            'page' => 1,
            'limit' => 20,
        ]);
        
        $this->assertEquals('GET', $response['method']);
        $this->assertArrayHasKey('page', $response['data']);
        $this->assertArrayHasKey('limit', $response['data']);
    }

    /**
     * 测试获取日志列表带筛选
     */
    public function testGetLogListWithFilter(): void
    {
        $response = $this->request('GET', '/admin/logs', [
            'type' => 'login',
            'user_id' => 1,
            'start_time' => '2026-01-01',
            'end_time' => '2026-12-31',
        ]);
        
        $this->assertEquals('GET', $response['method']);
        $this->assertArrayHasKey('type', $response['data']);
    }

    /**
     * 测试获取日志详情
     */
    public function testGetLogDetail(): void
    {
        $response = $this->request('GET', '/admin/logs/1');
        
        $this->assertEquals('GET', $response['method']);
        $this->assertEquals('/admin/logs/1', $response['uri']);
    }

    /**
     * 测试未授权访问日志
     */
    public function testUnauthorizedAccess(): void
    {
        $this->token = '';
        
        $response = $this->request('GET', '/admin/logs', [], []);
        
        $this->assertArrayNotHasKey('Authorization', $response['headers']);
    }
}
