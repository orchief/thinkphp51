<?php

// +----------------------------------------------------------------------
// | Description: 获取openid
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2018-01-31 13:40:28
// +----------------------------------------------------------------------

namespace app\base\controller;

use EasyWeChat\Factory;
use Utility\JWT;
use app\shop\model\Members;
use Utility\Controller;
/**
 * @route('base/OpenidCallback')
 */
class OpenidCallback extends Controller
{
    /**
     * 自动生成swagger-ui所需的json文件.
     */
    public function index()
    {
        // \think\Log::debug(['openid_param' => $this->param]);
        $configs = setting('WECHAT');
        $config = [
            'app_id' => $configs['app_id'],
            'secret' => $configs['secret'],

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => '/base/oauth_callback',
            ],
        ];
        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();die();
        
        session_start();
        if (!JWT::get('sessionId')) {
            $sessionId = session_id();
            JWT::set('sessionId', $sessionId);
        }
        
        // 检查用户是否已经存在 如果已经存在 并且绑定了手机号 则返回 authorization 和 bindPhone=1
        $userInfo = Members::where(['openId' => $user->getId()])->find();
        if ($userInfo) {
            JWT::set('userId', $userInfo['userId']);
            if ($userInfo['phone']) {
                $bindPhone = 1;
            } else {
                // 保存微信头像到本地
                $referUserId = Members::where(['phone' => $this->param['referPhone']])->value('userId');
                if ($referUserId) {
                    JWT::set('referUserId', $referUserId);
                } else {
                    JWT::set('referUserId', 0);
                }

                JWT::set('openid', $user->getId());
                JWT::set('nickname', $user->getNickname());
                JWT::set('headImg', $user->getAvatar());
                JWT::set('gender', $user->jsonSerialize()['original']['sex']);
                $bindPhone = 0;
            }
            $authorization = JWT::$encoded;
        } else {
            //  保存微信头像到本地
            $referUserId = Members::where(['phone' => $this->param['referPhone']])->value('userId');

            // \think\Log::debug(['referUserId' => $referUserId]);

            if ($referUserId) {
                JWT::set('referUserId', $referUserId);
            } else {
                JWT::set('referUserId', 0);
            }

            JWT::set('openid', $user->getId());
            JWT::set('nickname', $user->getNickname());
            JWT::set('headImg', $user->getAvatar());
            JWT::set('gender', $user->jsonSerialize()['original']['sex']);

            $authorization = JWT::$encoded;
            $bindPhone = 0;
        }

        $callback = $this->param['backUrl'];  // 授权回调前端地址

        header('Location: '.$callback.'?authorization='.$authorization.'&bindPhone='.$bindPhone);
    }
}
