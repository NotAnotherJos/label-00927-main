<?php
declare(strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\common\service\CronService;

/**
 * 清理过期缓存命令
 */
class ClearCacheCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('crontab:clear-cache')
            ->setDescription('清理过期缓存');
    }

    protected function execute(Input $input, Output $output): int
    {
        $output->writeln('开始清理过期缓存...');
        
        try {
            CronService::clearExpiredCache();
            $output->writeln('<info>缓存清理完成</info>');
            return 0;
        } catch (\Throwable $e) {
            $output->writeln('<error>缓存清理失败: ' . $e->getMessage() . '</error>');
            return 1;
        }
    }
}
