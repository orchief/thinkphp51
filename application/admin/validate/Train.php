<?php
// +----------------------------------------------------------------------
// | Description: 培训管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 00:19:28
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Train extends Validate
{
    protected $rule = array(
        'train_name' => 'length:1,255',
        'train_cate_name' => 'length:1,255',
        'modality' => 'length:1,255',
        'period' => 'length:1,255',
        'charge' => 'length:1,255',
        'year' => 'length:1,255',
        'unit' => 'length:1,255',
        'notice' => 'length:1,255',
    );
    protected $field = array(
        'train_name' => '培训班名称',
        'train_cate_name' => '培训班分类 字符串类型',
        'modality' => '培训形式  面授脱产培训/网络培训',
        'open_registration' => '是否开放报名 1是 0 否',
        'start_time' => '开始时间',
        'end_time' => '结束时间',
        'period' => '学时',
        'charge' => '培训费用',
        'status' => '培训课程状态 默认1 未开始 2 进行中 3 已结束',
        'year' => '年份 只有超级管理员能看这个字段',
        'unit' => '主办单位 只有超级管理员能看到这个字段',
        'notice' => '培训通知下载链接 后台根据培训内容自动生成的',
    );
    protected $scene = [
        "create" => ["train_name","train_cate_name","modality","open_registration","start_time","end_time","period","charge","status","year","unit","notice"],
        "update" => [
        'train_name' => 'length:1,255',
        'train_cate_name' => 'length:1,255',
        'modality' => 'length:1,255',
        'period' => 'length:1,255',
        'charge' => 'length:1,255',
        'year' => 'length:1,255',
        'unit' => 'length:1,255',
        'notice' => 'length:1,255',
        ]
    ];
}

