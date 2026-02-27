<?php
declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

/**
 * 用户验证器
 */
class UserValidate extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|alphaDash|length:3,30|unique:admin_user',
        'password' => 'require|length:6,30',
        'nickname' => 'require|length:2,30',
        'email'    => 'email',
        'phone'    => 'mobile',
        'dept_id'  => 'require|integer|gt:0',
        'role_id'  => 'require|integer|gt:0',
        'status'   => 'in:0,1',
    ];

    /**
     * 错误信息
     */
    protected $message = [
        'username.require'    => '用户名不能为空',
        'username.alphaDash'  => '用户名只能包含字母、数字、下划线和破折号',
        'username.length'     => '用户名长度必须在3-30个字符之间',
        'username.unique'     => '用户名已存在',
        'password.require'    => '密码不能为空',
        'password.length'     => '密码长度必须在6-30个字符之间',
        'nickname.require'    => '昵称不能为空',
        'nickname.length'     => '昵称长度必须在2-30个字符之间',
        'email.email'         => '邮箱格式不正确',
        'phone.mobile'        => '手机号格式不正确',
        'dept_id.require'     => '请选择部门',
        'dept_id.integer'     => '部门ID必须是整数',
        'dept_id.gt'          => '请选择有效的部门',
        'role_id.require'     => '请选择角色',
        'role_id.integer'     => '角色ID必须是整数',
        'role_id.gt'          => '请选择有效的角色',
        'status.in'           => '状态值不正确',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'create' => ['username', 'password', 'nickname', 'email', 'phone', 'dept_id', 'role_id'],
        'update' => ['nickname', 'email', 'phone', 'dept_id', 'role_id', 'status'],
        'login'  => ['username', 'password'],
    ];
}
