<?php
// +----------------------------------------------------------------------
// | Description: 个人统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 21:18:08
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Studentcount extends Validate
{
    protected $rule = array(
        'username' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
        'train_name' => 'length:1,255',
        'unit' => 'length:1,255',
        'modality' => 'length:1,255',
        'DiaoXun' => 'length:1,255',
        'train_cate_name' => 'length:1,255',
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
        'train_name' => '培训名称',
        'unit' => '主办单位',
        'modality' => '培训形式',
        'DiaoXun' => '是否调训',
        'train_cate_name' => '培训类型',
        'period' => '学时',
        'days' => '学制',
        'charge' => '培训费用',
    );
    protected $scene = [
        "create" => ["student_id","username","category","type","position","rank","train_name","unit","modality","DiaoXun","train_cate_name","period","days","charge"],
        "update" => [
        'username' => 'length:1,255',
        'category' => 'length:1,255',
        'type' => 'length:1,255',
        'position' => 'length:1,255',
        'rank' => 'length:1,255',
        'train_name' => 'length:1,255',
        'unit' => 'length:1,255',
        'modality' => 'length:1,255',
        'DiaoXun' => 'length:1,255',
        'train_cate_name' => 'length:1,255',
        'period' => 'length:1,255',
        'days' => 'length:1,255',
        'charge' => 'length:1,255',
        ]
    ];
}

