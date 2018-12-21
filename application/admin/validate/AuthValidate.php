<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/11/13
 * Time: 下午3:24
 */

namespace app\admin\validate;


use think\Validate;

class AuthValidate extends Validate
{
    protected $rule =   [
        'id'  => 'require|number',
        'pid'  => 'require|number',
        'title'   => 'require',
        'before_path'   => 'require',
        'after_path'   => 'require',
    ];

    protected $message  =   [
        'id.require' => 'id必须',
        'id.number' => 'id必须是数字',
        'pid.require' => 'pid必须',
        'pid.number' => 'pid必须是数字',
        'title.require'     => '名称必须',
        'before_path.require'   => '前端路由地址必须',
        'after_path.require'  => '后端路由地址必须',
    ];

    protected $scene = [
        'add'  =>  ['pid','title','before_path','after_path'],
        'edit'  =>  ['id','title','before_path','after_path'],
        'del'  =>  ['id'],
        'disable' => ['id'],
    ];
}