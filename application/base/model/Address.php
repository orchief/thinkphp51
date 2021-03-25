<?php
// +----------------------------------------------------------------------
// | Description: 公开地址服务
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2019-02-22 13:49:55
// +----------------------------------------------------------------------

namespace app\base\model;

use Utility\Model;
use think\facade\Cache;

class Address extends Model
{
    protected $name = 'base_address';
    protected $param;
    /**
     * 排序字段
     *
     * @var string
     */
    protected $sorts = ['create_time'];
    /**
     * 允许主表 模糊查询的字段
     *
     * @var array
     */
    protected $likeCons = ["areaName","cityCode","center"];
    /**
     * 允许主表 精确查询的字段
     *
     * @var array
     */
    protected  $eqCons  = ["level", "areaId", "areaCode", "parentId"];
    /**
     * 允许范围查询的字段 支持单段查询
     *
     * @var array
     */
    protected $ranges = [];
    /**
     * 联合查询
     *
     * @var array
     */
    protected $leftJoin = [
        // [
        //     'tablename',        // left join的表名
        //     'tablename_id',     // left join 的表的对应键
        //     'main_id'           // 主表对应的键
        // ]
    ];
    /**
     * 每页条数
     *
     * @var integer
     */
    protected $limit = 20;
    /**
     * 当前页码
     *
     * @var integer
     */
    protected $offset = 0;
    /**
     * select 后边的字符串
     *
     * @var array
     */
    protected $returnFields = '';
    /**
     * 只读字段
     *
     * @var array
     */
    protected $readonly = [];
    /**
     * 是否开启软删除
     *
     * @var boolean
     */
    protected $softDelete = false;
    /**
     * 隐藏字段
     * @var boolean
     */
    protected $hidden = ['userId'];
    /**
     * 显示字段
     * @var boolean
     */
    protected $visible = [];
    /**
     * 默认软删除字段
     *
     * @var boolean
     */
    protected $delField = 'isDelete';
    /**
     * json字段 设置为json的字段自动转换
     *
     * @var boolean
     */
    protected $jsonFields = [];

    public function getDataList($param){
        // 缓存某些条件的结果
        $param['limit'] = -1;
        if(isset($param['level']) && $param['level'] == '1,2' and $param['limit'] == -1){
            $address = Cache::get('address_level_1_2');
            if($address){
                return $address;
            }else{
                $list = $this->parseUrl($param)->select()->toArray();
                $total = $this->getTotals($param)->count();
                $tree = new \com\Tree();
                $list = $tree->list_to_tree($list, 'areaId', 'parentId', 'child', -1);
                $resData['list'] = $list;
                $resData['dataCount'] = $total;
                Cache::set('address_level_1_2', $resData);
                return $resData;
            }
        }else{
            $list = $this->parseUrl($param)->select()->toArray();
            $total = $this->getTotals($param)->count();
            if(isset($param['tree']) && $param['tree'] == 1){
                $tree = new \com\Tree();
                $list = $tree->list_to_tree($list, 'areaId', 'parentId', 'child', -1);
            }            
            $resData['list'] = $list;
            $resData['dataCount'] = $total;
            return $resData;
        }
    }
}
