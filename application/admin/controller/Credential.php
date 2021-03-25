<?php
// +----------------------------------------------------------------------
// | Description: 证书管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:49:01
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;

/**
 * @route('admin/credential')
 */
class Credential extends Controller
{
    public $modelName = 'Credential';
    use \Rest\Index;
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}