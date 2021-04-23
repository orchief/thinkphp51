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
        \result("Hello World!");
    }
}