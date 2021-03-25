<?php
// +----------------------------------------------------------------------
// | Description: 微信授权
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;
use Jenssegers\Optimus\Optimus;
use Utility\Controller;
use Bank\Bank;
use Yansongda\Pay\Pay;
use think\Db;
/**
 * @route('base/index')
 */
class Index extends Controller
{
    /**
     * @return void
     */
    public function index(){

        $content = [
            'type' => 'delivered',
            'userId' => '4830',
            'content' => ['44444', now(), ""]
        ];
        \think\facade\Hook::listen('minipush', $content);

        // $content = [
        //     'type' => 'expire',
        //     'userId' => '4830',
        //     'content' => ['2323223', now(), "dadfs"]
        // ];
        // \think\facade\Hook::listen('minipush', $content);
    }
}