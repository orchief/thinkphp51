<?php
namespace app\admin;

use Utility\Controller;
use think\facade\Request;

/**
 * 控制器基础类
 */
class AdminController extends Controller
{
    protected function initialize()
    {
        parent::initialize();
        $auth = new Auth();

        $admin_id = try_admin_id();
		// if(!$auth->check($admin_id)){
		// 	abort(['msg' => '你没有权限访问']);
		// }
    }
}
