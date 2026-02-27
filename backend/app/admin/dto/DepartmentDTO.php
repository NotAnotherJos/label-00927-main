<?php
declare(strict_types=1);

namespace app\admin\dto;

/**
 * 部门数据传输对象
 */
class DepartmentDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $pid,
        public readonly string $name,
        public readonly string $code,
        public readonly string $leader,
        public readonly string $phone,
        public readonly string $email,
        public readonly int $sort,
        public readonly int $status,
        public readonly ?string $createTime = null,
        public readonly ?array $children = null,
    ) {}

    /**
     * 从模型创建DTO
     */
    public static function fromModel(array $data): self
    {
        return new self(
            id: (int)($data['id'] ?? 0),
            pid: (int)($data['pid'] ?? 0),
            name: $data['name'] ?? '',
            code: $data['code'] ?? '',
            leader: $data['leader'] ?? '',
            phone: $data['phone'] ?? '',
            email: $data['email'] ?? '',
            sort: (int)($data['sort'] ?? 0),
            status: (int)($data['status'] ?? 1),
            createTime: $data['create_time'] ?? null,
            children: $data['children'] ?? null,
        );
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'pid' => $this->pid,
            'name' => $this->name,
            'code' => $this->code,
            'leader' => $this->leader,
            'phone' => $this->phone,
            'email' => $this->email,
            'sort' => $this->sort,
            'status' => $this->status,
            'create_time' => $this->createTime,
        ];
        
        if ($this->children !== null) {
            $result['children'] = $this->children;
        }
        
        return $result;
    }
}
