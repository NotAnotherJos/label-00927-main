<?php
declare(strict_types=1);

namespace app\admin\dto;

/**
 * 角色数据传输对象
 */
class RoleDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $code,
        public readonly int $dataScope,
        public readonly string $remark,
        public readonly int $sort,
        public readonly int $status,
        public readonly ?string $createTime = null,
        public readonly ?array $permissions = null,
        public readonly ?array $menus = null,
    ) {}

    /**
     * 从模型创建DTO
     */
    public static function fromModel(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            name: $data['name'] ?? '',
            code: $data['code'] ?? '',
            dataScope: (int)($data['data_scope'] ?? 1),
            remark: $data['remark'] ?? '',
            sort: (int)($data['sort'] ?? 0),
            status: (int)($data['status'] ?? 1),
            createTime: $data['create_time'] ?? null,
            permissions: $data['permissions'] ?? null,
            menus: $data['menus'] ?? null,
        );
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'data_scope' => $this->dataScope,
            'remark' => $this->remark,
            'sort' => $this->sort,
            'status' => $this->status,
            'create_time' => $this->createTime,
            'permissions' => $this->permissions,
            'menus' => $this->menus,
        ];
    }
}
