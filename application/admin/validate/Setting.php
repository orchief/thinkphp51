<?php
// +----------------------------------------------------------------------
// | Description: 系统设置
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 12:11:04
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Setting extends Validate
{
    protected $rule = array(
        'name' => 'unique:base_setting|length:1,50',
        'remark' => 'length:1,255',
        'title' => 'length:1,255',
        'type' => 'in:1,2',
    );
    protected $field = array(
        'name' => '程序使用的名称 全大写 下划线隔开',
        'remark' => '参数注释',
        'title' => '后台前端使用的名称',
        'type' => '数据类型  1: 配置 2: 字典',
        'value' => '配置值 会存储为一个json格式',
    );
    protected $scene = [
        "create" => ["name","remark","title","type","value"],
        "update" => [
        'name' => 'unique:base_setting|length:1,50',
        'remark' => 'length:1,255',
        'title' => 'length:1,255',
        'type' => 'in:1,2',
        ]
    ];
}

