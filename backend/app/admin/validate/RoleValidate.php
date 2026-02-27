<?php
declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

/**
 * 角色验证器
 */
class RoleValidate extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'name'       => 'require|length:2,50',
        'code'       => 'require|alphaDash|length:2,50|unique:role',
        'data_scope' => 'require|in:1,2,3,4,5',
        'sort'       => 'integer|egt:0',
        'status'     => 'in:0,1',
        'remark'     => 'max:255',
    ];

    /**
     * 错误信息
     */
    protected $message = [
        'name.require'       => '角色名称不能为空',
        'name.length'        => '角色名称长度必须在2-50个字符之间',
        'code.require'       => '角色编码不能为空',
        'code.alphaDash'     => '角色编码只能包含字母、数字、下划线和破折号',
        'code.length'        => '角色编码长度必须在2-50个字符之间',
        'code.unique'        => '角色编码已存在',
        'data_scope.require' => '请选择数据权限',
        'data_scope.in'      => '数据权限值不正确',
        'sort.integer'       => '排序必须是整数',
        'sort.egt'           => '排序不能小于0',
        'status.in'          => '状态值不正确',
        'remark.max'         => '备注不能超过255个字符',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'create' => ['name', 'code', 'data_scope', 'sort', 'remark'],
        'update' => ['name', 'data_scope', 'sort', 'status', 'remark'],
    ];
}
