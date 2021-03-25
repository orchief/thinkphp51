<?php
namespace app\base\command;

/**
 * 自动部署
 */
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Argument;
use think\console\input\Option;
use think\Db;
use Overtrue\Pinyin\Pinyin;

class Build extends Command
{
    protected function configure()
    {
        $this->setName('build')->setDescription('自动部署');
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
        // TODO 项目部署需要运行的代码
    }
}