<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 12:01:41
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Task extends Validate
{
    protected $rule = array(
        'task_name' => 'length:1,255',
        'task_url' => 'require|length:1,255',
    );
    protected $field = array(
        'boss_id' => '发布人ID',
        'error_num' => '异常任务数量',
        'finish_num' => '已完成数量',
        'last_num' => '进行中数量',
        'status' => '任务状态  4 未完成  5任务异常   )',
        'task_name' => '任务名称',
        'task_url' => '任务链接',
        'top_price' => '置顶价格',
        'total_num' => '任务总数量',
        'total_price' => '任务总价',
        'type' => '任务类型 1 抖音点赞 2 抖音关注',
        'unit_price' => '任务单价',
    );
    protected $scene = [
        "create" => ["boss_id","error_num","finish_num","last_num","status","task_name","task_url","top_price","total_num","total_price","type","unit_price"],
        "update" => [
        'task_name' => 'length:1,255',
        'task_url' => 'length:1,255',
        ]
    ];
}

