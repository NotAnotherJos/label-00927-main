<?php
declare(strict_types=1);

namespace app\admin\model;

use app\common\BaseModel;
use think\facade\Db;

/**
 * 角色模型
 */
class Role extends BaseModel
{
    protected $name = 'role';
    
    protected $hidden = ['delete_time'];
    
    protected $type = [
        'id' => 'integer',
        'data_scope' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
    ];

    /**
     * 关联用户
     */
    public function users()
    {
        return $this->hasMany(AdminUser::class, 'role_id', 'id');
    }

    /**
     * 获取角色权限ID列表
     *
     * @return array
     */
    public function getPermissionIds(): array
    {
        return Db::table('tp_role_permission')
            ->where('role_id', $this->id)
            ->column('permission_id');
    }

    /**
     * 获取角色菜单ID列表
     *
     * @return array
     */
    public function getMenuIds(): array
    {
        return Db::table('tp_role_menu')
            ->where('role_id', $this->id)
            ->column('menu_id');
    }

    /**
     * 获取角色部门ID列表（自定义数据权限）
     *
     * @return array
     */
    public function getDeptIds(): array
    {
        return Db::table('tp_role_dept')
            ->where('role_id', $this->id)
            ->column('dept_id');
    }
}
