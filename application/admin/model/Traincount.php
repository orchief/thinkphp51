<?php
// +----------------------------------------------------------------------
// | Description: 单位培训情况统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 13:36:28
// +----------------------------------------------------------------------

namespace app\admin\model;

use Utility\Model;

class Traincount extends Model
{
    protected $name = 'traincount';
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
    protected $likeCons = ["unit","train_id","trainpeople","persontime","days","hours","fee",];
    /**
     * 允许主表 精确查询的字段
     *
     * @var array
     */
    protected  $eqCons  = [];
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
        [
            'train',        // left join的表名
            'id',     // left join 的表的对应键
            'train_id'           // 主表对应的键
        ]
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
    protected $returnFields = 'train.train_name,train.start_time,train.end_time,traincount.*';
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

    // /**
    //  * 多条件获取数据列表.
    //  */
    // public function getDataList($param)
    // {
    //     // TODO 根据当天条件使用sql进行统计
    //     $sql = "select ";
    //     $resData['list'] = $res;
    //     $resData['dataCount'] = $total;
    //     return $resData;
    // }
}
