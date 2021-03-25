<?php
// +----------------------------------------------------------------------
// | Description: 部门管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-02-23 12:34:30
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Section extends Validate
{
    protected $rule = array(
        'section_name' => 'length:1,255',
    );
    protected $field = array(
        'section_name' => '名称',
        'pid' => '上级部门id',
    );
    protected $scene = [
        "create" => ["section_name","pid"],
        "update" => [
        'section_name' => 'length:1,255',
        ]
    ];
}