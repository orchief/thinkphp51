<?php
// +----------------------------------------------------------------------
// | Description: 用户登陆事件
// +----------------------------------------------------------------------
// | Author:  orchief
// +----------------------------------------------------------------------
namespace app\base\behavior;

use app\admin\model\Membersaccount;
use app\admin\model\Members;
use Zhuzhichao\IpLocationZh\Ip;
use think\Db;
use think\facade\Request;

class login
{
    public function run($content)
    {
        // 用户登陆后续需要做的操作
        $lastLoginTime = now();
        $lastLoginIP = Request::instance()->ip();
        $add = Ip::find($lastLoginIP);
        $lastLoginAdd = '';
        if($add){
            $addr = Ip::find($lastLoginIP);
            if($addr[0] == '中国'){
                $lastLoginAdd = $addr[1];
            }
        }
        
        Db::table('shop_members')->where(['userId' => $content['userId']])->update(
            [
                'lastLoginTime' =>  $lastLoginTime,
                'lastLoginIP'   =>  $lastLoginIP,
                'lastLoginAdd'  =>  $lastLoginAdd,
            ]
        );
	}
}