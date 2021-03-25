<?php
// +----------------------------------------------------------------------
// | Description: 证书管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:49:01
// +----------------------------------------------------------------------

namespace app\admin\validate;

use Utility\Validate;

class Credential extends Validate
{
    protected $rule = array(
        'credential' => 'length:1,255',
        'path' => 'length:1,255',
    );
    protected $field = array(
        'credential' => '证书名称',
        'path' => '证书下载地址',
    );
    protected $scene = [
        "create" => ["credential","path"],
        "update" => [
        'credential' => 'length:1,255',
        'path' => 'length:1,255',
        ]
    ];
}
