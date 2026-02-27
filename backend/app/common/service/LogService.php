<?php
declare(strict_types=1);

namespace app\common\service;

use app\common\model\OperationLog;
use think\facade\Request;

/**
 * 操作日志服务
 */
class LogService
{
    /**
     * 记录操作日志
     *
     * @param string $type 操作类型
     * @param string $content 操作内容
     * @param array $params 请求参数
     * @param int|null $userId 用户ID
     * @return bool
     */
    public static function record(string $type, string $content, array $params = [], ?int $userId = null): bool
    {
        try {
            $userId = $userId ?? self::getCurrentUserId();
            
            OperationLog::create([
                'user_id' => $userId,
                'type' => $type,
                'content' => $content,
                'params' => json_encode($params, JSON_UNESCAPED_UNICODE),
                'ip' => Request::ip(),
                'user_agent' => Request::header('User-Agent', ''),
                'create_time' => date('Y-m-d H:i:s'),
            ]);
            
            return true;
        } catch (\Exception $e) {
            // 记录日志失败不影响主流程
            \think\facade\Log::error('操作日志记录失败：' . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取当前用户ID
     *
     * @return int|null
     */
    private static function getCurrentUserId(): ?int
    {
        // 从JWT Token中获取用户ID
        $token = Request::header('Authorization', '');
        if (empty($token)) {
            return null;
        }
        
        $token = str_replace('Bearer ', '', $token);
        $payload = \extend\JwtUtil::verifyToken($token);
        
        return $payload['user_id'] ?? null;
    }
}
