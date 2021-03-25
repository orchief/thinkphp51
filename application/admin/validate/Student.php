<?php
// +----------------------------------------------------------------------
// | Description: 用户管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-02-23 12:48:17
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Student extends Validate
{
    protected $rule = array(
        'username' => 'length:1,255',
        'student_id' => 'length:1,255',
        'gender' => 'length:1,255',
        'birthday' => 'length:1,255',
        'politics_status' => 'length:1,255',
        'nation' => 'length:1,255',
        'unit' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
    );
    protected $field = array(
        'username' => '姓名',
        'student_id' => '学号',
        'gender' => '性别',
        'birthday' => '出生日期',
        'politics_status' => '政治面貌',
        'nation' => '民族',
        'unit' => '单位',
        'category' => '人员类别',
        'type' => '人员类型',
        'position' => '职务层次',
        'rank' => '职级',
        'entrytime' => '入职时间',
        'leader' => '是否为领导',
    );
    protected $scene = [
        "create" => ["username","student_id","gender","birthday","politics_status","nation","unit","category","type","position","rank","entrytime","leader"],
        "update" => [
        'username' => 'length:1,255',
        'student_id' => 'length:1,255',
        'gender' => 'length:1,255',
        'birthday' => 'length:1,255',
        'politics_status' => 'length:1,255',
        'nation' => 'length:1,255',
        'unit' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
        ]
    ];
}

