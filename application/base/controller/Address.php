<?php
// +----------------------------------------------------------------------
// | Description: 地址接口服务
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-09-04 16:45:51
// +----------------------------------------------------------------------

namespace app\base\controller;

use Utility\Controller;
/**
 * @route('base/address')
 */
class Address extends Controller
{
    public $modelName = 'Address';
    use \Rest\Index;
    use \Rest\Read;
}