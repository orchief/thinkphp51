<?php
// +----------------------------------------------------------------------
// | Description: 调动记录表
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 23:05:20
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/transfer')
 */
class Transfer extends Controller
{
    public $modelName = 'Transfer';

    public function index()
    {
        // TODO 只能看本单位的数据
        $param = $this->params();
        $param['unit'] = \get_current_admin_unit();
        $data = $this->model()->getDataList($param);
        result($data, $this->model()->getError());
    }

    use \Rest\Read;

    /**
     * 调度和批量调度
     *
     * @return void
     */
    public function save()
    {
        $param = \validates(
            [
                'student_id'    =>  'require',
                'new_unit'   =>  'require'
            ]
        );
        $student_ids = \str2Arr($param['student_id']);
        $trans_datas = [];
        if(!is_array($student_ids)){
            $student_ids = [$student_ids];
        }
        foreach($student_ids as $v){
            $old_unit   = \continue_if(\app\admin\model\Student::where(['id' => $v])->value('unit'), ['msg' => '学员没有所属单位']);
            $trans_datas[] = [
                'student_id'    =>  $v,
                'type'          =>  1,
                'unit'          =>  $old_unit,
                'create_time'   =>  now(),
            ];
            $trans_datas[] = [
                'student_id'    =>  $v,
                'type'          =>  2,
                'unit'          =>  $param['new_unit'],
                'create_time'   =>  now(),
            ];
        }

        $this->model()->insertAllData($trans_datas);
        result(['msg' => '添加成功!']);
    }
    use \Rest\Update;
    use \Rest\Delete;
    use \Rest\Enables;
}