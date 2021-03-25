<?php
// +----------------------------------------------------------------------
// | Description: 民族
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:53:42
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Nation extends Validate
{
    protected $rule = array(
        'nation' => 'length:1,30',
    );
    protected $field = array(
        'nation' => '民族名称',
    );
    protected $scene = [
        "create" => ["nation"],
        "update" => [
        'nation' => 'length:1,30',
        ]
    ];
}