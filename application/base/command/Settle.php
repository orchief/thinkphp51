<?php
namespace app\base\command;

/**
 * 系统结算
 *
 * 1	返佣通知	rebate
恭喜您！您的${user}购买{$money}元大礼包{$amount}元。
2	积分变更	point	${content}${point}积分。
3	到货通知	arrival	您的货物(订单号：${orderId})已到货。
4	发货通知	delivered	您的货物(订单号：${orderId})已发货。
5	等级变更	agent	恭喜您！您当前等级为${agent}。
 */
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Settle extends Command
{
    /**
     * @var array 插入账户记录表的记录
     */
    protected $record = [];

    protected function configure()
    {
        $this->setName('settle')->setDescription('系统结算');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $this->time = time();
        // 结算订单
        Db::table('shop_orders')->where(
            [
                ['settled', '=', 0],
                ['status', 'in', [2,3,4,6,8]],
                ['productType', 'in', [1, 3, 4]]
            ]
        )->chunk(100, function($list){
            foreach($list as $k => $v){
                Db::startTrans();
                try{
                    $orderId = $v['id'];
                    $userId  = $v['userId'];
                    // 将当前订单状态改为已经结算
                    Db::table('shop_orders')->where('id', $orderId)->update(['settled' => 1]);
                    // 用户推荐人绑定 / 更新绑定时间
                    $userInfo = $this->getUserInfo($userId);
                    if(!$userInfo['referUserId']){
                        if($userInfo['tempReferUserId']){
                            Db::table('shop_members')->where('userId', $userId)
                            ->update(['referUserId' => $userInfo['tempReferUserId'], 'referTime' => $v['createDate']]);
                        }else{
                            if($v['tempReferUserId']){
                                Db::table('shop_members')->where('userId', $userId)
                                ->update(['referUserId' => $v['tempReferUserId'], 'referTime' => $v['createDate']]);
                            }
                        }
                    }
                    $this->record = []; // 重置账户变动记录
                    $this->active($v, $userId);
                    // 大礼包结算
                    $this->record = []; // 重置账户变动记录
                    $this->upgrade($v, $userId);
                    // 领取产品结算
                    $this->record = []; // 重置账户变动记录
                    $this->free($v, $userId);
                    Db::commit();
                }catch(\Exception $e){
                    Db::rollback();
                    // TODO: 记录异常原因 并终止程序
                }
            }
        }, 'createDate');

        // 发放7天之前的待发放红包
        $day = 7;
        $last7day = date('Y-m-d H:i:s', time() - $day * 24 * 60 * 60);
        Db::table('shop_members_account')
        ->where([
            ['type', '=', 2],
            ['status', '=', 0],
            ['createTime', '<=', $last7day]
        ])->chunk(100, function($list){
            foreach($list as $v){
                $userId = $v['userId'];
                Db::startTrans();
                try{
                    Db::table('shop_members_account')->where('id', $v['id'])->update(['status' => 1, 'finishedTime' => now()]);
                    Db::table('shop_members')->where('userId', $userId)->setInc('reward', $v['amount']);
                    Db::commit();
                }catch(\Exception $e){
                    Db::rollback();die;
                    // TODO: 记录异常原因 并终止程序
                }
            }
        });

        //TODO: 记录本次运行记录

    }

    /**
     * 所有用户购买活动商品
     *
     * @return void
     */
    protected function active($orderInfo, $userId)
    {
        // TODO 只处理活动商品
        if($orderInfo['productType'] != 4 && $orderInfo['productType'] != 1){
            return;
        }
        $orderId = $orderInfo['id'];
        $userInfo = $this->getUserInfo($userId);

        // TODO 活动商品给上一级发放固定奖励
        // TODO 直接推荐人分佣 / 间接推荐人分佣
        $arr = [
            '1' =>  '直属',
        ];

        $level = 1;
        $referLevelName = $arr[$level];
        $scene = 4 + $level;
        $referInfo = $this->getReferInfo($userInfo, $level);
        if($referInfo && $referInfo['agentId'] && $referInfo['vip_expire_time'] >= $this->time){
            $amount   = $this->getActiveReferAmount();

            if((float)$amount){
                $this->record[] = [
                    'userId'    =>  $referInfo['userId'],
                    'amount'    =>  $amount,
                    'remark'    =>  "分享好友(" . $this->getStarPhone($userInfo['phone']) . ")领取产品得到".$amount."元红包",
                    'orderId'   =>  $orderId,
                    'type'      =>  2,
                    'status'    =>  0,
                    'scene'     =>  $scene,
                ];

                // 更新总红包获取值
                Db::table('shop_members')->where('userId', $referInfo['userId'])->setInc('rewardTotal', $amount);

                // 分佣通知 rebate 恭喜您！您的${user}购买{$money}元大礼包{$amount}元。
                $content = [
                    'userId'    =>  $referInfo['userId'],
                    'name'      =>  'active',
                    'content'   =>  [$referInfo['nickname'], $amount]
                ];
                \think\facade\Hook::listen("Message",$content);
            }
        }
        // TODO
        Db::table('shop_members_account')->insertAll($this->record);
    }

    /**
     * 获取推荐购买活动商品获得的奖励金额
     */
    public function getActiveReferAmount()
    {
        return 1;
    }

    /**
     * 免费领取产品上级获得积分奖励
     */
    protected function free($orderInfo, $userId)
    {// 只处理productType=1的订单
        if($orderInfo['productType'] != 1){
            return;
        }
        $orderId = $orderInfo['id'];
        $userInfo = $this->getUserInfo($userId);
        $this->freePoint($userInfo, $orderInfo);
        if(count($this->record)){
            Db::table('shop_members_account')->insertAll($this->record);
        }
    }

    /**
     * 用户购买套包升级等级
     * 收款方式（记录类型） 1: 余额  2: 奖金  3: 微信 4: 银行卡 5: 支付宝 6:积分
     * 场景  例如 1: 支付 2: 充值  3: 一层团队长奖励 4 二层团队长奖励 5 直属奖励 6 间接推荐奖励 7 直接推荐奖励积分 8 间接推荐奖励积分
     * 10 团队新增会员团队长获得积分奖励 11 领取产品获得积分 12 直接推荐人领取产品获得积分 13 间接推荐人领取产品获得积分
     * 14 团队会员领取产品团队长获得积分奖励 15 抽奖奖励 16 抽奖消耗
     * @param string $orderId 订单号
     * @param int $userId 用户id
     * @return void
     */
    protected function upgrade($orderInfo, $userId)
    {
        // 只处理productType=3的订单
        if($orderInfo['productType'] != 3){
            return;
        }
        $orderId = $orderInfo['id'];
        $userInfo = $this->getUserInfo($userId);
        $agentId = $userInfo['agentId'];
        $currentAgentLevel = $this->getAgentLevel($agentId);
        $toAgentId = $this->getToAgentId($orderId);
        $toAgentLevel = $this->getAgentLevel($toAgentId);

        // 用户等级升级 用户可以重复购买会员
        if($toAgentLevel >= 1){
            // 新等级大于原等级 升级
            $this->upAgentId($userId, $toAgentId, $agentId, $orderInfo);

            // 用户自身等级到达设置的会员等级后并且发展直属会员人数 到达设置人数，自动升级成为团队长；
            // 检查自己和上级
            $configs = setting('TEAM');
            $this->checkTeamGrade($configs, $userInfo, $toAgentId, $currentAgentLevel);

            // 检查上级
            $referInfo = $this->getReferInfo($userInfo, 1);
            if($referInfo){
                $referCurrentAgentLevel = $this->getAgentLevel($referInfo['agentId']);
                $this->checkTeamGrade($configs, $referInfo, $referInfo['agentId'], $referCurrentAgentLevel);
            }

            // 如果是第一次成为等级会员 则上级返还积分
            // if($currentAgentLevel == 0){
                $this->agentPoint($userInfo, $orderInfo);
            // }
        }

        // TODO 直接推荐人分佣 / 间接推荐人分佣
        $arr = [
            '1' =>  '直属',
            '2' =>  '间接'
        ];
        for($level = 1; $level <=2; $level++){
            $referLevelName = $arr[$level];
            $scene = 4 + $level;
            $referInfo = $this->getReferInfo($userInfo, $level);
            if($referInfo && $referInfo['agentId']){
                $amount   = $this->getReferAmount($userInfo, $referInfo, $level, $toAgentId);

                if((float)$amount){
                    $this->record[] = [
                        'userId'    =>  $referInfo['userId'],
                        'amount'    =>  $amount,
                        'remark'    =>  "推荐好友(VIP会员充值".$this->getStarPhone($userInfo['phone']).")",
                        'orderId'   =>  $orderId,
                        'type'      =>  2,
                        'status'    =>  0,
                        'scene'     =>  $scene,
                    ];

                    // 更新总红包获取值
                    Db::table('shop_members')->where('userId', $referInfo['userId'])->setInc('rewardTotal', $amount);

                    // 分佣通知 rebate 恭喜您！您的${user}购买{$money}元大礼包{$amount}元。
                    $content = [
                        'userId'    =>  $referInfo['userId'],
                        'name'      =>  'rebate',
                        'content'   =>  [$referInfo['nickname'], '('.$this->getStarPhone($userInfo['phone']).')', $orderInfo['originTotal'], '+'.$amount]
                    ];
                    \think\facade\Hook::listen("Message",$content);
                }
            }

        }

        Db::table('shop_members_account')->insertAll($this->record);
    }

    /**
     * 第一次升级为会员 上级/上上级/n层团队长 获得积分
     */
    protected function agentPoint($userInfo, $orderInfo)
    {
        $configs = setting('AGENT');
        // 直接推荐人
        $referUserId = $userInfo['referUserId'];
        if($referUserId){

            $referUserInfo = Db::table('shop_members')->where('userId', $referUserId)->find();
            if($referUserInfo['agentId'] && $referUserInfo['vip_expire_time'] >= $this->time){

                if((float)$configs['directly']){
                    $this->record[] = [
                        'userId'    =>  $referUserId,
                        'amount'    =>  $configs['directly'],
                        'remark'    =>  "推荐好友(VIP会员充值".$this->getStarPhone($userInfo['phone']).")",
                        'orderId'   =>  $orderInfo['id'],
                        'type'      =>  6,
                        'status'    =>  1,
                        'scene'     =>  7,
                    ];
                    // 增加积分余额
                    Db::table('shop_members')->where('userId', $referUserId)->setInc('point', $configs['directly']);

                    // 积分变更通知 point ${content}${point}积分。
                    $content = [
                        'userId'    =>  $referUserId,
                        'name'      =>  'point',
                        'content'   =>  [$referUserInfo['nickname'] . '成功发展('.$this->getStarPhone($userInfo['phone']).')', '+'.$configs['directly']]
                    ];
                    \think\facade\Hook::listen("Message",$content);
                }

                $content = [
                    'type'      =>  'point',
                    'content'   =>  ['您成功发展直属会员('.$this->getStarPhone($userInfo['phone']).')', '+'.$configs['directly'], now()],
                    'userId'    =>  $referUserId,
                ];
                \think\facade\Hook::listen("minipush", $content);
            }

            $referUserInfo = Db::table('shop_members')->where('userId', $referUserId)->find();
            $referReferUserId = $referUserInfo['referUserId'];
            if($referReferUserId){
                $referReferUserInfo = Db::table('shop_members')->where('userId', $referReferUserId)->find();
                if($referReferUserInfo['agentId'] && $referReferUserInfo['vip_expire_time'] >= $this->time){
                    // 间接推荐人

                    if((float)$configs['indirect']){
                        $this->record[] = [
                            'userId'    =>  $referReferUserId,
                            'amount'    =>  $configs['indirect'],
                            'remark'    =>  "推荐好友(".$this->getStarPhone($userInfo['phone']).")",
                            'orderId'   =>  $orderInfo['id'],
                            'type'      =>  6,
                            'status'    =>  1,
                            'scene'     =>  8,
                        ];

                        // 增加积分余额
                        Db::table('shop_members')->where('userId', $referReferUserId)->setInc('point', $configs['indirect']);

                        // 积分变更通知 point ${content}${point}积分。
                        $content = [
                            'userId'    =>  $referReferUserId,
                            'name'      =>  'point',
                            'content'   =>  [ $referReferUserInfo['nickname'] . '成功发展('.$this->getStarPhone($userInfo['phone']).')', '+'.$configs['indirect']]
                        ];
                        \think\facade\Hook::listen("Message",$content);
                    }

                    $content = [
                        'type'      =>  'point',
                        'content'   =>  ['您成功发展间接会员('.$this->getStarPhone($userInfo['phone']).')', '+'.$configs['indirect'], now()],
                        'userId'    =>  $referReferUserId,
                    ];
                    \think\facade\Hook::listen("minipush",$content);
                }
            }
        }

    }

    /**
     * 领取产品 自己/上级/上上级/n层团队长 获得积分
     */
    protected function freePoint($userInfo, $orderInfo)
    {
        $configs = setting('GETGOODS');
        // 自己获得积分
        if((float)$configs['point']){
            $this->record[] = [
                'userId'    =>  $userInfo['userId'],
                'amount'    =>  $configs['point'],
                'remark'    =>  "领取产品(订单号:".$orderInfo['id'].")",
                'orderId'   =>  $orderInfo['id'],
                'type'      =>  6,
                'status'    =>  1,
                'scene'     =>  11,
            ];
            // 增加积分余额
            Db::table('shop_members')->where('userId', $userInfo['userId'])->setInc('point', $configs['point']);

            // 积分变更通知 point ${content}${point}积分。
            // $referLevelName = $arr[$level];
            $content = [
                'userId'    =>  $userInfo['userId'],
                'name'      =>  'point',
                'content'   =>  [$userInfo['nickname'] . '成功领取产品', '+'.$configs['point']]
            ];
            \think\facade\Hook::listen("Message",$content);
        }

        $content = [
            'type'      =>  'point',
            'content'   =>  ['您成功领取产品', '+'.$configs['point'], now()],
            'userId'    =>  $userInfo['userId']
        ];
        \think\facade\Hook::listen("minipush", $content);

        // 直接推荐人
        $referUserId = $userInfo['referUserId'];
        if($referUserId){

            $referUserInfo = Db::table('shop_members')->where('userId', $referUserId)->find();

            // 推荐人不能是普通用户
            if($referUserInfo['agentId'] && $referUserInfo['vip_expire_time'] >= $this->time){
                if((float)$configs['directly']){
                    $this->record[] = [
                        'userId'    =>  $referUserId,
                        'amount'    =>  $configs['directly'],
                        'remark'    =>  "分享好友(".$this->getStarPhone($userInfo['phone']).")成功领取产品获得奖励",
                        'orderId'   =>  $orderInfo['id'],
                        'type'      =>  6,
                        'status'    =>  1,
                        'scene'     =>  12,
                    ];
                    // 增加积分余额
                    Db::table('shop_members')->where('userId', $referUserId)->setInc('point', $configs['directly']);

                    $content = [
                        'userId'    =>  $referUserId,
                        'name'      =>  'point',
                        'content'   =>  [ $referUserInfo['nickname'] . '的('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['directly']]
                    ];
                    \think\facade\Hook::listen("Message",$content);

                    $content = [
                        'type'      =>  'point',
                        'content'   =>  ['您的('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['directly'], now()],
                        'userId'    =>  $referUserId
                    ];
                    \think\facade\Hook::listen("minipush", $content);
                }
            }

            $referReferUserId = $referUserInfo['referUserId'];
            if($referReferUserId){

                $referReferUserInfo = Db::table('shop_members')->where('userId', $referReferUserId)->find();

                // 间接推荐人不能是普通用户
                if($referReferUserInfo['agentId'] && $referReferUserInfo['vip_expire_time'] >= $this->time){
                    if((float)$configs['indirect']){
                        $this->record[] = [
                            'userId'    =>  $referReferUserId,
                            'amount'    =>  $configs['indirect'],
                            'remark'    =>  "推荐好友(".$this->getStarPhone($userInfo['phone']).")成功领取产品[订单号:".$orderInfo['id']."]获得奖励",
                            'orderId'   =>  $orderInfo['id'],
                            'type'      =>  6,
                            'status'    =>  1,
                            'scene'     =>  13,
                        ];

                        // 增加积分余额
                        Db::table('shop_members')->where('userId', $referReferUserId)->setInc('point', $configs['indirect']);

                        // 积分变更通知 point ${content}${point}积分。
                        $content = [
                            'userId'    =>  $referReferUserId,
                            'name'      =>  'point',
                            'content'   =>  [ $referReferUserInfo['nickname'] . '的('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['indirect']]
                        ];
                        \think\facade\Hook::listen("Message",$content);

                        $content = [
                            'type'      =>  'point',
                            'content'   =>  ['您的间接会员('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['indirect'], now()],
                            'userId'    =>  $referReferUserId
                        ];
                        \think\facade\Hook::listen("minipush", $content);
                    }
                }
            }
        }

        // 无限级团队长获得积
        $userId = $userInfo['userId'];

        $level = 1;
        $teamInfo = $this->getTeamInfo($userId, $level);

        // 仅限于两层团长
        while($teamInfo && $level <= 2){
            if((float)$configs['team']){
                $this->record[] = [
                    'userId'    =>  $teamInfo['userId'],
                    'amount'    =>  $configs['team'],
                    'remark'    =>  "推荐好友(".$this->getStarPhone($userInfo['phone']).")成功领取产品[订单号:".$orderInfo['id']."]获得奖励",
                    'orderId'   =>  $orderInfo['id'],
                    'type'      =>  6,
                    'status'    =>  1,
                    'scene'     =>  14,
                ];

                // 增加积分余额
                Db::table('shop_members')->where('userId', $teamInfo['userId'])->setInc('point', $configs['team']);

                // 积分变更通知 point ${content}${point}积分。
                $content = [
                    'userId'    =>  $teamInfo['userId'],
                    'name'      =>  'point',
                    'content'   =>  [$teamInfo['nickname'] . '的('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['team']]
                ];
                \think\facade\Hook::listen("Message",$content);

                $content = [
                    'type'      =>  'point',
                    'content'   =>  ['您的团队会员('.$this->getStarPhone($userInfo['phone']).')成功领取产品', '+'.$configs['team'], now()],
                    'userId'    =>  $teamInfo['userId']
                ];
                \think\facade\Hook::listen("minipush", $content);
            }

            // 找到下一层团队长
            $level++;
            $teamInfo = $this->getTeamInfo($userId, $level);
        }
    }

    /**
     * 获取用户id
     * @param int $userId 用户id
     * @return array $userId 用户信息
     */
    protected function getUserInfo($userId)
    {
        $userInfo = Db::table('shop_members')->where('userId', $userId)->find();
        throw_if($userInfo, ['msg' => '用户不存在!']);
        return $userInfo;
    }

    /**
     * 检查自己和上级
     */
    protected function checkTeamGrade($configs, $userInfo, $toAgentId, $currentAgentLevel)
    {
        $configLevel = $this->getAgentLevel($configs['agentId']);
        $childNum = $this->getChildNum($userInfo['userId']);
        if($childNum >= $configs['membernumber'] && $currentAgentLevel >= $configLevel && !$userInfo['teamId']){
            // 满足条件升级为团长
            Db::table('shop_members')->where('userId', $userInfo['userId'])->update(['teamId' => 1]);

            // 生成团长升级记录
            $teamRecord = [
                'userId'        =>  $userInfo['userId'],
                'teamId'        =>  1,
                'oldteamId'     =>  0
            ];
            Db::table('shop_team_up')->insert($teamRecord);

            // agent	恭喜您！您当前等级为${agent} 升级为 s级团队长
            $content = [
                'userId'    =>  $userInfo['userId'],
                'name'      =>  'agent',
                'content'   =>  [$userInfo['nickname'], 's级团队长']
            ];
            \think\facade\Hook::listen("Message",$content);
        }
    }

    /**
     * 获取直属下级数量
     */
    protected function getChildNum($userId)
    {
        $count = Db::table('shop_members')->where('referUserId', $userId)->count();
        return $count;
    }

    /**
     * 获取推荐奖励金额 没有角色不给推荐奖金
     */
    public function getReferAmount($userInfo, $referInfo, $level, $toAgentId)
    {
        if($referInfo['agentId'] == 0){
            return 0;
        }
        $agentReward = Db::table('shop_members_agent_reward')
        ->where([
            ['agentId', '=', $toAgentId],
            ['referAgentId', '=', $referInfo['agentId']]
        ])->find();
        if($level == 1){
            return $agentReward['oneMoney'];
        }else if($level == 2){
            return $agentReward['twoMoney'];
        }
        throw_if(false, ['msg' => '获取推荐奖励金额level错误!']);
    }

    /**
     * 获取团队奖励金额
     */
    public function getTeamAmount($level, $currentAgentId)
    {
        $teamReward = Db::table('shop_members_team_reward')
        ->where('agentId', $currentAgentId)->find();
        if($level == 1){
            return $teamReward['oneMoney'];
        }else if($level == 2){
            return $teamReward['twoMoney'];
        }
        throw_if(false, ['msg' => '获取团队奖励金额level错误!']);
    }

    /**
     * 格式化手机号
     */
    public function getStarPhone($phone, $pre = '*', $hiddenNum = 4)
    {
        return substr_replace($phone, str_repeat($pre, $hiddenNum), 3, $hiddenNum);
    }

    /**
     * 根据用户id 获取用户推挤人id
     */
    protected function getReferUserId($userId)
    {
        $userId = Db::table('shop_members')->where('userId', $userId)->value('referUserId');
        return $userId;
    }

    /**
     * 获取用户等级1
     */
    protected function getReferInfo($userInfo, $level)
    {
        $referUserId = $userInfo['referUserId'];
        if($referUserId == 0){
            return null;
        }
        $referUserInfo = Db::table('shop_members')->where('userId', $referUserId)->find();

        if($level == 1){
            return $referUserInfo;
        }

        if($referUserInfo && $referUserInfo['referUserId']){
            $referUserInfo = Db::table('shop_members')->where('userId', $referUserInfo['referUserId'])->find();
            return $referUserInfo;
        }
        return null;
    }

    /**
     * 根据用户id 获取用户一级/二期团队长的 userId
     */
    public function getTeamInfo($userId, $level)
    {
        // 越过直接和间接推荐
        $referUserId = $this->getReferUserId($userId);
        if($referUserId == 0){
            return 0;
        }
        $referUserId = $this->getReferUserId($referUserId);
        if($referUserId == 0){
            return 0;
        }
        $referUserId = $this->getReferUserId($referUserId);
        if($referUserId == 0){
            return 0;
        }
        // 无限查找直到找到有团长资格的用户
        $count = 1;
        while($referUserId){
            $userInfo = Db::table('shop_members')->where('userId', $referUserId)->find();
            if($userInfo['teamId']){
                if($level == $count){   // 当前层级和要求层级一致
                    return $userInfo;
                }
                $count++;
            }
            if(!$userInfo){
                $referUserId = 0;
            }else{
                $referUserId = $userInfo['referUserId'];
            }
        }
        return 0; // 没有任何匹配
    }

    /**
     * 当前用户等级升级
     * @param int $userId 用户ID
     * @param int $toAgentId 用户应该升级到的等级
     * @return bool 升级结果
     */
    protected function upAgentId($userId, $toAgentId, $currentAgentId, $orderInfo)
    {
        $userInfo = $this->getUserInfo($userId);
        $time = time();
        if($userInfo['vip_expire_time'] > $time){
            $vip_expire_time = $userInfo['vip_expire_time'] + 30 * 24 * 60 * 60; // 会员失效时间
        }else{
            $vip_expire_time = $time + 30 * 24 * 60 * 60;      // 会员失效时间
        }

        $res = Db::table('shop_members')->where('userId', $userId)->update(['agentId' => $toAgentId, 'vip_expire_time' => $vip_expire_time]);

        // 订单状态修改成已完成
        Db::table('shop_orders')->where('id', $orderInfo['id'])->update(['status' => 4]);

        $agentName = Db::table('shop_members_agent')->where('id', $toAgentId)->value('title');

        // 生成升级记录 =》 续费记录
        $agentRecord = [
            'userId'        =>  $userId,
            'agentId'       =>  $toAgentId,
            'oldagentId'    =>  $currentAgentId
        ];
        Db::table('shop_members_up')->insert($agentRecord);

        // agent	恭喜您！您当前等级为${agent} 升级为 s级团队长  => 修改成续费成功的通知
        // $nickname = Db::table('shop_members')->where('userId', $userId)->value('nickname');
        // $content = [
        //     'userId'    =>  $userId,
        //     'name'      =>  'agent',
        //     'content'   =>  [$nickname, $agentName]
        // ];
        // \think\facade\Hook::listen("Message",$content);
        return (bool)$res;
    }

    /**
     * 查询订单从表 获取应该升级到的等级
     * @param string $orderId 订单号
     * @return int 用户因为购买这个订单 应该升级到的等级
     */
    protected function getToAgentId($orderId)
    {
        return 1; // 购买只能升级为vip会员 对应原系统的等级1
        $sum = Db::table('shop_orders_items_goods')->where('orderId', $orderId)->count();
        $agents = Db::table('shop_members_agent')->select();
        foreach($agents as $v){
            if($v['buyNumber'] == $sum){
                return $v['id'];
            }
        }
        throw_if(false, ['msg' => '订单从表数据错误!']);
    }

    /**
     * 通过agentId获取agentlevel
     * @param int $agentId 用户角色id
     * @return int 角色对应的等级
     */
    protected function getAgentLevel($agentId)
    {
        if($agentId == 0){
            $level = 0;// 普通用户等级 agentId 和level都是0
            return 0;
        }
        $agent = Db::table('shop_members_agent')->where('id', $agentId)->find();
        throw_if($agent, ['msg' => '用户等级不存在!']);
        $level = $agent['level'];
        return $level;
    }
}