<?php
// +----------------------------------------------------------------------
// | Description: 分类管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2019-09-29 13:40:33
// +----------------------------------------------------------------------

namespace app\base\controller;

use Utility\Controller;

/**
 * @route('base/cate')
 */
class Cate extends Controller
{
    public $modelName = 'Cate';
    use \Rest\Index;

    use \Rest\Read;


}