<?php
declare(strict_types=1);

namespace middleware;

use Closure;
use think\Request;
use think\Response;

/**
 * 请求日志中间件
 */
class Log
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2);
        
        // 记录请求日志
        \think\facade\Log::info('Request: ' . $request->method() . ' ' . $request->url() . ' - ' . $duration . 'ms');
        
        return $response;
    }
}
