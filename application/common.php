<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use Utility\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

/**
 * 获取视频点赞数量
 *
 * @return void
 */
function getStar($url, $type)
{
    $id = trim($url);

    try {
        if (strpos($id, 'https://v.douyin.com/') !== false) {
            $request = new Request('HEAD', $id);
        } else {
            $request = new Request('HEAD', 'https://v.douyin.com/' . $id);
        }

        $historyContainer = [];

        $handlerStack = HandlerStack::create();
        $handlerStack->push(Middleware::history($historyContainer));

        $client = new Client(['handler' => $handlerStack]);

        $client->send($request);

        foreach ($historyContainer as $httpTransaction) {
            $request = $httpTransaction['request'];
            $url = (string) $request->getUri();
        }
    } catch (\Exception $e) {
        abort('请检查任务地址!');
    }

    if ($type == 1) {
        // 正则获取id
        $pattern = '/(video\/)(.*)(?)(\/)/';     //errorType Array为开头 结尾字符串
        preg_match_all($pattern, $url, $matches);

        continue_if(isset($matches[2][0]), '视频不存在!');

        $mid = $matches[2][0];

        $url2 = "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=" . $mid;

        $res_data = file_get_contents($url2);

        $video_info = json_decode($res_data);

        $res = object_array($video_info);

        return isset($res['item_list'][0]['statistics']['digg_count']) ? $res['item_list'][0]['statistics']['digg_count'] : 0;
    } else if ($type == 2) {
        // 正则获取id
        $pattern = '/(sec_uid=)(.*)(\=)/';     //errorType Array为开头 结尾字符串
        preg_match_all($pattern, $url, $matches);

        continue_if(isset($matches[2][0]), '用户不存在!');

        $uid = $matches[2][0];

        $sec_uid = explode('&', $uid)[0];

        $url2 = "https://www.iesdouyin.com/web/api/v2/user/info/?sec_uid=" . $sec_uid;

        $res = file_get_contents($url2);

        $user = json_decode($res);

        return $user->user_info->favoriting_count;
    }
}

/**
 * 终止程序返回json数据.
 *
 * @param array $jsonBody 返回的json
 * @param int   $httpCode http code
 */
function json($data = [], $code = 200, $httpcode = 200, $header = [], $options = [])
{
    $Body = [
        'code' => $code,
        'data' => $data,
    ];

    $response = think\Response::create($Body, 'json', $httpcode, $header, $options);
    throw new think\exception\HttpResponseException($response);
}

/**
 * 获取用户信息
 */
function user_info($id, $fields = null)
{
    if (!$fields) {
        return continue_if(\think\Db::table('user')->where('user_id', $id)->find(), '用户不存在!');
    }
    $fields = str2Arr($fields);
    if (is_array($fields)) {
        if (count($fields) > 1) {
            return continue_if(\think\Db::table('user')->field($fields)->where('user_id', $id)->find(), '用户不存在!');
        } elseif (count($fields) == 1) {
            return continue_if(\think\Db::table('user')->field($fields)->where('user_id', $id)->value($fields[0]), '用户不存在!');
        }
    } else {
        return continue_if(\think\Db::table('user')->field($fields)->where('user_id', $id)->value($fields), '用户不存在!');
    }
    // 根据用户id获取用户信息
}

/**
 * 检查是否有请求权限
 *
 * @return void
 */
function current_api()
{
    $method = Request()->method();
    $rulename = Request()->path();
    $rulename = str_replace('\\', '/', $rulename);

    $name_arr = explode('/', $rulename);

    if (isset($name_arr[0]) && $name_arr[1]) {
        $api = $method . ' ' . $name_arr[0] . '/' . $name_arr[1];
    } else {
        $api = $method;
    }

    $api = strtolower($api);
    // 对比
    return $api;
}

/**
 * 邀请码
 *
 * @return void
 */
function get_invite_code()
{
    return time();
}

/**
 * 获取订单号
 *
 * @return void
 */
function get_order_no()
{
    return uniqid();
}


/**
 * 将数据库主表和从表联查的数据格式化为树形.
 *
 * @param array  $data                需要进行格式化的数据
 * @param string $primaryKey          唯一键(不一定是主键)
 * @param string $childrenField       子字段名称 (自定义的)
 * @param mixed  $childrenValueFields 子字段数据字段 本字段为混合类型 为字符串的时候 子字段为索引数组 为数组是子字段为关联数组
 * @param array  $normalFields        核心字段(唯一键已经有了)
 */
function tree($data, $primaryKey, $childrenField, $childrenValueFields, $normalFields)
{
    $arr = [];
    foreach ($data as $k => $v) {
        foreach ($normalFields as $key => $value) {
            $arr[$data[$k][$primaryKey]][$value] = $v[$value];
        }
        if (is_array($childrenValueFields)) {
            $temp = [];
            foreach ($childrenValueFields as $key => $value) {
                if (null === $v[$value] && is_array($v) && array_key_exists($value, $v)) {
                    continue;
                }
                if (null !== $v[$value]) {
                    array_key_exists($value, $v) && $temp[$value] = $v[$value];
                }
            }
            $arr[$data[$k][$primaryKey]][$childrenField][] = $temp;
            $temp == [] && $arr[$data[$k][$primaryKey]][$childrenField] = [];
        }
        if (is_string($childrenValueFields)) {
            $arr[$data[$k][$primaryKey]][$childrenField][] = $v[$childrenValueFields];
        }
    }

    return array_values($arr);
}


/**
 * 将数据库主表和从表联查的数据格式化为树形.
 *
 * @param array  $data                需要进行格式化的数据
 * @param string $primaryKey          唯一键(不一定是主键)
 * @param string $childrenField       子字段名称 (自定义的)
 * @param mixed  $childrenValueFields 子字段数据字段 本字段为混合类型 为字符串的时候 子字段为索引数组 为数组是子字段为关联数组
 * @param array  $normalFields        核心字段(唯一键已经有了)
 */
function tree_e($data, $primaryKey, $childrenField, $childrenValueFields, $normalFields)
{
    $arr = [];
    foreach ($data as $k => $v) {
        foreach ($normalFields as $key => $value) {
            $arr[$data[$k][$primaryKey]][$value] = $v[$value];
        }
        if (is_array($childrenValueFields)) {
            $temp = [];
            foreach ($childrenValueFields as $key => $value) {
                if (null === $v[$value] && is_array($v) && array_key_exists($value, $v)) {
                    continue;
                }
                if (null !== $v[$value]) {
                    array_key_exists($value, $v) && $temp[$value] = $v[$value];
                }
            }
            $arr[$data[$k][$primaryKey]][$childrenField][] = $temp;
            $temp == [] && $arr[$data[$k][$primaryKey]][$childrenField] = [];
        }
        if (is_string($childrenValueFields)) {
            $arr[$data[$k][$primaryKey]][$childrenField][] = $v[$childrenValueFields];
        }
    }

    return $arr;
}


/**
 * 获取 / 设置配置信息
 *
 * @param string $name
 * @param string $value
 * @return mixed
 */
function setting($name = null, $value = null, $name_str = 'name', $value_str = 'value')
{
    $name_arr = explode('.', $name);
    $res = null;
    $level = count($name_arr);
    switch ($level) {
        case 1:
            $value = \app\admin\model\Setting::where([$name_str => $name])->value($value_str);
            $arrV = json_decode($value, true);
            foreach ($arrV as $k => $v) {
                $res[$v[$name_str]] = $v[$value_str];
            }
            break;
        case 2:
            $value = \app\admin\model\Setting::where([$name_str => $name_arr[0]])->value($value_str);
            $arrV = json_decode($value, true);
            foreach ($arrV as $k => $v) {
                if ($v[$name_str] == $name_arr[1]) {
                    $res = $v[$value_str];
                }
            }
    }

    return $res;
}

/**
 * 终止程序返回json数据.
 *
 * @param array $jsonBody 返回的json
 * @param int   $httpCode http code
 */
function abort($data = [], $code = 400, $httpcode = 200, $header = [], $options = [])
{
    if (!is_array($data)) {
        $data = ['msg' => $data];
    }
    $Body = [
        'code' => $code,
        'data' => $data,
    ];

    $response = think\Response::create($Body, 'json', $httpcode, $header, $options);
    throw new think\exception\HttpResponseException($response);
}

/**
 * Undocumented function.
 */
function continue_if($bool, $data, $code = 400, $httpcode = 200, $header = [], $options = [])
{
    if (!$bool) {
        if (!is_array($data)) {
            $data = ['msg' => $data];
        }
        json($data, $code, $httpcode, $header, $options);
    }

    return $bool;
}

function sha256($data, $rawOutput = false)
{
    if (!is_scalar($data)) {
        return false;
    }
    $data = (string) $data;
    $rawOutput = !!$rawOutput;
    return hash('sha256', $data, $rawOutput);
}

/**
 * 抛出异常或者程序继续执行.
 *
 * @param boolen $boolen 需要检验的结果 为true 则程序继续运行 否则抛出异常
 */
function throw_if($boolen, $msg)
{
    if (!$boolen) {
        throw new \Exception(json_encode($msg, JSON_UNESCAPED_UNICODE));
    }

    return $boolen;
}

function result($res, $data = [], $code = 400, $httpcode = 200, $header = [], $options = [])
{
    if ($res || is_array($res)) {
        json($res, 200, $httpcode, $header, $options);
    } else {
        if (!is_array($data)) {
            $data = ['msg' => $data];
        }
        json($data, $code, $httpcode, $header, $options);
    }
}

/**
 * 验证数据 (数据 默认为请求参数).
 *
 * @param array $rules Rules of inspection
 * @param array $field field 注释
 * @param array $data  data
 */
function validates($rules, $field = [], $data = null, $msg = [])
{
    if (null == $data) {
        $data = \think\facade\Request::instance()->param();
    }

    foreach ($rules as $k => $v) {
        if ([] != $field) {
            $field_keys = array_keys($field);
            if (!in_array($k, $field_keys)) {
                $field[$k] = $k;
            }
        }
    }

    $validator = new \Utility\Validate($rules, $msg, $field);
    continue_if($validator->check($data), $validator->getError());
    return $data;
}

/**
 * 获取当前用户的userId.
 */
function userId($userId = 'userId', $_userId = '_userId')
{
    // return 3508;
    if (isset($GLOBALS[$_userId])) {
        return $GLOBALS[$_userId];
    }

    continue_if(isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'], '请登录！', 403);

    $res_userId = continue_if(\Utility\JWT::get($userId), '没有访问权限！', 403);

    // 存储UserId
    $GLOBALS[$_userId] = \Utility\JWT::get($userId);

    // 验证当前用户是否被禁用
    $userInfo = \think\Db::table('shop_members')->where('userId', $GLOBALS[$_userId])->find();

    continue_if($userInfo, ['msg' => '账户被删除!'], 401);

    continue_if($userInfo['status'], ['msg' => '账户被禁用!'], 401);

    return $res_userId;
}

/**
 * 当前年份
 *
 * @return void
 */
function year()
{
    return date('Y');
}

/**
 * 数据库事务函数
 * @param function $func 匿名函数
 * @return void
 */
function trans($func)
{
    \think\Db::startTrans();
    try {
        call_user_func($func);
        \think\Db::commit();
    } catch (\Exception $th) {
        \think\Db::rollback();
        if ($th instanceof \think\exception\HttpResponseException) {
            $res = json_decode($th->getResponse()->getContent(), true);
            result($res['data'], $res['code']);
        } else {
            abort($th->getMessage(), 500);
        }
    }
    return true;
}

/**
 * 获取当前登陆管理员的用户名
 *
 * @return void
 */
function get_adminname()
{
    return \think\Db::table('admin_user')->where('id', admin_id())->value('username');
}

/**
 * 尝试获取当前登陆账户id
 *
 * @return void
 */
function try_admin_id()
{
    return JWT::get('admin_id');
}

function decode_invite($invite_code)
{
    // TODO 根据 $invite_code 获取上级id
    $invite = \think\Db::table('invite')->where('invite_code', $invite_code)->find();
    if ($invite) {
        if ($invite['role'] == 1) {
            $refer_user_id = $invite['user_id'];
            $refer_area_id = \think\Db::table('user')->where('user_id', $refer_user_id)->value('refer_area_id');
        } else {
            $refer_user_id = 0;
            $refer_area_id = $invite['user_id'];
        }
    } else {
        $refer_user_id = 0;
        $refer_area_id = 0;
    }

    return [
        'refer_user_id' =>  $refer_user_id,
        'refer_area_id' =>  $refer_area_id
    ];
}

/**
 * 获取收益金额
 */
function get_user_unit_price($task_id, $type, $user_id)
{
    return 0.02;
}

/**
 * 获取用户id
 *
 * @return void
 */
function user_id()
{
    return continue_if(JWT::get('user_id'), '未登陆', 401);
}


/**
 * 下单端用户id
 *
 * @return void
 */
function mobile_id()
{
    // 检查
    $user_id = session('user_id');
    return continue_if($user_id, '未登陆', 401);
}


function config_path()
{
    $path = \think\facade\Env::get('root_path') . 'config/';
    return $path;
}

/**
 * 临时文件目录
 *
 * @return void
 */
function temp_path()
{
    $path = \think\facade\Env::get('root_path') . 'runtime/';
    return $path;
}

/**
 * 文件锁防止高并发 + 数据库事务
 */
function single_proc($func, $file = '')
{
    $file = temp_path() . "lock/" . $file . ".lock";
    if (!is_dir(temp_path() . "lock")) {
        mkdir(temp_path() . "lock");
    }
    $fp = fopen($file, "a+");
    if (flock($fp, LOCK_EX)) {
        \think\Db::startTrans();
        try {
            call_user_func($func);
            \think\Db::commit();
            flock($fp, LOCK_UN);
            fclose($fp);
        } catch (\Exception $th) {
            \think\Db::rollback();
            flock($fp, LOCK_UN);
            fclose($fp);
            if ($th instanceof \think\exception\HttpResponseException) {
                $res = json_decode($th->getResponse()->getContent(), true);
                result($res['data'], $res['code']);
            } else {
                abort($th->getMessage(), 500);
            }
        }
    }

    return true;
}

function object_array($array)
{
    if (is_object($array)) {
        $array = (array) $array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}


/**
 * 尝试获取当前登陆账户id
 *
 * @return void
 */
function admin_id()
{
    // return 1; // 为了测试
    return continue_if(JWT::get('admin_id'), '未登陆', 401);
}

/**
 * 获取当前后台操作用户名称
 *
 * @return void
 */
function get_current_admin_name()
{
    /*获取头部信息*/
    $authKey = \Utility\JWT::get('authKey');
    $sessionId = \Utility\JWT::get('sessionId');

    if (!$authKey) {
        return 'admin';
    }
    $cache = cache('Auth_' . $authKey);

    // 检查账号有效性
    $userInfo = $cache['userInfo'];
    $map['id'] = $userInfo['id'];
    $map['status'] = 1;
    $totalInfo = \think\Db::name('admin_user')->where($map)->find();
    continue_if($totalInfo, '账号已被删除或禁用', 102);
    return $totalInfo['username'];
}


/**
 * 获取当前后台操作用户名称
 *
 * @return void
 */
function get_current_admin_unit()
{
    /*获取头部信息*/
    $authKey = \Utility\JWT::get('authKey');
    $sessionId = \Utility\JWT::get('sessionId');

    if (!$authKey) {
        return '';
    }
    $cache = cache('Auth_' . $authKey);

    // 检查账号有效性
    $userInfo = $cache['userInfo'];
    $map['id'] = $userInfo['id'];
    $map['status'] = 1;
    $totalInfo = \think\Db::name('admin_user')->where($map)->find();
    continue_if($totalInfo, '账号已被删除或禁用', 102);
    $res = \think\Db::table('section')->where(['id' => $totalInfo['section_id']])->find();
    return $res['section_name'];
}


/**
 * 查找某个二维数组中是否存在某个数组包含 $value
 */
function find_in_array($array, $array_array)
{
    foreach ($array_array as $key => $value) {
        if (!array_diff($array, $value)) {
            return true;
        }
    }

    return false;
}

/**
 * 将数字格式化为字符串 null 会变成 "0"
 */
function strfy($num)
{
    return (string) (float) $num;
}


/**
 * 获取短信验证码
 */
function getSmsCode($length = 4)
{
    $min = pow(10, ($length - 1));
    $max = pow(10, $length) - 1;
    return rand($min, $max);
}

/**
 * 层级多维数组根据字段进行排序.
 *
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function arraySequence($array, $field, $sort = 'SORT_ASC', $child = 'child')
{
    if (is_array($array)) {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {

            if (isset($row[$child])) {
                $array[$uniqid][$child] = arraySequence($row[$child], $field, $sort);
            }
            foreach ($row as $key => $value) {


                $arrSort[$key][$uniqid] = $value;
            }
        }
        if (isset($arrSort[$field]) && is_array($arrSort[$field])) {
            array_multisort($arrSort[$field], constant($sort), $array);
        }
    }
    return $array;
}

/**
 * 树形结构还原成list
 */
function tree_to_list($tree_data, $child = 'child')
{
    $arr = [];
    foreach ($tree_data as $k => $v) {

        if (isset($v['child'])) {
            $arr = array_merge(tree_to_list($v['child']), $arr);
        }

        $arr[] = $v;
    }
    return $arr;
}


/**
 * 格式化金钱
 */
function format_money($money)
{
    return number_format($money, 2);
}


/**
 * 查询出套包商品
 */
function taobao_product_ids()
{
    // 查询出套包商品
    $packages = \think\Db::table('shop_refer_award_settings')->field('packageId')->select();

    $cateIds = array_column($packages, 'packageId');

    if (count($cateIds)) {
        $product_list = \think\Db::table('shop_goods_products')->field('id')->where('categoryId', 'in', $cateIds)->select();

        $ids = array_column($product_list, 'id');
    } else {
        $ids = [];
    }

    return $ids;
}


/**
 * 不打折的商品
 */
function product_ids()
{
    return [];
    // 查询出套包商品
    $packages = \think\Db::table('shop_refer_award_settings')->field('packageId')->select();

    $cateIds = array_column($packages, 'packageId');

    if (count($cateIds)) {
        $product_list = \think\Db::table('shop_goods_products')->field('id')->where('categoryId', 'in', $cateIds)->select();

        $ids = array_column($product_list, 'id');
    } else {
        $ids = [];
    }

    // 查询出促销商品
    $products = \think\Db::table('shop_goods_products')->where('productType', 2)->field('id')->select();

    $ids2 = array_column($products, 'id');

    if (!$ids2) {
        $ids2 = [];
    }
    if (!$ids) {
        $ids = [];
    }
    $res = array_merge($ids, $ids2);

    return $res;
}


function kq_ck_null($kq_va, $kq_na)
{
    if ($kq_va == "") {
        $kq_va = "";
    } else {
        return $kq_va = $kq_na . '=' . $kq_va . '&';
    }
}
/**
 * @param $data 需要处理的数据
 * @param int $precision 保留几位小数
 * @return array|string
 */
function fix_number_precision($data, $precision = 2)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = fix_number_precision($value, $precision);
        }
        return $data;
    }

    if (is_numeric($data)) {
        $precision = is_float($data) ? $precision : 0;
        return number_format($data, $precision, '.', '');
    }

    return $data;
}
/**
 * 计算两个日期相差月数
 */
function getMonthNum($date1, $date2, $tags = '-')
{
    $date1 = explode($tags, $date1);
    $date2 = explode($tags, $date2);
    return abs($date1[0] - $date2[0]) * 12 - $date2[1] + abs($date1[1]);
}
/**
 * 检查设置json是否是正确的json
 */
function check_settings($value)
{
    $arr = str2Arr($value);
    $rules = [];
    $fields = [];
    $param = [];
    foreach ($arr as $k => $v) {
        if (isset($v['rule'])) {
            $param[$v['name']] = $v['value'];
            $rules[$v['name']] = $v['rule'];
            $fields[$v['name']] = $v['title'];
        }
    }

    if (count($param)) {
        validates($rules, $fields, $param);
    }
}
