<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use app\common\service\AppLogService;

/**
 * 请求日志中间件
 * 记录完整的请求和响应信息
 */
class RequestLog
{
    /**
     * 不记录日志的路由
     */
    protected array $except = [
        'admin/swagger',
        'admin/swagger/ui',
    ];

    /**
     * 处理请求
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 检查是否需要跳过日志记录
        if ($this->shouldSkip($request)) {
            return $next($request);
        }
        
        // 记录请求开始时间
        $startTime = microtime(true);
        
        // 生成请求ID
        $requestId = $request->header('X-Request-ID') ?: uniqid('req_', true);
        
        // 记录请求日志
        AppLogService::request([
            'request_id' => $requestId,
            'controller' => $request->controller(),
            'action' => $request->action(),
        ]);
        
        // 执行请求
        $response = $next($request);
        
        // 计算请求耗时
        $duration = microtime(true) - $startTime;
        
        // 记录响应日志
        AppLogService::response(
            $response->getCode(),
            $response->getContent(),
            $duration
        );
        
        // 记录慢请求
        if ($duration > 1.0) {
            AppLogService::warning('Slow request detected', [
                'request_id' => $requestId,
                'duration_ms' => round($duration * 1000, 2),
                'url' => $request->url(true),
            ]);
        }
        
        // 添加请求ID到响应头
        $response->header(['X-Request-ID' => $requestId]);
        
        return $response;
    }

    /**
     * 检查是否应该跳过日志记录
     */
    protected function shouldSkip(Request $request): bool
    {
        $path = trim($request->pathinfo(), '/');
        
        foreach ($this->except as $pattern) {
            if ($path === $pattern || str_starts_with($path, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
}
