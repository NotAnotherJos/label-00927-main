<?php
declare(strict_types=1);

namespace app\admin\dto;

/**
 * 菜单数据传输对象
 */
class MenuDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $pid,
        public readonly string $name,
        public readonly string $path,
        public readonly string $component,
        public readonly string $icon,
        public readonly int $type,
        public readonly string $permission,
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
            path: $data['path'] ?? '',
            component: $data['component'] ?? '',
            icon: $data['icon'] ?? '',
            type: (int)($data['type'] ?? 1),
            permission: $data['permission'] ?? '',
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
            'path' => $this->path,
            'component' => $this->component,
            'icon' => $this->icon,
            'type' => $this->type,
            'permission' => $this->permission,
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
