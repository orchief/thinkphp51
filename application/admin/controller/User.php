<?php
// +----------------------------------------------------------------------
// | Description: 管理员管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 18:02:17
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;
use think\Db;

/**
 * @route('admin/user')
 */
class User extends Controller
{
    public $modelName = 'User';
    use \Rest\Index;
    use \Rest\Read;

    public function save()
    {
        $param = $this->params();
        $param['password'] = user_md5($param['password']);
        $param['invite_code'] = get_invite_code();

        // TODO 检查对应角色是否下架
        $groups = \str2Arr($param['groups']);
        $count = Db::table('admin_group')->where('status', 1)->where('id', 'in', $groups)->count();

        continue_if(count($groups) == $count, '角色异常!');

        $this->model()->validate($param, 'create');
        $id = $this->model()->createData($param);
        result(['msg' => '添加成功!']);
    }

    public function update($id)
    {
        $param = $this->params();
        if(isset($param['password']) && $param['password']){
            $param['password'] = \user_md5($param['password']);
        }
        if(isset($param['userId'])){    // 需要权限的情况
            $res = $this->model()->updateUserDataById($param, $id);
        }else{
            $res = $this->model()->updateDataById($param, $id);
        }

        result(['msg' => '更新成功!']);
    }

    use \Rest\Batch;
    use \Rest\Delete;
    use \Rest\Deletes;
}