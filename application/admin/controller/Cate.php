<?php
// +----------------------------------------------------------------------
// | Description: 人员类别
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:36:16
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/cate')
 */
class Cate extends Controller
{
    public $modelName = 'Cate';
    use \Rest\Index;

    use \Rest\Read;


    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}