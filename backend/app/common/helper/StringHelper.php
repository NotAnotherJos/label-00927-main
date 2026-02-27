<?php
declare(strict_types=1);

namespace app\common\helper;

/**
 * 字符串助手类
 */
class StringHelper
{
    /**
     * 生成随机字符串
     *
     * @param int $length 长度
     * @param string $chars 字符集
     * @return string
     */
    public static function random(int $length = 16, string $chars = ''): string
    {
        if (empty($chars)) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        
        $str = '';
        $max = strlen($chars) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, $max)];
        }
        
        return $str;
    }

    /**
     * 生成UUID
     *
     * @return string
     */
    public static function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }

    /**
     * 驼峰转下划线
     *
     * @param string $str 字符串
     * @return string
     */
    public static function toSnakeCase(string $str): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
    }

    /**
     * 下划线转驼峰
     *
     * @param string $str 字符串
     * @param bool $ucFirst 首字母是否大写
     * @return string
     */
    public static function toCamelCase(string $str, bool $ucFirst = false): string
    {
        $str = str_replace('_', ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        
        return $ucFirst ? $str : lcfirst($str);
    }

    /**
     * 脱敏手机号
     *
     * @param string $phone 手机号
     * @return string
     */
    public static function maskPhone(string $phone): string
    {
        if (strlen($phone) !== 11) {
            return $phone;
        }
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }

    /**
     * 脱敏邮箱
     *
     * @param string $email 邮箱
     * @return string
     */
    public static function maskEmail(string $email): string
    {
        $pos = strpos($email, '@');
        if ($pos === false || $pos < 2) {
            return $email;
        }
        
        $prefix = substr($email, 0, 2);
        $suffix = substr($email, $pos);
        
        return $prefix . '***' . $suffix;
    }

    /**
     * 截取字符串
     *
     * @param string $str 字符串
     * @param int $length 长度
     * @param string $suffix 后缀
     * @return string
     */
    public static function truncate(string $str, int $length, string $suffix = '...'): string
    {
        if (mb_strlen($str) <= $length) {
            return $str;
        }
        
        return mb_substr($str, 0, $length) . $suffix;
    }
}
