<?php
declare(strict_types=1);

namespace tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use app\common\service\CacheService;

/**
 * 缓存服务测试
 */
class CacheServiceTest extends TestCase
{
    /**
     * 测试获取用户权限缓存
     */
    public function testGetUserPermissions(): void
    {
        $permissions = CacheService::getUserPermissions(1);
        
        $this->assertIsArray($permissions);
    }

    /**
     * 测试获取菜单树缓存
     */
    public function testGetMenuTree(): void
    {
        $menuTree = CacheService::getMenuTree();
        
        $this->assertIsArray($menuTree);
    }

    /**
     * 测试获取角色信息缓存
     */
    public function testGetRoleInfo(): void
    {
        $roleInfo = CacheService::getRoleInfo(1);
        
        // 可能返回数组或null
        $this->assertTrue(is_array($roleInfo) || is_null($roleInfo));
    }

    /**
     * 测试获取用户信息缓存
     */
    public function testGetUserInfo(): void
    {
        $userInfo = CacheService::getUserInfo(1);
        
        // 可能返回数组或null
        $this->assertTrue(is_array($userInfo) || is_null($userInfo));
    }

    /**
     * 测试清除用户权限缓存
     */
    public function testClearUserPermission(): void
    {
        $result = CacheService::clearUserPermission(1);
        
        $this->assertTrue($result);
    }

    /**
     * 测试清除菜单树缓存
     */
    public function testClearMenuTree(): void
    {
        $result = CacheService::clearMenuTree();
        
        $this->assertTrue($result);
    }

    /**
     * 测试清除角色缓存
     */
    public function testClearRoleCache(): void
    {
        $result = CacheService::clearRoleCache(1);
        
        $this->assertTrue($result);
    }

    /**
     * 测试清除用户信息缓存
     */
    public function testClearUserInfo(): void
    {
        $result = CacheService::clearUserInfo(1);
        
        $this->assertTrue($result);
    }

    /**
     * 测试清理过期缓存
     */
    public function testClearExpiredCache(): void
    {
        $result = CacheService::clearExpiredCache();
        
        $this->assertTrue($result);
    }
}
