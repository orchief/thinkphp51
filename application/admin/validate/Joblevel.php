<?php
// +----------------------------------------------------------------------
// | Description: 人员职务层次
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:19:14
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Joblevel extends Validate
{
    protected $rule = array(
        'name' => 'length:1,255',
    );
    protected $field = array(
        'name' => '职务层次名称',
        'pid' => '上级职务名称',
    );
    protected $scene = [
        "create" => ["name","pid"],
        "update" => [
        'name' => 'length:1,255',
        ]
    ];
}

