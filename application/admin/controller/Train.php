<?php
// +----------------------------------------------------------------------
// | Description: 培训管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 00:10:24
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;
use think\facade\Env;

/**
 * @route('admin/train')
 */
class Train extends Controller
{
    public $modelName = 'Train';

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
     * 获取通知模版下载地址 根据当前信息合成获得
     *
     * @return void
     */
    private function get_notice_url($word_data)
    {
        $tmp=new \PhpOffice\PhpWord\TemplateProcessor('../天津市气象局内设机构直属单位区气象局培训通知参考模板.docx');//打开模板
        $rep_field = [
            'unit', 'train_name', 'start_time', 'year', 'modality'
        ];
        foreach($rep_field as $k => $v){
            $tmp->setValue($v, $word_data[$v]);  //替换变量name
        }

        $tmp->saveAs('word/' . $word_data['unit'] . $word_data['train_name'] . '通知.docx'); //另存为
        return  Env::get('app.app_host') . 'word/' . $word_data['unit'] . $word_data['train_name'] . '通知.docx';
    }

    public function save()
    {
        $param = $this->params();
        $param['year'] = year();
        $param['notice'] = $this->get_notice_url($param);
        $this->model()->validate($param, 'create');
        $this->model()->createData($param);
        result(['msg' => '添加成功!']);
    }
    use \Rest\Update;
    use \Rest\Delete;
}