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
        // for($id = 5783057;$id <= 5783116;$id++){
        //     $ids[] = $id;
        // }
        // echo implode(',', $ids);

        

        // while(true){
        //     Db::table('sites')->where('installments', 'in', [1,2])->update(['last_update' => time() * 1000]);
        //     Db::table('sites')->where('id', 'in', [12, 23, 44,56,36,57,78,24,90,47,76, 39])->update(['last_update' => time()]);
        //     sleep(5);
        // }
        // $sites = Db::table('sites')->select();
        // foreach($sites as $k => $v){
        //     $this->gen_data($v);
        // }
    }

    /**
     * 判断数据是否正常
     *
     * @param [type] $has_data
     * @return bool
     */
    public function judge($site, $has_data, $type)
    {
        // 判断电量是否异常
        if($type == 1){
            if($has_data['electricity_cosume'] <= $has_data['accumulate_volume'] * 1.3 && $has_data['electricity_cosume'] >= $has_data['accumulate_volume']){
                return true;
            }else{
                return false;
            }
        }

        // 判断水量是否异常
        if($type == 2){
            if($has_data['accumulate_volume'] <= $site['capacity'] && $has_data['accumulate_volume'] >= $site['capacity'] * 60 / 100){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 生成假数据
     *
     * @return void
     */
    public function gen_data($site)
    {
        // 生成过去30天的数据
        $lastNdays = 30;
        for($i = 0; $i < $lastNdays; $i++){
            $day = date("Y-m-d", strtotime('-'. $i .' days'));
            $has_data = Db::table('reports')->where(['type' => 'Day','time_str' => $day, 'site' => $site['id']])->find();

            \var_dump('处理数据: ', $has_data);

            // 查询一样的数据 如果发现一样的数据 删除其他数据
            $delCount = Db::table('reports')->where('id', 'notin', [$has_data['id']])->where(['type' => "Day", 'time_str' => $day, 'site' => $site['id']])->delete();

            \var_dump('删除数据: '. $delCount);

            if($has_data){
                // TODO 判断水量数据是否正常
                $shui = $this->judge($site, $has_data, 2);
                if(!$shui){
                    // 更新水量
                    $accumulate_volume = bcsub($site['capacity'], ( $site['capacity'] * (mt_rand(100, 400) / 1000)), 2);
                    Db::table('reports')->where('id', $has_data['id'])->update(['accumulate_volume' => $accumulate_volume, 'timestamp' => 0, 'start_timestamp' => 0, 'end_timestamp' => 0]);
                    \var_dump('更新数据水量: ', $accumulate_volume);
                }
                // TODO 判断电量数据是否正常
                $dian = $this->judge($site, $has_data, 1);
                if(!$dian){
                    // 更新电量
                    $electricity_cosume = bcadd($has_data['accumulate_volume'], $has_data['accumulate_volume'] * (mt_rand(0, 300) / 1000), 2);
                    Db::table('reports')->where('id', $has_data['id'])->update(['electricity_cosume' => $electricity_cosume, 'timestamp' => 0, 'start_timestamp' => 0, 'end_timestamp' => 0]);
                    \var_dump('更新数据电量: ', $electricity_cosume);
                }
            }else{
                $electricity_cosume = bcsub($site['capacity'], ( $site['capacity'] * (mt_rand(100, 400) / 1000)), 2);
                $accumulate_volume = bcadd($electricity_cosume, $electricity_cosume * (mt_rand(0, 300) / 1000), 2);
                $ins_data = [
                    'type'               => 'Day',
                    'electricity_cosume' => $electricity_cosume,
                    'accumulate_volume'  => $accumulate_volume,
                    'state'              => 'Generated',
                    'time_str'           => $day,
                    'site'               => $site['id'],
                    'timestamp'        => 0,
                    'start_timestamp' => 0,
                    'end_timestamp' => 0
                ];
                Db::table('reports')->where('id', $has_data['id'])->insert($ins_data);
                \var_dump('插入数据: ', $ins_data);
            }
        }

        return;
        // 统计过去1个月的数据
        $lastNMonths = 12;
        for($i = 0; $i < $lastNMonths; $i++){
            $day = date("Y-m", strtotime('-'. $i .' months'));
            $has_data = Db::table('reports')->where(['type' => 'Mohth', 'time_str' => $day, 'site' => $site['id']])->find();
            if($has_data){
                // TODO 判断水量数据是否正常
                $shui = $this->judge($site, $has_data, 2);
                if(!$shui){
                    // 更新水量
                    $electricity_cosume = 30 * bcsub($site['capacity'], ( $site['capacity'] * (mt_rand(100, 400) / 1000)), 2);
                    Db::table('reports')->where('id', $has_data['id'])->update(['electricity_cosume' => $electricity_cosume, 'state' => 'Generated']);
                }
                // TODO 判断电量数据是否正常
                $dian = $this->judge($site, $has_data, 1);
                if(!$dian){
                    // 更新电量
                    $accumulate_volume = 30 * bcadd($has_data['accumulate_volume'], $has_data['accumulate_volume'] * (mt_rand(0, 300) / 1000), 2);
                    Db::table('reports')->where('id', $has_data['id'])->update(['electricity_cosume' => $electricity_cosume, 'state' => 'Generated']);
                }
            }else{
                $electricity_cosume = 30 * bcsub($site['capacity'], ( $site['capacity'] * (mt_rand(100, 400) / 1000)), 2);
                $accumulate_volume = 30 * bcadd($electricity_cosume, $electricity_cosume * (mt_rand(0, 300) / 1000), 2);
                $ins_data = [
                    'type'               => 'Mohth',
                    'electricity_cosume' => $electricity_cosume,
                    'accumulate_volume'  => $accumulate_volume,
                    'state'              => 'Generated',
                    'time_str'           => $day,
                    'site'               => $site['id'],
                ];
                Db::table('reports')->where('id', $has_data['id'])->insert($ins_data);
            }
        }
    }


    /**
     *
     * @return void
     */
    protected function request_add($user)
    {
        $url = "http://surveillance.huile.me:59973/inspectingMatter/createMatter";
        $response = \Httpful\Request::post($url, json_encode($user))
            ->addHeader('authorization', 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ7XCJ1c2VySWRcIjoxMDUsXCJyb2xlXCI6XCJQcmluY2lwYWxcIixcInJpZ2h0TGlzdFwiOltdLFwiZXhwaXJhdGlvblwiOjE2MDUwNjgyMzg4NTN9In0.n7IS0ST9KtS9DVNzsO5p81EailqyvYpIZX-CrJIHZhn3OYEtbv_zUxMGvuanB9lTCDfDDpLvCwmhdJcmdR9yXQ')
            ->addHeader('Content-Type', 'application/json;charset=UTF-8')
            ->expectsJson()
            ->send();
        $response;
    }
    /**
     * 记录不存在
     *
     * @return void
     */
    protected function record_not_exist($name, $tel_num)
    {
        return false;
        // return Db::table('users')->where(['nickname' => $name, 'tel_num' => $tel_num])->count();
    }

    /**
     * 人名转换成全拼
     *
     * @return void
     */
    public function to_pinyin($name)
    {
        $pinyin = new Pinyin();
        $res = $pinyin->convert($name);
        $res = \implode('', $res);
        return $res;
    }
}