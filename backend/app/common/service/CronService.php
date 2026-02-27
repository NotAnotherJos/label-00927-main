<?php
declare(strict_types=1);

namespace app\common\service;

use app\common\model\OperationLog;
use think\facade\Db;
use think\facade\Log;

/**
 * 定时任务服务
 */
class CronService
{
    /**
     * 清理过期缓存
     * 每天凌晨执行
     *
     * @return bool
     */
    public static function clearExpiredCache(): bool
    {
        $startTime = microtime(true);
        AppLogService::cron('clearExpiredCache', 'start');
        
        try {
            // 清理缓存
            CacheService::clearExpiredCache();
            
            // 清理runtime目录下的日志文件（保留最近7天）
            self::cleanOldLogFiles(7);
            
            // 清理临时文件
            self::cleanTempFiles();
            
            $duration = microtime(true) - $startTime;
            AppLogService::cron('clearExpiredCache', 'complete', true, [
                'duration_ms' => round($duration * 1000, 2),
            ]);
            
            Log::info('定时任务：清理过期缓存完成');
            return true;
        } catch (\Exception $e) {
            AppLogService::cron('clearExpiredCache', 'failed', false, [
                'error' => $e->getMessage(),
            ]);
            Log::error('定时任务：清理过期缓存失败 - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 归档操作日志
     * 每周日凌晨2点执行
     *
     * @return bool
     */
    public static function archiveLogs(): bool
    {
        $startTime = microtime(true);
        AppLogService::cron('archiveLogs', 'start');
        
        Db::startTrans();
        try {
            // 归档30天前的日志
            $archiveDate = date('Y-m-d H:i:s', strtotime('-30 days'));
            
            // 获取需要归档的日志
            $logs = OperationLog::where('create_time', '<', $archiveDate)
                ->limit(1000) // 每次最多处理1000条
                ->select();
            
            if ($logs->isEmpty()) {
                AppLogService::cron('archiveLogs', 'complete', true, [
                    'archived_count' => 0,
                    'message' => '没有需要归档的日志',
                ]);
                Log::info('定时任务：没有需要归档的日志');
                Db::commit();
                return true;
            }
            
            // 插入到历史表
            $archiveData = [];
            $archiveTime = date('Y-m-d H:i:s');
            foreach ($logs as $log) {
                $data = $log->toArray();
                $data['archive_time'] = $archiveTime;
                $archiveData[] = $data;
            }
            
            // 批量插入历史表
            Db::table('tp_operation_log_history')->insertAll($archiveData);
            
            // 删除原表数据
            $ids = $logs->column('id');
            OperationLog::whereIn('id', $ids)->delete();
            
            Db::commit();
            
            $duration = microtime(true) - $startTime;
            AppLogService::cron('archiveLogs', 'complete', true, [
                'archived_count' => count($logs),
                'duration_ms' => round($duration * 1000, 2),
            ]);
            
            Log::info('定时任务：归档操作日志完成，归档' . count($logs) . '条记录');
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            AppLogService::cron('archiveLogs', 'failed', false, [
                'error' => $e->getMessage(),
            ]);
            Log::error('定时任务：归档操作日志失败 - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 清理旧日志文件
     *
     * @param int $keepDays 保留天数
     * @return void
     */
    private static function cleanOldLogFiles(int $keepDays): void
    {
        $logPath = runtime_path() . 'log/';
        if (!is_dir($logPath)) {
            return;
        }
        
        $expireTime = time() - ($keepDays * 86400);
        $dirs = glob($logPath . '*', GLOB_ONLYDIR);
        $cleanedCount = 0;
        
        foreach ($dirs as $dir) {
            $dirTime = strtotime(basename($dir));
            if ($dirTime && $dirTime < $expireTime) {
                self::removeDirectory($dir);
                $cleanedCount++;
            }
        }
        
        if ($cleanedCount > 0) {
            AppLogService::info('清理旧日志目录', [
                'cleaned_count' => $cleanedCount,
            ], AppLogService::TYPE_CRON);
        }
    }

    /**
     * 清理临时文件
     *
     * @return void
     */
    private static function cleanTempFiles(): void
    {
        $tempPath = runtime_path() . 'temp/';
        if (!is_dir($tempPath)) {
            return;
        }
        
        $expireTime = time() - 86400; // 24小时前
        $files = glob($tempPath . '*');
        $cleanedCount = 0;
        
        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $expireTime) {
                @unlink($file);
                $cleanedCount++;
            }
        }
        
        if ($cleanedCount > 0) {
            AppLogService::info('清理临时文件', [
                'cleaned_count' => $cleanedCount,
            ], AppLogService::TYPE_CRON);
        }
    }

    /**
     * 递归删除目录
     *
     * @param string $dir
     * @return bool
     */
    private static function removeDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? self::removeDirectory($path) : @unlink($path);
        }
        
        return @rmdir($dir);
    }

    /**
     * 刷新缓存
     * 可手动调用或定时执行
     *
     * @return bool
     */
    public static function refreshCache(): bool
    {
        $startTime = microtime(true);
        AppLogService::cron('refreshCache', 'start');
        
        try {
            CacheService::refreshAll();
            
            $duration = microtime(true) - $startTime;
            AppLogService::cron('refreshCache', 'complete', true, [
                'duration_ms' => round($duration * 1000, 2),
            ]);
            
            Log::info('定时任务：刷新缓存完成');
            return true;
        } catch (\Exception $e) {
            AppLogService::cron('refreshCache', 'failed', false, [
                'error' => $e->getMessage(),
            ]);
            Log::error('定时任务：刷新缓存失败 - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 清理过期Token
     * 可选任务，用于清理数据库中存储的过期Token记录
     *
     * @return bool
     */
    public static function cleanExpiredTokens(): bool
    {
        $startTime = microtime(true);
        AppLogService::cron('cleanExpiredTokens', 'start');
        
        try {
            // 如果有Token黑名单表，可以在这里清理过期记录
            // Db::table('tp_token_blacklist')
            //     ->where('expire_time', '<', time())
            //     ->delete();
            
            $duration = microtime(true) - $startTime;
            AppLogService::cron('cleanExpiredTokens', 'complete', true, [
                'duration_ms' => round($duration * 1000, 2),
            ]);
            
            Log::info('定时任务：清理过期Token完成');
            return true;
        } catch (\Exception $e) {
            AppLogService::cron('cleanExpiredTokens', 'failed', false, [
                'error' => $e->getMessage(),
            ]);
            Log::error('定时任务：清理过期Token失败 - ' . $e->getMessage());
            return false;
        }
    }
}
