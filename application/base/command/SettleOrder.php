<?php

namespace app\base\command;

/**
 * 订单扫描
 */

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Argument;
use think\console\input\Option;
use think\facade\Hook;
use Utility\JWT;
use think\Db;

class SettleOrder extends Command
{
    protected function configure()
    {
        $this->setName('settle')->setDescription('结算订单');
    }

    /**
     * 主体
     *
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $this->configs = setting('reward');
        // TODO 结算订单
        Db::table('user_order')
            ->where([
                ['status', '=', 2],
                ['settle', '=', 0]
            ])->chunk(100, function ($list) {
                foreach ($list as $k => $order) {
                    // TODO 当前用户 入账金额
                    $user_id = $order['user_id'];
                    $user = Db::table('user')->where('user_id', $user_id)->find();
                    $user_unit_price = $this->get_user_unit_price(1, $order['type']);
                    $after_amount = $user['balance'] + $user_unit_price;
                    $account_record = [
                        'type'          =>  1,
                        'scene'         =>  3,
                        'income'        =>  $user_unit_price,
                        'user_id'       =>  $user['user_id'],
                        'remark'        =>  '到账',
                        'after_amount'  =>  $after_amount,
                        'record_no'     =>  get_order_no(),
                    ];

                    try {
                        Db::table('user_account_record')->insertGetId($account_record);
                        Db::table('user')->where('user_id', $user['user_id'])->update(['balance' => $after_amount]);

                        // TODO 上级入账金额
                        $user_id = $this->get_refer_user_id($user);
                        if ($user_id) {
                            $user = Db::table('user')->where('user_id', $user_id)->find();
                            $user_unit_price = $this->get_user_unit_price(2, $order['type']);
                            $after_amount = $user['balance'] + $user_unit_price;
                            $account_record = [
                                'type'          =>  1,
                                'scene'         =>  4,
                                'income'      =>  $user_unit_price,
                                'user_id'       =>  $user['user_id'],
                                'remark'        =>  '上下级提成',
                                'after_amount'  =>  $after_amount,
                                'record_no'     =>  get_order_no(),
                            ];

                            Db::table('user_account_record')->insertGetId($account_record);
                            Db::table('user')->where('user_id', $user['user_id'])->update(['balance' => $after_amount]);

                            $user_id = $this->get_refer_user_id($user);
                            if ($user_id) {
                                $user = Db::table('user')->where('user_id', $user_id)->find();
                                $user_unit_price = $this->get_user_unit_price(3, $order['type']);
                                $after_amount = $user['balance'] + $user_unit_price;
                                $account_record = [
                                    'type'          =>  1,
                                    'scene'         =>  4,
                                    'income'        =>  $user_unit_price,
                                    'user_id'       =>  $user['user_id'],
                                    'remark'        =>  '上下级提成',
                                    'after_amount'  =>  $after_amount,
                                    'record_no'     =>  get_order_no(),
                                ];

                                Db::table('user_account_record')->insertGetId($account_record);
                                Db::table('user')->where('user_id', $user['user_id'])->update(['balance' => $after_amount]);
                            }
                        }

                        Db::table('user_order')->where('id', $order['id'])->update(['settle' => 1]);
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        continue;
                    }
                }
            });
    }

    /**
     * 获取
     *
     * @return void
     */
    public function get_refer_user_id($user)
    {
        return $user['refer_user_id'];
    }

    /**
     * 获取奖励金额
     *
     * @param [type] $level
     * @return void
     */
    public function get_user_unit_price($level, $type)
    {
        $arr = [
            '1' => [
                '1' =>  'douyin_dianzan',
                '2' =>  'douyin_follow'
            ],
            '2' => [
                '1' =>  'douyin_dianzan_refer',
                '2' =>  'douyin_follow_refer'
            ],
            '3' => [
                '1' =>  'douyin_dianzan_indirect',
                '2' =>  'douyin_follow_indirect'
            ]
        ];
        return $this->configs[$arr[$level][$type]];
    }
}
