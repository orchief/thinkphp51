<?php
// +----------------------------------------------------------------------
// | Description: 单位培训情况统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:36:28
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Traincount extends Validate
{
    protected $rule = array(
        'unit' => 'length:1,255',
        'train_id' => 'length:1,255',
        'trainpeople' => 'length:1,255',
        'persontime' => 'length:1,255',
        'days' => 'length:1,255',
        'hours' => 'length:1,255',
        'fee' => 'length:1,255',
    );
    protected $field = array(
        'unit' => '单位名称',
        'train_id' => '培训班id',
        'trainpeople' => '培训人数',
        'persontime' => '培训人次',
        'days' => '面授脱产培训人天数',
        'hours' => '网络培训学时',
        'fee' => '培训费用',
    );
    protected $scene = [
        "create" => ["unit","train_id","trainpeople","persontime","days","hours","fee"],
        "update" => [
        'unit' => 'length:1,255',
        'train_id' => 'length:1,255',
        'trainpeople' => 'length:1,255',
        'persontime' => 'length:1,255',
        'days' => 'length:1,255',
        'hours' => 'length:1,255',
        'fee' => 'length:1,255',
        ]
    ];
}
