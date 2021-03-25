<?php
// +----------------------------------------------------------------------
// | Description: 支付宝支付回调
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use EasyWeChat\Factory;
use think\Db;
use app\shop\model\MembersAccount;
use Yansongda\Pay\Pay;
use think\facade\Log;
use Utility\Controller;

class AlipayNotify extends Controller
{
    /**
     * @return void
     */
    public function index(){
        Log::debug(['start'=> $_POST]);
        $ALIPAY = setting('ALIPAY');
        $config = [
            'app_id'            => $ALIPAY['app_id'],
            'ali_public_key'    => $ALIPAY['ali_public_key'],
            'private_key'       => $ALIPAY['private_key'],
            'http'              => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ],
        ];  
        $alipay = Pay::alipay($config);
        Log::debug(['alipays'=> $alipay]);
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            $message = $data->all();
            Log::debug(['ali_message'=> $message]);
            $payInfo = Db::table('shop_orders_status_pay')->where(['orderId' => $message['out_trade_no']])->find();
            if (!$payInfo || $payInfo['status'] != 0) {
                return true;
            }
            
            if ($message['trade_status'] === 'TRADE_SUCCESS' or $message['trade_status'] === 'TRADE_FINISHED' ) { 
                // 用户是否支付成功
                $res = $this->paySuccess($payInfo);
                if (!$res){
                    return '通信失败，请稍后再通知我';
                }
            } else {
                return '通信失败，请稍后再通知我';
            }

            return true; // 返回处理完成

        } catch (\Exception $e) {
            Log::debug(['Exception_getMessage'=> $e->getMessage()]);
            return '通信失败，请稍后再通知我';
        }

        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()`
    }


    /**
     * 支付成功后续逻辑
     *
     * @return void
     */
    function paySuccess($payInfo)
    {
        Log::debug(['payInfos'=> $payInfo]);
        if(in_string('re', $payInfo['orderId'])){
            // TODO 充值订单 插入充值记录
            $data[] = [
                'userId'    =>   $payInfo['userId'],
                'change'    =>   -$payInfo['amount'],
                'type'      =>   4,
                'remark'    =>  '支付宝充值',
            ];

            $data[] = [
                'userId'    =>   $payInfo['userId'],
                'change'    =>   $payInfo['amount'],
                'type'      =>   1,
                'remark'    =>  '支付宝充值到余额',
            ];

            MembersAccount::insertAll($data);
        }

        return Db::table('shop_orders_status_pay')->where(['orderId' => $payInfo['orderId']])
        ->update(['status' => 2, 'finishDate' => now()]);
    }
}