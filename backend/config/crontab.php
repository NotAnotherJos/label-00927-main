<?php
// 定时任务配置 (基于 topthink/think-crontab)
use think\crontab\Task;

return [
    // 任务列表
    'tasks' => [
        // 每天凌晨清理过期缓存
        [
            'title' => '清理过期缓存',
            'type' => Task::TYPE_COMMAND,
            'rule' => '0 0 * * *', // 每天0点
            'target' => 'crontab:clear-cache',
        ],
        // 每周日归档操作日志
        [
            'title' => '归档操作日志',
            'type' => Task::TYPE_COMMAND,
            'rule' => '0 2 * * 0', // 每周日2点
            'target' => 'crontab:archive-logs',
        ],
    ],
];
