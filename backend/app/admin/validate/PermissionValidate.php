<?php
declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

/**
 * 权限验证器
 */
class PermissionValidate extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'pid'       => 'integer|egt:0',
        'name'      => 'require|length:2,50',
        'code'      => 'require|alphaDash|max:100',
        'type'      => 'require|in:1,2',
        'path'      => 'max:200',
        'component' => 'max:200',
        'icon'      => 'max:50',
        'sort'      => 'integer|egt:0',
        'status'    => 'in:0,1',
    ];

    /**
     * 错误信息
     */
    protected $message = [
        'pid.integer'     => '父权限ID必须是整数',
        'pid.egt'         => '父权限ID不能小于0',
        'name.require'    => '权限名称不能为空',
        'name.length'     => '权限名称长度必须在2-50个字符之间',
        'code.require'    => '权限编码不能为空',
        'code.alphaDash'  => '权限编码只能包含字母、数字、下划线和破折号',
        'code.max'        => '权限编码不能超过100个字符',
        'type.require'    => '请选择权限类型',
        'type.in'         => '权限类型值不正确',
        'path.max'        => '路由路径不能超过200个字符',
        'component.max'   => '组件路径不能超过200个字符',
        'icon.max'        => '图标不能超过50个字符',
        'sort.integer'    => '排序必须是整数',
        'sort.egt'        => '排序不能小于0',
        'status.in'       => '状态值不正确',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'create' => ['pid', 'name', 'code', 'type', 'path', 'component', 'icon', 'sort'],
        'update' => ['pid', 'name', 'code', 'type', 'path', 'component', 'icon', 'sort', 'status'],
    ];
}
