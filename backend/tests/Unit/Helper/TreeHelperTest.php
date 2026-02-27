<?php
declare(strict_types=1);

namespace tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use app\common\helper\TreeHelper;

/**
 * 树形结构助手测试
 */
class TreeHelperTest extends TestCase
{
    private array $testData = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->testData = [
            ['id' => 1, 'pid' => 0, 'name' => '根节点1'],
            ['id' => 2, 'pid' => 0, 'name' => '根节点2'],
            ['id' => 3, 'pid' => 1, 'name' => '子节点1-1'],
            ['id' => 4, 'pid' => 1, 'name' => '子节点1-2'],
            ['id' => 5, 'pid' => 3, 'name' => '子节点1-1-1'],
            ['id' => 6, 'pid' => 2, 'name' => '子节点2-1'],
        ];
    }

    /**
     * 测试列表转树形结构
     */
    public function testListToTree(): void
    {
        $tree = TreeHelper::listToTree($this->testData);
        
        $this->assertCount(2, $tree);
        $this->assertEquals('根节点1', $tree[0]['name']);
        $this->assertEquals('根节点2', $tree[1]['name']);
        $this->assertArrayHasKey('children', $tree[0]);
        $this->assertCount(2, $tree[0]['children']);
    }

    /**
     * 测试树形结构转列表
     */
    public function testTreeToList(): void
    {
        $tree = TreeHelper::listToTree($this->testData);
        $list = TreeHelper::treeToList($tree);
        
        $this->assertCount(6, $list);
    }

    /**
     * 测试获取所有子节点ID
     */
    public function testGetChildIds(): void
    {
        $childIds = TreeHelper::getChildIds($this->testData, 1);
        
        $this->assertCount(3, $childIds);
        $this->assertContains(3, $childIds);
        $this->assertContains(4, $childIds);
        $this->assertContains(5, $childIds);
    }

    /**
     * 测试获取所有父节点ID
     */
    public function testGetParentIds(): void
    {
        $parentIds = TreeHelper::getParentIds($this->testData, 5);
        
        $this->assertCount(2, $parentIds);
        $this->assertContains(3, $parentIds);
        $this->assertContains(1, $parentIds);
    }

    /**
     * 测试获取节点路径
     */
    public function testGetNodePath(): void
    {
        $path = TreeHelper::getNodePath($this->testData, 5);
        
        $this->assertEquals('根节点1 / 子节点1-1 / 子节点1-1-1', $path);
    }

    /**
     * 测试空列表
     */
    public function testEmptyList(): void
    {
        $tree = TreeHelper::listToTree([]);
        
        $this->assertEmpty($tree);
    }

    /**
     * 测试自定义字段名
     */
    public function testCustomFieldNames(): void
    {
        $data = [
            ['uid' => 1, 'parent_id' => 0, 'title' => '节点1'],
            ['uid' => 2, 'parent_id' => 1, 'title' => '节点2'],
        ];
        
        $tree = TreeHelper::listToTree($data, 'uid', 'parent_id', 'items');
        
        $this->assertCount(1, $tree);
        $this->assertArrayHasKey('items', $tree[0]);
    }
}
