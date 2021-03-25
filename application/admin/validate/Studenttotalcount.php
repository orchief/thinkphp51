<?php
// +----------------------------------------------------------------------
// | Description: 个人总统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 21:50:35
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Studenttotalcount extends Validate
{
    protected $rule = array(
        'username' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
        'full_time_period' => 'length:1,255',
        'full_time_days' => 'length:1,255',
        'half_time_period' => 'length:1,255',
        'half_time_days' => 'length:1,255',
        'dangxing' => 'length:1,255',
        'zhuanye' => 'length:1,255',
        'xitong' => 'length:1,255',
        'jishu' => 'length:1,255',
        'tigao' => 'length:1,255',
        'qixiang' => 'length:1,255',
        'qita' => 'length:1,255',
        'period' => 'length:1,255',
        'days' => 'length:1,255',
        'charge' => 'length:1,255',
    );
    protected $field = array(
        'student_id' => '学号',
        'username' => '姓名',
        'category' => '类别',
        'type' => '类型',
        'position' => '职务',
        'rank' => '职务层次',
        'full_time_period' => '脱产 学时',
        'full_time_days' => '脱产 学制',
        'half_time_period' => '网络 学时',
        'half_time_days' => '网络 学制',
        'dangxing' => '党性教育',
        'zhuanye' => '专业能力',
        'xitong' => '系统教育',
        'jishu' => '新技术方法',
        'tigao' => '提高政治站位修研',
        'qixiang' => '气象基础知识',
        'qita' => '其他',
        'totalnum' => '参加调训次数',
        'period' => '总学时',
        'days' => '总学制',
        'charge' => '总培训费用',
    );
    protected $scene = [
        "create" => ["student_id","username","category","type","position","rank","full_time_period","full_time_days","half_time_period","half_time_days","dangxing","zhuanye","xitong","jishu","tigao","qixiang","qita","totalnum","period","days","charge"],
        "update" => [
        'username' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
        'full_time_period' => 'length:1,255',
        'full_time_days' => 'length:1,255',
        'half_time_period' => 'length:1,255',
        'half_time_days' => 'length:1,255',
        'dangxing' => 'length:1,255',
        'zhuanye' => 'length:1,255',
        'xitong' => 'length:1,255',
        'jishu' => 'length:1,255',
        'tigao' => 'length:1,255',
        'qixiang' => 'length:1,255',
        'qita' => 'length:1,255',
        'period' => 'length:1,255',
        'days' => 'length:1,255',
        'charge' => 'length:1,255',
        ]
    ];
}