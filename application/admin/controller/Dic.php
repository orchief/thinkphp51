<?php
// +----------------------------------------------------------------------
// | Description: 字典
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:44:14
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/dic')
 */
class Dic extends Controller
{
    public $modelName = 'Dic';
    use \Rest\Index;

    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}