<?php
declare(strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\common\service\CronService;

/**
 * 归档操作日志命令
 */
class ArchiveLogsCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('crontab:archive-logs')
            ->setDescription('归档操作日志');
    }

    protected function execute(Input $input, Output $output): int
    {
        $output->writeln('开始归档操作日志...');
        
        try {
            CronService::archiveLogs();
            $output->writeln('<info>日志归档完成</info>');
            return 0;
        } catch (\Throwable $e) {
            $output->writeln('<error>日志归档失败: ' . $e->getMessage() . '</error>');
            return 1;
        }
    }
}
