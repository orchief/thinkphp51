<?php
// +----------------------------------------------------------------------
// | Description: 用户管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 10:55:32
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class User extends Validate
{
    protected $rule = array(
        'username' => 'length:1,100',
        'password' => 'length:1,100|password',
        'phone' => 'length:1,13|phone',
        'remark' => 'length:1,100',
        'invite_code' => 'length:1,20',
    );
    protected $field = array(
        'username' => '管理后台账号',
        'password' => '管理后台密码',
        'phone' => '手机号',
        'remark' => '用户备注',
        'status' => '状态,1启用0禁用',
        'create_time' => '注册时间',
        'super_admin' => '超级管理员',
        'invite_code' => '邀请码',
        'groups' => '角色列表id josn array',
        'section_id' => '所属部门id',
    );
    protected $scene = [
        "create" => ["username","password","phone","remark","status","create_time","super_admin","invite_code","groups","section_id"],
        "update" => [
        'username' => 'length:1,100',
        'password' => 'length:1,100|password',
        'phone' => 'length:1,13|phone',
        'remark' => 'length:1,100',
        'invite_code' => 'length:1,20',
        ]
    ];
}
