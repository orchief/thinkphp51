<?php
// +----------------------------------------------------------------------
// | Description: 个人总统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 21:50:35
// +----------------------------------------------------------------------

namespace app\admin\model;

use Utility\Model;

class Studenttotalcount extends Model
{
    protected $name = 'studenttotalcount';
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
    protected $likeCons = ["student_id","username","category","type","position","rank","full_time_period","full_time_days","half_time_period","half_time_days","dangxing","zhuanye","xitong","jishu","tigao","qixiang","qita","totalnum","period","days","charge",];
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
}
