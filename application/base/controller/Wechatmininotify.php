<?php
// +----------------------------------------------------------------------
// | Description: 微信小程序支付回调
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use EasyWeChat\Factory;
use think\Db;
use Utility\Controller;
use think\facade\Log;

/**
 * @route('base/wechatmininotify')
 */
class Wechatmininotify extends Controller
{
    /**
     * @return void
     */
    public function save(){
        //  接收微信支付成功推送 并处理
        $configs = setting('MIMIPROGRAM');
        Log::debug(['$configs' => $configs]);
        $config = [
            // 必要配置
            'app_id'        => $configs['app_id'], 
            'mch_id'        => $configs['mch_id'],
            'key'           => $configs['key'] // API 密钥
        ];
        $app = Factory::payment($config);
        $response = $app->handlePaidNotify(function($message, $fail){
            $payInfo = Db::table('shop_orders')->where(['id' => $message['out_trade_no']])->find();
            if (!$payInfo || $payInfo['status'] != 1) {
                return '通信失败，请稍后再通知我'; 
            }

            if ($message['return_code'] === 'SUCCESS') {
                $res = $this->paySuccess($payInfo, $message);
                if(!$res){
                    return '通信失败，请稍后再通知我';
                }
            } else {
                return '通信失败，请稍后再通知我';
            }

            return true;
        });
        
        $response->send();
    }

    /**
     * 支付成功后续逻辑.
     */
    public function paySuccess($payInfo, $message)
    {
        $content = ['payInfo' => $payInfo, 'message' => $message, 'transaction_id' => $message['transaction_id']];
        
        Db::startTrans();
        try{
            Db::table('shop_orders')->where('id', $payInfo['id'])
            ->update(['status' => 2 ]);
            Db::table('shop_orders_status_pay')->where("orderId",$payInfo["id"])
            ->update(['status' => 2 , 'finishDate' => now() , 'transactionId' => $message['transaction_id']]);
            $record = [
                'userId'        =>  $payInfo['userId'],
                'amount'        =>  -$payInfo['payment'],
                'type'          =>  3,
                'scene'         =>  1,
                'finishedTime'  =>  now(),
                'remark'        =>  '下单支付(订单号: '.$payInfo["id"].")"
            ];
            \app\admin\model\MembersAccount::memberUpdate($record);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback();
            Log::debug($e->getMessage());
            return false;
        }
        return true;
    }
}