<?php
// +----------------------------------------------------------------------
// | Description: 微信授权
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use think\Db;
use Utility\Controller;

/**
 * @route('base/Minitmpids')
 */
class Minitmpids extends Controller
{
    /**
     * 自动生成swagger-ui所需的json文件
     *
     * @return void
     */
    public function index(){
        $templateId = Db::table("admin_mini_message")->column("templateId");
        result($templateId);
    }
}