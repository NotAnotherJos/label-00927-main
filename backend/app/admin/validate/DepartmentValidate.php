<?php
declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

/**
 * 部门验证器
 */
class DepartmentValidate extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'pid'    => 'integer|egt:0',
        'name'   => 'require|length:2,50',
        'code'   => 'alphaDash|max:50',
        'leader' => 'max:50',
        'phone'  => 'mobile',
        'email'  => 'email',
        'sort'   => 'integer|egt:0',
        'status' => 'in:0,1',
    ];

    /**
     * 错误信息
     */
    protected $message = [
        'pid.integer'    => '父部门ID必须是整数',
        'pid.egt'        => '父部门ID不能小于0',
        'name.require'   => '部门名称不能为空',
        'name.length'    => '部门名称长度必须在2-50个字符之间',
        'code.alphaDash' => '部门编码只能包含字母、数字、下划线和破折号',
        'code.max'       => '部门编码不能超过50个字符',
        'leader.max'     => '负责人不能超过50个字符',
        'phone.mobile'   => '联系电话格式不正确',
        'email.email'    => '邮箱格式不正确',
        'sort.integer'   => '排序必须是整数',
        'sort.egt'       => '排序不能小于0',
        'status.in'      => '状态值不正确',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'create' => ['pid', 'name', 'code', 'leader', 'phone', 'email', 'sort'],
        'update' => ['pid', 'name', 'code', 'leader', 'phone', 'email', 'sort', 'status'],
    ];
}
