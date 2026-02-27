<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Db;
use app\common\service\AppLogService;

/**
 * SQL日志中间件
 * 记录所有SQL查询
 */
class SqlLog
{
    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 监听SQL执行
        Db::listen(function ($sql, $time, $explain, $master) {
            // 只在调试模式下记录SQL日志
            if (env('APP_DEBUG', false)) {
                AppLogService::sql($sql, [], $time);
            }
        });
        
        return $next($request);
    }
}
