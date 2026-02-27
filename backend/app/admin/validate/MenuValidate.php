<?php
declare(strict_types=1);

namespace app\admin\validate;

use think\Validate;

/**
 * 菜单验证器
 */
class MenuValidate extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'pid'        => 'integer|egt:0',
        'name'       => 'require|length:2,50',
        'path'       => 'max:200',
        'component'  => 'max:200',
        'icon'       => 'max:50',
        'type'       => 'require|in:1,2,3',
        'permission' => 'max:100',
        'sort'       => 'integer|egt:0',
        'status'     => 'in:0,1',
    ];

    /**
     * 错误信息
     */
    protected $message = [
        'pid.integer'       => '父菜单ID必须是整数',
        'pid.egt'           => '父菜单ID不能小于0',
        'name.require'      => '菜单名称不能为空',
        'name.length'       => '菜单名称长度必须在2-50个字符之间',
        'path.max'          => '路由路径不能超过200个字符',
        'component.max'     => '组件路径不能超过200个字符',
        'icon.max'          => '图标不能超过50个字符',
        'type.require'      => '请选择菜单类型',
        'type.in'           => '菜单类型值不正确',
        'permission.max'    => '权限标识不能超过100个字符',
        'sort.integer'      => '排序必须是整数',
        'sort.egt'          => '排序不能小于0',
        'status.in'         => '状态值不正确',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'create' => ['pid', 'name', 'path', 'component', 'icon', 'type', 'permission', 'sort'],
        'update' => ['pid', 'name', 'path', 'component', 'icon', 'type', 'permission', 'sort', 'status'],
    ];
}
