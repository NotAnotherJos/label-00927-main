<?php
declare(strict_types=1);

namespace app\common\service;

use app\common\model\OperationLog;
use think\facade\Request;
use think\facade\Db;

/**
 * 操作日志服务（增强版）
 * 提供更丰富的操作日志记录功能
 */
class OperationLogService
{
    // 操作类型常量
    const TYPE_LOGIN = 'login';
    const TYPE_LOGOUT = 'logout';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';
    const TYPE_IMPORT = 'import';
    const TYPE_EXPORT = 'export';
    const TYPE_OTHER = 'other';

    // 模块常量
    const MODULE_USER = 'user';
    const MODULE_ROLE = 'role';
    const MODULE_PERMISSION = 'permission';
    const MODULE_MENU = 'menu';
    const MODULE_DEPARTMENT = 'department';
    const MODULE_SYSTEM = 'system';

    /**
     * 记录操作日志
     *
     * @param string $type 操作类型
     * @param string $content 操作内容
     * @param array $params 请求参数
     * @param int|null $userId 用户ID
     * @param string $module 模块名称
     * @return bool
     */
    public static function record(
        string $type,
        string $content,
        array $params = [],
        ?int $userId = null,
        string $module = ''
    ): bool {
        try {
            $userId = $userId ?? self::getCurrentUserId();
            
            // 过滤敏感参数
            $filteredParams = self::filterSensitiveParams($params);
            
            OperationLog::create([
                'user_id' => $userId ?? 0,
                'type' => $type,
                'module' => $module,
                'content' => $content,
                'params' => json_encode($filteredParams, JSON_UNESCAPED_UNICODE),
                'ip' => Request::ip(),
                'user_agent' => Request::header('User-Agent', ''),
                'create_time' => date('Y-m-d H:i:s'),
            ]);
            
            return true;
        } catch (\Exception $e) {
            AppLogService::error('操作日志记录失败', [
                'error' => $e->getMessage(),
                'type' => $type,
                'content' => $content,
            ]);
            return false;
        }
    }

    /**
     * 记录登录日志
     */
    public static function login(int $userId, string $username, bool $success = true, string $reason = ''): bool
    {
        $content = $success 
            ? "用户[{$username}]登录成功" 
            : "用户[{$username}]登录失败：{$reason}";
        
        return self::record(self::TYPE_LOGIN, $content, [
            'username' => $username,
            'success' => $success,
            'reason' => $reason,
        ], $userId, self::MODULE_SYSTEM);
    }

    /**
     * 记录登出日志
     */
    public static function logout(int $userId, string $username): bool
    {
        return self::record(
            self::TYPE_LOGOUT,
            "用户[{$username}]退出登录",
            [],
            $userId,
            self::MODULE_SYSTEM
        );
    }

    /**
     * 记录创建操作
     */
    public static function create(string $module, string $target, array $data = [], ?int $userId = null): bool
    {
        return self::record(
            self::TYPE_CREATE,
            "创建{$module}：{$target}",
            $data,
            $userId,
            $module
        );
    }

    /**
     * 记录更新操作
     */
    public static function update(string $module, string $target, array $data = [], ?int $userId = null): bool
    {
        return self::record(
            self::TYPE_UPDATE,
            "更新{$module}：{$target}",
            $data,
            $userId,
            $module
        );
    }

    /**
     * 记录删除操作
     */
    public static function delete(string $module, string $target, ?int $userId = null): bool
    {
        return self::record(
            self::TYPE_DELETE,
            "删除{$module}：{$target}",
            [],
            $userId,
            $module
        );
    }

    /**
     * 获取操作日志列表
     *
     * @param array $params 查询参数
     * @return array
     */
    public static function getList(array $params = []): array
    {
        $page = (int)($params['page'] ?? 1);
        $limit = (int)($params['limit'] ?? 15);
        
        $query = OperationLog::order('id', 'desc');
        
        // 按用户ID筛选
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }
        
        // 按操作类型筛选
        if (!empty($params['type'])) {
            $query->where('type', $params['type']);
        }
        
        // 按模块筛选
        if (!empty($params['module'])) {
            $query->where('module', $params['module']);
        }
        
        // 按时间范围筛选
        if (!empty($params['start_time'])) {
            $query->where('create_time', '>=', $params['start_time']);
        }
        if (!empty($params['end_time'])) {
            $query->where('create_time', '<=', $params['end_time']);
        }
        
        // 关键词搜索
        if (!empty($params['keyword'])) {
            $query->where('content', 'like', "%{$params['keyword']}%");
        }
        
        $total = $query->count();
        $list = $query->page($page, $limit)->select()->toArray();
        
        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 获取日志详情
     */
    public static function getDetail(int $id): ?array
    {
        $log = OperationLog::find($id);
        return $log ? $log->toArray() : null;
    }

    /**
     * 获取用户最近操作
     */
    public static function getUserRecentLogs(int $userId, int $limit = 10): array
    {
        return OperationLog::where('user_id', $userId)
            ->order('id', 'desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }

    /**
     * 统计操作日志
     */
    public static function statistics(string $startDate, string $endDate): array
    {
        $stats = Db::table('tp_operation_log')
            ->field('type, COUNT(*) as count')
            ->whereBetween('create_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->group('type')
            ->select()
            ->toArray();
        
        return array_column($stats, 'count', 'type');
    }

    /**
     * 清理过期日志
     */
    public static function cleanExpiredLogs(int $days = 90): int
    {
        $expireDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return OperationLog::where('create_time', '<', $expireDate)->delete();
    }

    /**
     * 获取当前用户ID
     */
    private static function getCurrentUserId(): ?int
    {
        $token = Request::header('Authorization', '');
        if (empty($token)) {
            return null;
        }
        
        $token = str_replace('Bearer ', '', $token);
        $payload = \extend\JwtUtil::verifyToken($token);
        
        return $payload['user_id'] ?? null;
    }

    /**
     * 过滤敏感参数
     */
    private static function filterSensitiveParams(array $params): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'key', 'authorization'];
        
        foreach ($params as $key => $value) {
            $lowerKey = strtolower($key);
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (str_contains($lowerKey, $sensitiveKey)) {
                    $params[$key] = '***';
                    break;
                }
            }
            
            if (is_array($value)) {
                $params[$key] = self::filterSensitiveParams($value);
            }
        }
        
        return $params;
    }
}
