<?php
// +----------------------------------------------------------------------
// | Description: 登陆和注销
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 19:54:37
// +----------------------------------------------------------------------

namespace app\admin\controller;

use Utility\Controller;
use Utility\JWT;
use think\Db;

/**
 * @route('admin/auth')
 */
class Auth extends Controller
{
    public $modelName = 'Auth';

    public function index()
    {
        $admin = Db::table('admin_user')->where('id', admin_id())->find();
        // 获取当前用户权限
        $groups = \str2Arr($admin['groups']);

        $group_list = Db::table('admin_group')->where('id', 'in', $groups)->select();

        $ids = [];
        foreach ($group_list as $k => $v) {
            $ids = array_merge($ids, \str2Arr($v['rules']));
        }

        $ids = array_unique($ids);

        $rule = new \app\admin\model\Rule();
        $param['limit'] = 10000;
        $param['id']   = $ids;
        $rule = $rule->getDataList($param);

        unset($admin['password']);

        // TODO 根据用户名 和 时间范围查询
        $where = [];
        if (isset($param['create_time'])) {
            $param['create_time'] = \str2Arr($param['create_time']);

            if (\is_array($param['create_time'])) {
                if (isset($param['create_time'][0])) {
                    $where[] = [
                        'create_time', '>=', $param['create_time'][0]
                    ];
                }

                if (isset($param['create_time'][1])) {
                    $where[] = [
                        'create_time', '<=', $param['create_time'][1]
                    ];
                }
            }
        }

        result(['rule' => $rule['list'], 'user' => $admin]);
    }

    public function save()
    {
        $param = validates(
            [
                'username'  =>  'require',
                'password'  =>  'require',
            ],
            [
                'username'  =>  '用户名',
                'password'  =>  '密码'
            ]
        );
        $admin = Db::table('admin_user')->where('username', $param['username'])->find();
        continue_if($admin['password'] == user_md5($param['password']), '密码错误!');
        $admin_id = $admin['id'];
        JWT::set('admin_id', $admin_id);
        $param['token'] = sha256(JWT::$encoded);
        $param['jwt'] = JWT::$encoded;
        $param['admin_id'] = $admin_id;
        $param['create_time'] = date('Y-m-d H:i:s');
        $this->model()->validate($param, 'create');
        $this->model()->createData($param);

        // 获取当前用户权限
        $groups = str2Arr($admin['groups']);

        $group_list = Db::table('admin_group')->where('id', 'in', $groups)->select();

        $ids = [];
        foreach ($group_list as $k => $v) {
            $ids = array_merge($ids, \str2Arr($v['rules']));
        }

        $ids = array_unique($ids);

        $rule = new \app\admin\model\Rule();
        $param['limit'] = 10000;
        $param['ids']   = $ids;
        $rule = $rule->getDataList($param);

        unset($admin['password']);

        result(['rule' => $rule['list'], 'user' => $admin]);
    }
}
