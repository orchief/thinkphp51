<?php
// +----------------------------------------------------------------------
// | Description: jssdk授权
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use Utility\Controller;
use EasyWeChat\Factory;

/**
 * @route('base/wxsign')
 */
class WxSign extends Controller
{
    /**
    * @OA\Get(
    *     path="/base/wxsign",
    *     tags={"微信接口"},
    *     summary="获取微信签名",
    *     @OA\Parameter(
    *         name="configUrl",
    *         in="query",
    *         description="当前页面url",
    *         required=true,
    *         @OA\Schema(
    *             type="string",
    *             format="string",
    *         )
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Invalid input"
    *     ),
    * )
    */
    public function index()
    {
        validates([
            'configUrl' =>  'require'
        ]);
        $configs = setting('WECHAT');
        $config = [
            // 必要配置
            'app_id' => $configs['app_id'],
            'mch_id' => $configs['mch_id'],
            'key'    => $configs['key'], 
            'secret' => $configs['secret']
        ];
        $app = Factory::payment($config);
        $APIs = ['chooseWXPay', 'onMenuShareWeibo', 'onMenuShareTimeline', 'onMenuShareAppMessage','checkJsApi', 'scanQRCode'];
        $url = $this->param['configUrl'];
        $app->jssdk->setUrl($url);
        $res = $app->jssdk->buildConfig($APIs, $debug = false, $beta = false, $json = true);
        $resArr = json_decode($res, true);
        result($resArr);
    }
}