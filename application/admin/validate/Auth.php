<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 11:59:56
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Auth extends Validate
{
    protected $rule = array(
        'jwt' => 'length:1,2000',
        'token' => 'unique:admin_token|length:1,255',
    );
    protected $field = array(
        'admin_id' => '',
        'create_time' => '',
        'jwt' => '',
        'token' => '',
    );
    protected $scene = [
        "create" => ["admin_id","create_time","jwt","token"],
        "update" => [
        'jwt' => 'length:1,2000',
        'token' => 'unique:admin_token|length:1,255',
        ]
    ];
}

