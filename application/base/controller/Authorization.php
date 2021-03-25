<?php

namespace app\base\controller;

use Utility\JWT;

/**
 * @route('base/authorization')
 */
class Authorization
{
    /**
     * Undocumented function.
     */
    public function jwt()
    {
        validates(
            [
                'appid' => 'require',
                'secret' => 'require',
            ]
        );
        // 发放唯一的jwt token字符串
        session_start();
        if (!JWT::get('sessionId')) {
            JWT::set('sessionId', session_id());
        }
        result(['msg' => '获取成功！']);
    }
}
