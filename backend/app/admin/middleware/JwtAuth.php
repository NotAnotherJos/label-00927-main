<?php
declare(strict_types=1);

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use extend\JwtUtil;
use app\common\exception\BusinessException;

/**
 * JWT认证中间件
 */
class JwtAuth
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
        $token = $request->header('Authorization', '');
        
        if (empty($token)) {
            throw new BusinessException('Token不能为空', 401);
        }
        
        // 移除Bearer前缀
        $token = str_replace('Bearer ', '', $token);
        
        // 验证Token
        $payload = JwtUtil::verifyToken($token);
        if ($payload === false) {
            throw new BusinessException('Token无效或已过期', 401);
        }
        
        // 将用户信息注入到请求中
        $request->userId = $payload['user_id'] ?? null;
        $request->roleId = $payload['role_id'] ?? null;
        $request->dataScope = $payload['data_scope'] ?? 1;
        
        return $next($request);
    }
}
