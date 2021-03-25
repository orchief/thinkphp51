<?php
// +----------------------------------------------------------------------
// | Description: 版本更新校验
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use Utility\Controller;
use think\Db;
/**
 * @route('base/version')
 */
class Version extends Controller
{  
    /**
     * @return void
     */
    public function index(){
        $param = $this->params();
        $name = $param["name"];
        $info = Db::table("base_version")->where("name",$name)->order("version desc")->find();
        result($info);
    }
}