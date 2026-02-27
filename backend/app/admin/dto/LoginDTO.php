<?php
declare(strict_types=1);

namespace app\admin\dto;

/**
 * 登录响应数据传输对象
 */
class LoginDTO
{
    public function __construct(
        public readonly string $token,
        public readonly int $expiresIn,
        public readonly UserDTO $user,
    ) {}

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'expires_in' => $this->expiresIn,
            'user' => $this->user->toArray(),
        ];
    }
}
