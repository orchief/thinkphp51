<?php
// +----------------------------------------------------------------------
// | Description: 轮播图
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2019-09-29 13:35:38
// +----------------------------------------------------------------------

namespace app\base\controller;

use Utility\Controller;

/**
 * @route('base/banners')
 */
class Banners extends Controller
{
    public $modelName = 'Banners';

    use \Rest\Read;
    use \Rest\Index;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}