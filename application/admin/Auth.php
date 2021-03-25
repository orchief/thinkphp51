<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5.1 auth
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.wyxgn.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ;)
// +----------------------------------------------------------------------
// | Author: lqsong <957698457@qq.com>
// +----------------------------------------------------------------------
namespace app\admin;

use think\facade\Config;

class Auth
{
    /**
     * var object 对象实例
     */
    protected static $instance;
    //默认配置
    protected $config = [
        'auth_on'    => 1, // 权限开关
        'auth_type'  => 1, // 认证方式，1为实时认证；2为登录认证。
        'admin_group' => 'admin_group', // 用户组数据表名
        'admin_rule'  => 'admin_rule', // 权限规则表
        'admin_user'  => 'admin_user', // 用户信息表
    ];

    /**
     * 类架构函数
     * Auth constructor.
     */
    public function __construct()
    {
        //可设置配置项 auth, 此配置项为数组。
        if ($auth = Config::get('auth')) {
            $this->config = array_merge($this->config, $auth);
        }
    }

    /**
     * 检查权限
     * @param $uid  int          认证用户的id
     * return bool               通过验证返回true;失败返回false
     */
    public function check($uid)
    {
        if (!$this->config['auth_on']) {
            return true;
        }
        // 获取用户需要验证的所有有效规则列表
        $apiList = $this->getApiList($uid);

        $current_api = current_api();

        if(in_array($current_api, $apiList)){
            return true;
        }

        return false;
    }

    /**
     * 获取某个用户能访问的接口列表
     *
     * @return array
     */
    protected function getApiList($uid)
    {
        $list = [
            'get admin/user',
            'post admin/user',
            'get admin/rule',
            'get admin/group',
            'post admin/group',
            'delete admin/group',
            'put admin/group',
        ];
        return $list;
    }
}
