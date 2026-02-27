<?php
declare(strict_types=1);

namespace app\admin\model;

use app\common\BaseModel;

/**
 * 部门模型
 */
class Department extends BaseModel
{
    protected $name = 'department';
    
    protected $hidden = ['delete_time'];
    
    protected $type = [
        'id' => 'integer',
        'pid' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
    ];

    /**
     * 关联父部门
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'pid', 'id');
    }

    /**
     * 关联子部门
     */
    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id')
            ->order('sort asc, id asc');
    }

    /**
     * 关联用户
     */
    public function users()
    {
        return $this->hasMany(AdminUser::class, 'dept_id', 'id');
    }

    /**
     * 获取所有子部门ID
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

    /**
     * 获取部门及所有子部门ID
     *
     * @return array
     */
    public function getAllChildrenIds(): array
    {
        return array_merge([$this->id], $this->getChildrenIds());
    }
}
