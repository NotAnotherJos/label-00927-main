<?php
declare(strict_types=1);

namespace app\admin\dto;

/**
 * 用户数据传输对象
 */
class UserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $nickname,
        public readonly string $email,
        public readonly string $phone,
        public readonly int $deptId,
        public readonly int $roleId,
        public readonly int $dataScope,
        public readonly int $status,
        public readonly ?string $avatar = null,
        public readonly ?string $deptName = null,
        public readonly ?string $roleName = null,
        public readonly ?string $lastLoginTime = null,
        public readonly ?string $lastLoginIp = null,
        public readonly ?string $createTime = null,
    ) {}

    /**
     * 从模型创建DTO
     */
    public static function fromModel(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            username: $data['username'] ?? '',
            nickname: $data['nickname'] ?? '',
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? '',
            deptId: (int)($data['dept_id'] ?? 0),
            roleId: (int)($data['role_id'] ?? 0),
            dataScope: (int)($data['data_scope'] ?? 1),
            status: (int)($data['status'] ?? 1),
            avatar: $data['avatar'] ?? null,
            deptName: $data['dept_name'] ?? null,
            roleName: $data['role_name'] ?? null,
            lastLoginTime: $data['last_login_time'] ?? null,
            lastLoginIp: $data['last_login_ip'] ?? null,
            createTime: $data['create_time'] ?? null,
        );
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'phone' => $this->phone,
            'dept_id' => $this->deptId,
            'role_id' => $this->roleId,
            'data_scope' => $this->dataScope,
            'status' => $this->status,
            'avatar' => $this->avatar,
            'dept_name' => $this->deptName,
            'role_name' => $this->roleName,
            'last_login_time' => $this->lastLoginTime,
            'last_login_ip' => $this->lastLoginIp,
            'create_time' => $this->createTime,
        ];
    }
}
