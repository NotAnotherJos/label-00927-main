<?php
declare(strict_types=1);

namespace app\common\exception;

use Exception;

/**
 * 业务异常类
 */
class BusinessException extends Exception
{
    /**
     * 构造函数
     *
     * @param string $message 错误信息
     * @param int $code 错误码，默认200
     */
    public function __construct(string $message = '', int $code = 200)
    {
        parent::__construct($message, $code);
    }
}
