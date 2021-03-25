<?php
// +----------------------------------------------------------------------
// | Description: 微信app支付回调
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use EasyWeChat\Factory;
use think\Db;
use app\shop\model\MembersAccount;
use Utility\Controller;

class WechatAppNotify extends Controller
{
    /**
     * @return void
     */
    public function index(){
        // TODO 接收微信支付成功推送 并处理
        $configs = setting('WECHAT_OPEN');

        $config = [
            // 必要配置
            'app_id'        => $configs['app_id'], 
            'mch_id'        => $configs['mch_id'],
            'key'           => $configs['key'] // API 密钥
        ];
        
        $app = Factory::payment($config);
        $response = $app->handlePaidNotify(function($message, $fail){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $payInfo = Db::table('shop_orders_status_pay')->where(['orderId' => $message['out_trade_no']])->find();

            if (!$payInfo || $payInfo['status'] != 0) {
                return true; 
            }

            if ($message['return_code'] === 'SUCCESS') {
                return $this->paySuccess($payInfo);
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });
        
        $response->send(); // return $response;
    }

    /**
     * 支付成功后续逻辑
     *
     * @return void
     */
    function paySuccess($payInfo)
    {
        // TODO 充值
        if(in_string('re', $payInfo['orderId'])){
            // TODO 充值订单 插入充值记录
            $data[] = [
                'userId'    =>   $payInfo['userId'],
                'change'    =>   -$payInfo['amount'],
                'type'      =>   5,
                'remark'    =>  '微信充值',
                'orderId'       => $payInfo['orderId'],
                'transType'     => 4,
            ];

            $data[] = [
                'userId'    =>   $payInfo['userId'],
                'change'    =>   $payInfo['amount'],
                'type'      =>   1,
                'remark'    =>  '微信充值到余额',
                'orderId'       => $payInfo['orderId'],
                'transType'     => 4,
            ];

            if(count($data)){
                MembersAccount::insertAll($data);
            }

        }else{

            $userInfo = Db::table('shop_members')
            ->alias('a')
            ->join('shop_members b', "a.referUserId = b.userId")
            ->field('a.*, b.userId superior_id')
            ->where('a.userId',  $payInfo['userId'])
            ->find();
    
            if(!empty($userInfo)){
                $data[] = [
                    'userId'        => $userInfo['superior_id'],
                    'change'        => 0,
                    'type'          => 7,
                    'remark'        => '待发放促销商品，订单号为'.$payInfo['orderId'],
                    'orderId'       => $payInfo['orderId'],
                    'transType'     => 4,
                ];

            }

            Db::startTrans();
            try{
                if(count($data)){
                    MembersAccount::insertAll($data);
                }
    
                Db::table('shop_orders_status_pay')->where(['orderId' => $payInfo['orderId']])
                ->update(['status' => 2, 'finishDate' => now()]);
                Db::commit();
            }catch(\Exception $e){
                Db::rollback();
                return false;
            }
        }

        return true;
    }
}