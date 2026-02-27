<?php
declare(strict_types=1);

namespace app\admin\model;

use app\common\BaseModel;

/**
 * 后台用户模型
 */
class AdminUser extends BaseModel
{
    protected $name = 'admin_user';
    
    protected $hidden = ['password', 'delete_time'];
    
    // 字段类型
    protected $type = [
        'id' => 'integer',
        'dept_id' => 'integer',
        'role_id' => 'integer',
        'data_scope' => 'integer',
        'status' => 'integer',
    ];

    /**
     * 关联部门
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    /**
     * 关联角色
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * 获取用户权限列表
     *
     * @return array
     */
    public function getPermissions(): array
    {
        // 超级管理员返回所有权限
        if ($this->role_id == 1) {
            return Permission::where('status', 1)->column('code');
        }
        
        // 获取角色权限
        $permissionIds = \think\facade\Db::table('tp_role_permission')
            ->where('role_id', $this->role_id)
            ->column('permission_id');
        
        if (empty($permissionIds)) {
            return [];
        }
        
        return Permission::whereIn('id', $permissionIds)
            ->where('status', 1)
            ->column('code');
    }

    /**
     * 检查是否有权限
     *
     * @param string $permissionCode
     * @return bool
     */
    public function hasPermission(string $permissionCode): bool
    {
        // 超级管理员拥有所有权限
        if ($this->role_id == 1) {
            return true;
        }
        
        $permissions = $this->getPermissions();
        return in_array($permissionCode, $permissions);
    }

    /**
     * 密码加密器
     *
     * @param string $value
     * @return string
     */
    public function setPasswordAttr(string $value): string
    {
        // 如果已经是hash过的密码，直接返回
        if (strlen($value) === 60 && strpos($value, '$2y$') === 0) {
            return $value;
        }
        return password_hash($value, PASSWORD_DEFAULT);
    }
}
