<?php
declare(strict_types=1);

namespace crontab;

use app\common\service\CronService;

/**
 * 清理过期缓存定时任务
 * 执行时间：每天凌晨0点
 */
class ClearCacheTask
{
    /**
     * 任务名称
     */
    public const NAME = '清理过期缓存';

    /**
     * 执行规则（Cron表达式）
     */
    public const RULE = '0 0 * * *';

    /**
     * 执行任务
     */
    public function execute(): void
    {
        CronService::clearExpiredCache();
    }
}
