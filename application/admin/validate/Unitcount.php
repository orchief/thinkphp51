<?php
// +----------------------------------------------------------------------
// | Description: 单位统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:06:34
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Unitcount extends Validate
{
    protected $rule = array(
        'unit' => 'length:1,255',
        'classes' => 'length:1,255',
        'totalpeople' => 'length:1,255',
        'days' => 'length:1,255',
        'classhour' => 'length:1,255',
    );
    protected $field = array(
        'unit' => '培训单位',
        'classes' => '培训班次',
        'totalpeople' => '总人数',
        'days' => '面授脱产培训人天数',
        'classhour' => '网络培训总学时',
    );
    protected $scene = [
        "create" => ["unit","classes","totalpeople","days","classhour"],
        "update" => [
        'unit' => 'length:1,255',
        'classes' => 'length:1,255',
        'totalpeople' => 'length:1,255',
        'days' => 'length:1,255',
        'classhour' => 'length:1,255',
        ]
    ];
}

