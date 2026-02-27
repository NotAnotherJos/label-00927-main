<?php
declare(strict_types=1);

namespace app\common\service;

use think\facade\Log;
use think\facade\Request;

/**
 * 应用日志服务
 * 提供统一的日志记录接口
 */
class AppLogService
{
    // 日志级别常量
    const LEVEL_DEBUG = 'debug';
    const LEVEL_INFO = 'info';
    const LEVEL_NOTICE = 'notice';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';
    const LEVEL_ALERT = 'alert';
    const LEVEL_EMERGENCY = 'emergency';

    // 日志类型常量
    const TYPE_REQUEST = 'request';      // 请求日志
    const TYPE_RESPONSE = 'response';    // 响应日志
    const TYPE_SQL = 'sql';              // SQL日志
    const TYPE_CACHE = 'cache';          // 缓存日志
    const TYPE_AUTH = 'auth';            // 认证日志
    const TYPE_BUSINESS = 'business';    // 业务日志
    const TYPE_EXCEPTION = 'exception';  // 异常日志
    const TYPE_CRON = 'cron';            // 定时任务日志
    const TYPE_API = 'api';              // API调用日志

    /**
     * 记录调试日志
     */
    public static function debug(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_DEBUG, $message, $context, $type);
    }

    /**
     * 记录信息日志
     */
    public static function info(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_INFO, $message, $context, $type);
    }

    /**
     * 记录通知日志
     */
    public static function notice(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_NOTICE, $message, $context, $type);
    }

    /**
     * 记录警告日志
     */
    public static function warning(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_WARNING, $message, $context, $type);
    }

    /**
     * 记录错误日志
     */
    public static function error(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_ERROR, $message, $context, $type);
    }

    /**
     * 记录严重错误日志
     */
    public static function critical(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_CRITICAL, $message, $context, $type);
    }

    /**
     * 记录警报日志
     */
    public static function alert(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_ALERT, $message, $context, $type);
    }

    /**
     * 记录紧急日志
     */
    public static function emergency(string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        self::log(self::LEVEL_EMERGENCY, $message, $context, $type);
    }

    /**
     * 记录请求日志
     */
    public static function request(array $extra = []): void
    {
        $context = [
            'method' => Request::method(),
            'url' => Request::url(true),
            'ip' => Request::ip(),
            'user_agent' => Request::header('User-Agent', ''),
            'params' => self::filterSensitiveData(Request::param()),
            'headers' => self::filterHeaders(Request::header()),
        ];
        
        $context = array_merge($context, $extra);
        
        self::info('Request received', $context, self::TYPE_REQUEST);
    }

    /**
     * 记录响应日志
     */
    public static function response(int $statusCode, $data = null, float $duration = 0): void
    {
        $context = [
            'status_code' => $statusCode,
            'duration_ms' => round($duration * 1000, 2),
            'response_size' => is_string($data) ? strlen($data) : strlen(json_encode($data)),
        ];
        
        self::info('Response sent', $context, self::TYPE_RESPONSE);
    }

    /**
     * 记录SQL日志
     */
    public static function sql(string $sql, array $bindings = [], float $time = 0): void
    {
        $context = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time_ms' => round($time * 1000, 2),
        ];
        
        self::debug('SQL executed', $context, self::TYPE_SQL);
    }

    /**
     * 记录缓存日志
     */
    public static function cache(string $action, string $key, $value = null, bool $hit = true): void
    {
        $context = [
            'action' => $action,
            'key' => $key,
            'hit' => $hit,
        ];
        
        if ($value !== null && $action === 'set') {
            $context['value_size'] = is_string($value) ? strlen($value) : strlen(json_encode($value));
        }
        
        self::debug('Cache ' . $action, $context, self::TYPE_CACHE);
    }

    /**
     * 记录认证日志
     */
    public static function auth(string $action, int $userId = 0, bool $success = true, string $reason = ''): void
    {
        $context = [
            'action' => $action,
            'user_id' => $userId,
            'success' => $success,
            'ip' => Request::ip(),
            'user_agent' => Request::header('User-Agent', ''),
        ];
        
        if (!$success && $reason) {
            $context['reason'] = $reason;
        }
        
        $level = $success ? self::LEVEL_INFO : self::LEVEL_WARNING;
        self::log($level, 'Auth ' . $action, $context, self::TYPE_AUTH);
    }

    /**
     * 记录异常日志
     */
    public static function exception(\Throwable $e, array $extra = []): void
    {
        $context = [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => self::formatTrace($e->getTrace()),
        ];
        
        $context = array_merge($context, $extra);
        
        self::error('Exception occurred', $context, self::TYPE_EXCEPTION);
    }

    /**
     * 记录定时任务日志
     */
    public static function cron(string $taskName, string $action, bool $success = true, array $extra = []): void
    {
        $context = [
            'task' => $taskName,
            'action' => $action,
            'success' => $success,
        ];
        
        $context = array_merge($context, $extra);
        
        $level = $success ? self::LEVEL_INFO : self::LEVEL_ERROR;
        self::log($level, 'Cron task ' . $action, $context, self::TYPE_CRON);
    }

    /**
     * 记录API调用日志
     */
    public static function api(string $service, string $method, array $params = [], $response = null, float $duration = 0, bool $success = true): void
    {
        $context = [
            'service' => $service,
            'method' => $method,
            'params' => self::filterSensitiveData($params),
            'duration_ms' => round($duration * 1000, 2),
            'success' => $success,
        ];
        
        if ($response !== null) {
            $context['response_size'] = is_string($response) ? strlen($response) : strlen(json_encode($response));
        }
        
        $level = $success ? self::LEVEL_INFO : self::LEVEL_ERROR;
        self::log($level, 'API call to ' . $service, $context, self::TYPE_API);
    }

    /**
     * 统一日志记录方法
     */
    protected static function log(string $level, string $message, array $context = [], string $type = self::TYPE_BUSINESS): void
    {
        // 添加通用上下文
        $context['log_type'] = $type;
        $context['timestamp'] = date('Y-m-d H:i:s.u');
        $context['request_id'] = self::getRequestId();
        
        // 格式化消息
        $formattedMessage = sprintf('[%s] %s', strtoupper($type), $message);
        
        // 记录日志
        Log::$level($formattedMessage, $context);
    }

    /**
     * 获取请求ID
     */
    protected static function getRequestId(): string
    {
        static $requestId = null;
        
        if ($requestId === null) {
            $requestId = Request::header('X-Request-ID') ?: uniqid('req_', true);
        }
        
        return $requestId;
    }

    /**
     * 过滤敏感数据
     */
    protected static function filterSensitiveData(array $data): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'key', 'authorization', 'cookie'];
        
        foreach ($data as $key => $value) {
            $lowerKey = strtolower($key);
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (strpos($lowerKey, $sensitiveKey) !== false) {
                    $data[$key] = '***FILTERED***';
                    break;
                }
            }
            
            if (is_array($value)) {
                $data[$key] = self::filterSensitiveData($value);
            }
        }
        
        return $data;
    }

    /**
     * 过滤请求头
     */
    protected static function filterHeaders(array $headers): array
    {
        $allowedHeaders = ['content-type', 'accept', 'user-agent', 'x-request-id', 'x-forwarded-for'];
        
        $filtered = [];
        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $allowedHeaders)) {
                $filtered[$key] = $value;
            }
        }
        
        return $filtered;
    }

    /**
     * 格式化堆栈跟踪
     */
    protected static function formatTrace(array $trace): array
    {
        $formatted = [];
        $maxFrames = 10;
        
        foreach (array_slice($trace, 0, $maxFrames) as $frame) {
            $formatted[] = [
                'file' => $frame['file'] ?? 'unknown',
                'line' => $frame['line'] ?? 0,
                'function' => $frame['function'] ?? 'unknown',
                'class' => $frame['class'] ?? '',
            ];
        }
        
        return $formatted;
    }
}
