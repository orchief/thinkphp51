<?php
// +----------------------------------------------------------------------
// | Description: 1
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 12:00:22
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Order extends Validate
{
    protected $rule = array(
        'douyin_url' => 'length:1,255',
        'order_no' => 'length:1,100',
        'task_name' => 'length:1,255',
        'task_url' => 'length:1,255',
        'type' => 'length:1,255',
    );
    protected $field = array(
        'boss_id' => '发任务的老板id',
        'douyin_url' => '抖音url',
        'finished_time' => '完成任务时间',
        'order_no' => '订单编号',
        'receive_time' => '接任务的时间',
        'status' => '任务状态   未完成 任务异常   )',
        'task_id' => '任务id',
        'task_name' => '任务名称',
        'task_url' => '任务url',
        'top_price' => '置顶价格',
        'total_price' => '总价',
        'type' => '任务类型',
        'unit_price' => '单价',
        'user_id' => '接任务的用户id',
    );
    protected $scene = [
        "create" => ["boss_id","douyin_url","finished_time","order_no","receive_time","status","task_id","task_name","task_url","top_price","total_price","type","unit_price","user_id"],
        "update" => [
        'douyin_url' => 'length:1,255',
        'order_no' => 'length:1,100',
        'task_name' => 'length:1,255',
        'task_url' => 'length:1,255',
        'type' => 'length:1,255',
        ]
    ];
}
