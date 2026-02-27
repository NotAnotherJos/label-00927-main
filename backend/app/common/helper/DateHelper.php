<?php
declare(strict_types=1);

namespace app\common\helper;

/**
 * 日期时间助手类
 */
class DateHelper
{
    /**
     * 获取当前时间戳（毫秒）
     *
     * @return int
     */
    public static function getMillisecond(): int
    {
        return (int)(microtime(true) * 1000);
    }

    /**
     * 格式化日期时间
     *
     * @param int|string|null $time 时间
     * @param string $format 格式
     * @return string
     */
    public static function format(int|string|null $time = null, string $format = 'Y-m-d H:i:s'): string
    {
        if ($time === null) {
            $time = time();
        } elseif (is_string($time)) {
            $time = strtotime($time);
        }
        
        return date($format, $time);
    }

    /**
     * 获取友好的时间显示
     *
     * @param int|string $time 时间
     * @return string
     */
    public static function friendly(int|string $time): string
    {
        if (is_string($time)) {
            $time = strtotime($time);
        }
        
        $diff = time() - $time;
        
        if ($diff < 60) {
            return '刚刚';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . '分钟前';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . '小时前';
        } elseif ($diff < 604800) {
            return floor($diff / 86400) . '天前';
        } elseif ($diff < 2592000) {
            return floor($diff / 604800) . '周前';
        } elseif ($diff < 31536000) {
            return floor($diff / 2592000) . '个月前';
        } else {
            return floor($diff / 31536000) . '年前';
        }
    }

    /**
     * 获取今天的开始时间
     *
     * @return int
     */
    public static function todayStart(): int
    {
        return strtotime(date('Y-m-d 00:00:00'));
    }

    /**
     * 获取今天的结束时间
     *
     * @return int
     */
    public static function todayEnd(): int
    {
        return strtotime(date('Y-m-d 23:59:59'));
    }

    /**
     * 获取本周的开始时间
     *
     * @return int
     */
    public static function weekStart(): int
    {
        return strtotime(date('Y-m-d 00:00:00', strtotime('this week monday')));
    }

    /**
     * 获取本周的结束时间
     *
     * @return int
     */
    public static function weekEnd(): int
    {
        return strtotime(date('Y-m-d 23:59:59', strtotime('this week sunday')));
    }

    /**
     * 获取本月的开始时间
     *
     * @return int
     */
    public static function monthStart(): int
    {
        return strtotime(date('Y-m-01 00:00:00'));
    }

    /**
     * 获取本月的结束时间
     *
     * @return int
     */
    public static function monthEnd(): int
    {
        return strtotime(date('Y-m-t 23:59:59'));
    }

    /**
     * 计算两个日期之间的天数
     *
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @return int
     */
    public static function diffDays(string $startDate, string $endDate): int
    {
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        
        return (int)floor(abs($end - $start) / 86400);
    }

    /**
     * 判断是否为工作日
     *
     * @param int|string|null $time 时间
     * @return bool
     */
    public static function isWorkday(int|string|null $time = null): bool
    {
        if ($time === null) {
            $time = time();
        } elseif (is_string($time)) {
            $time = strtotime($time);
        }
        
        $dayOfWeek = date('N', $time);
        return $dayOfWeek < 6;
    }
}
