<?php
// +----------------------------------------------------------------------
// | Description: 系统设置
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-05 12:11:04
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/setting')
 */
class Setting extends Controller
{
    public $modelName = 'Setting';
    protected function params($param = [])
    {
        // 验证数据格式是否正确
        $param = parent::params([]);
        if(isset($param['value'])){
            check_settings(json_decode($param['value'],true));
        }

        return $param;
    }

    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}