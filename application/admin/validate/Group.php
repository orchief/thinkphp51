<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 11:59:27
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Group extends Validate
{
    protected $rule = array(
        'title' =>  'require|unique:admin_group',
        'remark' => 'length:1,255',
        'rules' => 'require',
    );
    protected $field = array(
        'remark' => '角色备注',
        'rules' => '规则列表id json array',
        'status' => '角色状态',
        'title' => '角色名称',
    );
    protected $scene = [
        "create" => ["remark","rules","status","title"],
        "update" => [
        'remark' => 'length:1,255',
        ]
    ];
}
