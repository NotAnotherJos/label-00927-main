<?php
declare(strict_types=1);

namespace app\common\helper;

use think\Response;

/**
 * 响应助手类
 */
class ResponseHelper
{
    /**
     * 成功响应
     *
     * @param mixed $data 响应数据
     * @param string $msg 响应消息
     * @param int $code 响应码
     * @return Response
     */
    public static function success(mixed $data = [], string $msg = 'success', int $code = 200): Response
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'timestamp' => time(),
        ]);
    }

    /**
     * 失败响应
     *
     * @param string $msg 错误消息
     * @param int $code 错误码
     * @param mixed $data 响应数据
     * @return Response
     */
    public static function error(string $msg = 'error', int $code = 400, mixed $data = []): Response
    {
        return json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'timestamp' => time(),
        ]);
    }

    /**
     * 分页响应
     *
     * @param array $list 列表数据
     * @param int $total 总数
     * @param int $page 当前页
     * @param int $limit 每页数量
     * @param string $msg 响应消息
     * @return Response
     */
    public static function paginate(
        array $list,
        int $total,
        int $page = 1,
        int $limit = 15,
        string $msg = 'success'
    ): Response {
        return json([
            'code' => 200,
            'msg' => $msg,
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 未授权响应
     *
     * @param string $msg 错误消息
     * @return Response
     */
    public static function unauthorized(string $msg = '未授权'): Response
    {
        return self::error($msg, 401);
    }

    /**
     * 禁止访问响应
     *
     * @param string $msg 错误消息
     * @return Response
     */
    public static function forbidden(string $msg = '禁止访问'): Response
    {
        return self::error($msg, 403);
    }

    /**
     * 资源不存在响应
     *
     * @param string $msg 错误消息
     * @return Response
     */
    public static function notFound(string $msg = '资源不存在'): Response
    {
        return self::error($msg, 404);
    }
}
