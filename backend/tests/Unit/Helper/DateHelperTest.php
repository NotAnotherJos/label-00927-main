<?php
declare(strict_types=1);

namespace tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use app\common\helper\DateHelper;

/**
 * 日期时间助手测试
 */
class DateHelperTest extends TestCase
{
    /**
     * 测试获取毫秒时间戳
     */
    public function testGetMillisecond(): void
    {
        $ms = DateHelper::getMillisecond();
        
        $this->assertIsInt($ms);
        $this->assertGreaterThan(time() * 1000, $ms);
    }

    /**
     * 测试格式化日期时间
     */
    public function testFormat(): void
    {
        $timestamp = strtotime('2026-01-27 10:30:00');
        
        $this->assertEquals('2026-01-27 10:30:00', DateHelper::format($timestamp));
        $this->assertEquals('2026-01-27', DateHelper::format($timestamp, 'Y-m-d'));
        $this->assertEquals('10:30', DateHelper::format($timestamp, 'H:i'));
    }

    /**
     * 测试友好时间显示
     */
    public function testFriendly(): void
    {
        $this->assertEquals('刚刚', DateHelper::friendly(time() - 30));
        $this->assertEquals('5分钟前', DateHelper::friendly(time() - 300));
        $this->assertEquals('2小时前', DateHelper::friendly(time() - 7200));
        $this->assertEquals('3天前', DateHelper::friendly(time() - 259200));
    }

    /**
     * 测试今天的开始和结束时间
     */
    public function testTodayStartEnd(): void
    {
        $start = DateHelper::todayStart();
        $end = DateHelper::todayEnd();
        
        $this->assertEquals(date('Y-m-d 00:00:00'), date('Y-m-d H:i:s', $start));
        $this->assertEquals(date('Y-m-d 23:59:59'), date('Y-m-d H:i:s', $end));
    }

    /**
     * 测试本月的开始和结束时间
     */
    public function testMonthStartEnd(): void
    {
        $start = DateHelper::monthStart();
        $end = DateHelper::monthEnd();
        
        $this->assertEquals(date('Y-m-01 00:00:00'), date('Y-m-d H:i:s', $start));
        $this->assertEquals(date('Y-m-t 23:59:59'), date('Y-m-d H:i:s', $end));
    }

    /**
     * 测试计算两个日期之间的天数
     */
    public function testDiffDays(): void
    {
        $this->assertEquals(10, DateHelper::diffDays('2026-01-01', '2026-01-11'));
        $this->assertEquals(365, DateHelper::diffDays('2026-01-01', '2027-01-01'));
        $this->assertEquals(0, DateHelper::diffDays('2026-01-01', '2026-01-01'));
    }

    /**
     * 测试判断是否为工作日
     */
    public function testIsWorkday(): void
    {
        // 2026-01-27 是周二
        $tuesday = strtotime('2026-01-27');
        $this->assertTrue(DateHelper::isWorkday($tuesday));
        
        // 2026-01-25 是周日
        $sunday = strtotime('2026-01-25');
        $this->assertFalse(DateHelper::isWorkday($sunday));
    }
}
