<?php
// +----------------------------------------------------------------------
// | Description: 培训报名
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-02 19:00:52
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/registration')
 */
class Registration extends Controller
{
    public $modelName = 'Registration';
    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}