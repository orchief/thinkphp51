<?php
// +----------------------------------------------------------------------
// | Description: 调动记录表
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 23:05:20
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Transfer extends Validate
{
    protected $rule = array(
        'unit' => 'length:1,255',
        'approver' => 'length:1,255',
    );
    protected $field = array(
        'student_id' => '',
        'status' => '调度状态 1 未审批 2审批通过 3 审批拒绝',
        'type' => '调度方向 1 调入 2 调出',
        'unit' => '原单位/ 调入部门 根据type区分',
        'approver' => '审批人',
        'create_time' => '申请调出/调入时间',
        'agree_time' => '通过时间',
    );
    protected $scene = [
        "create" => ["student_id","status","type","unit","approver","create_time","agree_time"],
        "update" => [
        'unit' => 'length:1,255',
        'approver' => 'length:1,255',
        ]
    ];
}

