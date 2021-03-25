<?php
// +----------------------------------------------------------------------
// | Description: 字典
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:44:14
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Dic extends Validate
{
    protected $rule = array(
        'title' => 'length:1,255',
        'type' => 'in:1,2',
        'name' => 'unique:base_setting|length:1,50',
        'remark' => 'length:1,255',
    );
    protected $field = array(
        'title' => '后台前端使用的名称',
        'type' => '数据类型  1: 配置 2: 字典',
        'name' => '程序使用的名称 全大写 下划线隔开',
        'value' => '配置值 会存储为一个json格式',
        'remark' => '参数注释',
    );
    protected $scene = [
        "create" => ["title","type","name","value","remark"],
        "update" => [
        'title' => 'length:1,255',
        'type' => 'in:1,2',
        'name' => 'unique:base_setting|length:1,50',
        'remark' => 'length:1,255',
        ]
    ];
}
