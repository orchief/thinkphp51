<?php
// +----------------------------------------------------------------------
// | Description: 单位培训情况统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:36:28
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/traincount')
 */
class Traincount extends Controller
{
    public $modelName = 'Traincount';
    use \Rest\Index;

    use \Rest\Read;


}