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
use Wrep\Daemonizable\Command\EndlessCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class SettleTask extends EndlessCommand
{
    protected function configure()
    {
        $this->setName('task')->setDescription('结算任务');
    }

    /**
     * 主体 每隔3分钟自动统计系统数据
     *
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $this->traincount();
        while(true){
            echo "hello\n";
            sleep(5);
        }
    }

    /**
     * 单位统计
     *
     * @return void
     */
    protected function traincount()
    {
        // TODO 读取单位表
        $units = $this->get_units();

        // TODO 遍历并分别统计
        foreach($units as $k => $v){
            $record = [
                'unit'          =>  $v['section_name'],
                'classes_num'   =>  1,
                'trainpeople'   =>  1,
                'persontime'    =>  1,
                'days'          =>  1,
                'hours'         =>  1,
                'fee'           =>  1,
            ];
        }
    }

    /**
     * 获取报名信息
     *
     * @return array
     */
    public function get_registration()
    {
        return [];
    }


    /**
     * 单位统计
     *
     * @return void
     */
    protected function unitcount()
    {
        // TODO 读取数据最老的时间
        // Db::table('registration')->where(['settle' => 0])->order('closing_time')-<
        Db::table('registration')->chunk(100, function($regs){
            foreach($regs as $k => $v){

            }
        });

        // TODO 按照时间遍历培训数据
        // foreach($registration as $k => $v){
        //     $record = [
        //         'unit'          =>  $v['section_name'],
        //         'classes'       =>  get_classes_by_unit($v['section_name']),
        //         'trainpeople'   =>  1,
        //         'persontime'    =>  1,
        //         'days'          =>  1,
        //         'hours'         =>  1,
        //         'fee'           =>  1,
        //     ];
        // }
    }

    /**
     * 获取所有单位信息
     *
     * @return array
     */
    protected function get_units()
    {
        return [];
    }
}