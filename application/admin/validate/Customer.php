<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 11:40:47
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Customer extends Validate
{
    protected $rule = array(
        'password' => 'length:1,255|password',
        'username' => 'length:1,255|unique:user',
    );
    protected $field = array(
        'aliaccount' => '阿里云账户',
        'aliname' => '阿里云姓名',
        'balance' => '余额',
        'create_time' => '创建时间',
        'douyin_url' => '抖音url',
        'password' => '密码',
        'refer_area_id' => '推荐渠道id',
        'refer_userid' => '推荐人',
        'role' => '用户角色  1 普通用户 2 客户',
        'status' => '状态 0 启用 1 禁用',
        'user_id' => '',
        'username' => '用户名',
    );
    protected $scene = [
        "create" => ["password", "username"],
        "update" => [
            'aliaccount' => 'length:1,255',
            'aliname' => 'length:1,255',
            'douyin_url' => 'length:1,255',
            'password' => 'length:1,255|password',
            'status' => 'length:1,255',
            'username' => 'length:1,255',
        ]
    ];
}
