<?php
declare(strict_types=1);

namespace extend;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\facade\Config;

/**
 * JWT工具类
 */
class JwtUtil
{
    /**
     * 生成Token
     *
     * @param array $payload 载荷数据
     * @return string
     */
    public static function generateToken(array $payload): string
    {
        $config = Config::get('jwt');
        $now = time();
        
        $tokenPayload = [
            'iat' => $now, // 签发时间
            'exp' => $now + $config['ttl'], // 过期时间
            'data' => $payload, // 自定义数据
        ];
        
        return JWT::encode($tokenPayload, $config['secret'], $config['algorithm']);
    }

    /**
     * 验证Token
     *
     * @param string $token
     * @return array|false
     */
    public static function verifyToken(string $token)
    {
        try {
            $config = Config::get('jwt');
            $decoded = JWT::decode($token, new Key($config['secret'], $config['algorithm']));
            return (array) $decoded->data;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 刷新Token
     *
     * @param string $token
     * @return string|false
     */
    public static function refreshToken(string $token)
    {
        $payload = self::verifyToken($token);
        if ($payload === false) {
            return false;
        }
        
        return self::generateToken($payload);
    }

    /**
     * 解析Token（不验证）
     *
     * @param string $token
     * @return array|false
     */
    public static function parseToken(string $token)
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return false;
            }
            
            $payload = json_decode(base64_decode($parts[1]), true);
            return $payload['data'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
