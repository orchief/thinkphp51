<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 12:00:46
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Rule extends Validate
{
    protected $rule = array(
        'name' => 'length:1,255|unique:admin_rule',
        'title' => 'length:1,255|unique:admin_rule',
    );
    protected $field = array(
        'api_list' => '可访问的接口列表',
        'extra' => '前端存储的额外数据',
        'level' => '级别用于前端区分',
        'name' => '',
        'pid' => '所属父级',
        'status' => '规则状态',
        'title' => '',
    );
    protected $scene = [
        "create" => ["api_list","extra","level","name","pid","status","title"],
        "update" => [
        'name' => 'length:1,255',
        'title' => 'length:1,255',
        ]
    ];
}

