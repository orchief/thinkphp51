<?php
// +----------------------------------------------------------------------
// | Description: 人员职务层次
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-04 22:19:14
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/joblevel')
 */
class Joblevel extends Controller
{
    public $modelName = 'Joblevel';
    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}