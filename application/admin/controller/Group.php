<?php
// +----------------------------------------------------------------------
// | Description: 角色管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 17:35:14
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController;
use think\Db;

/**
 * @route('admin/group')
 */
class Group extends AdminController
{
    public $modelName = 'Group';
    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Batch;
    use \Rest\Delete;

    public function deletes()
    {
        $param = $this->params();
        $this->check_group_in_use(str2Arr($param['ids']));
        if(isset($param['ids'])){
            $param['ids'] = str2Arr($param['ids']);
            $res = $this->model()
            ->where($this->model()->getPk(), 'in', $param['ids'])
            ->delete();
        }else{
            abort(['msg' => '删除失败!']);
        }

        result(['msg' => '删除成功!', 'deleteCount' => $res]);
    }

    /**
     * 删除和批量删除
     *
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        // 检查是否正在使用中
        $this->check_group_in_use(\str2Arr($id));
        $param = $this->params();
        if(isset($param['userId'])){    // 需要权限的情况
            $res = $this->model()->delUserDatas(str2Arr($id), $param['userId']);
        }else{
            $res = $this->model()->delDatas(str2Arr($id));
        }
        result(['msg' => '删除成功!']);
    }

    /**
     * 检查角色是否正在使用中
     */
    protected function check_group_in_use($ids)
    {
        $admin_user = Db::table('admin_user')->select();
        $group_ids = [];
        foreach($admin_user as $k => $v){
            $group_ids = \array_merge(\str2Arr($v['groups']), $group_ids);
        }

        // 强行变成数组 兼容前端
        if(!is_array($ids)){
            $ids = [$ids];
        }
        foreach($ids as $k => $v){
            continue_if(!in_array($v, $group_ids), '当前角色正在使用中!');
        }
    }
}