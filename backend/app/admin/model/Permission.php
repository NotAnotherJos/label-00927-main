<?php
declare(strict_types=1);

namespace app\admin\model;

use app\common\BaseModel;

/**
 * 权限模型
 */
class Permission extends BaseModel
{
    protected $name = 'permission';
    
    protected $hidden = ['delete_time'];
    
    protected $type = [
        'id' => 'integer',
        'pid' => 'integer',
        'type' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
    ];

    /**
     * 关联父权限
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'pid', 'id');
    }

    /**
     * 关联子权限
     */
    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id')
            ->order('sort asc, id asc');
    }

    /**
     * 获取所有子权限ID
     *
     * @return array
     */
    public function getChildrenIds(): array
    {
        $ids = [];
        $children = self::where('pid', $this->id)->column('id');
        foreach ($children as $childId) {
            $ids[] = $childId;
            $child = self::find($childId);
            if ($child) {
                $ids = array_merge($ids, $child->getChildrenIds());
            }
        }
        return $ids;
    }
}
