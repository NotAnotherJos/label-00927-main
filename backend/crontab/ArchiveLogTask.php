<?php
declare(strict_types=1);

namespace crontab;

use app\common\service\CronService;

/**
 * 归档操作日志定时任务
 * 执行时间：每周日凌晨2点
 */
class ArchiveLogTask
{
    /**
     * 任务名称
     */
    public const NAME = '归档操作日志';

    /**
     * 执行规则（Cron表达式）
     */
    public const RULE = '0 2 * * 0';

    /**
     * 执行任务
     */
    public function execute(): void
    {
        CronService::archiveLogs();
    }
}
