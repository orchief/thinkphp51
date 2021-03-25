<?php
// +----------------------------------------------------------------------
// | Description: 部门管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-02-23 12:34:30
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/section')
 */
class Section extends Controller
{
    public $modelName = 'Section';
    use \Rest\Index;
    use \Rest\Read;
}