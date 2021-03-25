<?php
// +----------------------------------------------------------------------
// | Description: 用户管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-02-23 12:48:17
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/student')
 */
class Student extends Controller
{
    public $modelName = 'Student';
    public function index()
    {
        // TODO 只能看本单位的数据
        $param = $this->params();
        $param['unit'] = \get_current_admin_unit();
        $data = $this->model()->getDataList($param);
        result($data, $this->model()->getError());
    }
    use \Rest\Read;
    use \Rest\Update;

    public function batch()
    {
        $params = $this->params();
        foreach($params as $param){
            $param['student_id'] = $this->model()->order('student_id desc')->limit(1)->value('student_id') + 1;
            $this->model()->insert($param);
        }

        result(['msg' => '添加成功!']);
    }

    public function save()
    {
        $param = $this->params();
        $param['student_id'] = $this->model()->order('student_id desc')->limit(1)->value('student_id') + 1;
        $this->model()->validate($param, 'create');
        $this->model()->createData($param);
        result(['msg' => '添加成功!']);
    }

}