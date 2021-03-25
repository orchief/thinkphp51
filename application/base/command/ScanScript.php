<?php
namespace app\base\command;

/**
 * 订单扫描
 */
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Hook;

class ScanScript extends Command
{
    protected function configure()
    {
        $this->setName('script')->setDescription('扫描当前运行脚本数量');
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
        // 更新系统当前正在运行脚本数量
        Hook::listen('CheckScript');
    }
}