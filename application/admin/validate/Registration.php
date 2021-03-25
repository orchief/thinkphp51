<?php
// +----------------------------------------------------------------------
// | Description: 培训报名
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-02 19:00:52
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Registration extends Validate
{
    protected $rule = array(
    );
    protected $field = array(
        'student_id' => '用户id',
        'train_id' => '培训课程id',
        'create_time' => '生成记录时间',
        'regist_time' => '报名时间',
    );
    protected $scene = [
        "create" => ["student_id","train_id","create_time","regist_time"],
        "update" => [
        ]
    ];
}