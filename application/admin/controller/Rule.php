<?php
// +----------------------------------------------------------------------
// | Description: 权限规则
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 17:59:39
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/rule')
 */
class Rule extends Controller
{
    public $modelName = 'Rule';
    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
}