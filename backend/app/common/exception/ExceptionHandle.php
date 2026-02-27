<?php
declare(strict_types=1);

namespace app\common\exception;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use app\common\service\AppLogService;

/**
 * 全局异常处理
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用自定义日志服务记录异常
        if (!$this->isIgnoreReport($exception)) {
            AppLogService::exception($exception, [
                'url' => $this->app->request->url(true),
                'method' => $this->app->request->method(),
                'ip' => $this->app->request->ip(),
            ]);
        }
        
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 记录异常响应日志
        $this->logExceptionResponse($e);
        
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json([
                'code' => 400,
                'msg' => $e->getError(),
                'data' => [],
                'timestamp' => time(),
            ]);
        }

        // 请求异常
        if ($e instanceof HttpException) {
            return json([
                'code' => $e->getStatusCode(),
                'msg' => $e->getMessage() ?: $this->getHttpStatusMessage($e->getStatusCode()),
                'data' => [],
                'timestamp' => time(),
            ]);
        }

        // 业务异常
        if ($e instanceof BusinessException) {
            return json([
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'data' => [],
                'timestamp' => time(),
            ]);
        }

        // 数据未找到异常
        if ($e instanceof ModelNotFoundException || $e instanceof DataNotFoundException) {
            return json([
                'code' => 404,
                'msg' => '数据不存在',
                'data' => [],
                'timestamp' => time(),
            ]);
        }

        // 调试模式下返回详细错误信息
        if (env('APP_DEBUG', false)) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage(),
                'data' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ],
                'timestamp' => time(),
            ]);
        }

        // 生产环境返回通用错误信息
        return json([
            'code' => 500,
            'msg' => '服务器内部错误',
            'data' => [],
            'timestamp' => time(),
        ]);
    }

    /**
     * 记录异常响应日志
     */
    protected function logExceptionResponse(Throwable $e): void
    {
        $code = 500;
        
        if ($e instanceof BusinessException) {
            $code = $e->getCode();
        } elseif ($e instanceof HttpException) {
            $code = $e->getStatusCode();
        } elseif ($e instanceof ValidateException) {
            $code = 400;
        }
        
        AppLogService::warning('Exception response', [
            'code' => $code,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ], AppLogService::TYPE_EXCEPTION);
    }

    /**
     * 获取HTTP状态码对应的消息
     */
    protected function getHttpStatusMessage(int $code): string
    {
        $messages = [
            400 => '请求参数错误',
            401 => '未授权访问',
            403 => '禁止访问',
            404 => '资源不存在',
            405 => '请求方法不允许',
            500 => '服务器内部错误',
            502 => '网关错误',
            503 => '服务不可用',
        ];
        
        return $messages[$code] ?? '未知错误';
    }
}
