<?php
// +----------------------------------------------------------------------
// | Description: 人员类别
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:36:16
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Cate extends Validate
{
    protected $rule = array(
        'name' => 'length:1,255',
    );
    protected $field = array(
        'name' => '',
    );
    protected $scene = [
        "create" => ["name"],
        "update" => [
        'name' => 'length:1,255',
        ]
    ];
}
