<?php
// 控制台配置
return [
    // 指令定义
    'commands' => [
        'crontab:clear-cache' => \app\command\ClearCacheCommand::class,
        'crontab:archive-logs' => \app\command\ArchiveLogsCommand::class,
    ],
];
