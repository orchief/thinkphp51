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

class ScanOrder extends Command
{
    protected function configure()
    {
        $this->setName('scan')->setDescription('结算订单');
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
        Hook::listen('CheckOrder');
    }
}