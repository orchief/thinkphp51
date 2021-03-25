<?php

// +----------------------------------------------------------------------
// | Description: 公开配置参数
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2019-01-09 13:02:23
// +----------------------------------------------------------------------

namespace app\base\model;

use Utility\Model;

class Config extends Model
{
    protected $name = 'shop_config';
    protected $param;
    /**
     * 排序字段.
     *
     * @var string
     */
    protected $orderStr;
    /**
     * 允许主表 模糊查询的字段.
     *
     * @var array
     */
    protected $eqCons = [];
    /**
     * 允许主表 精确查询的字段.
     *
     * @var array
     */
    protected $likeCons = ['name', 'value', 'remark'];
    /**
     * 允许范围查询的字段 支持单段查询.
     *
     * @var array
     */
    protected $ranges = [];
    /**
     * 联合查询.
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
     * 每页条数.
     *
     * @var int
     */
    protected $limit = 20;
    /**
     * 当前页码
     *
     * @var int
     */
    protected $offset = 0;
    /**
     * select 后边的字符串.
     *
     * @var array
     */
    protected $returnFields = '';
    /**
     * 只读字段.
     *
     * @var array
     */
    protected $readonly = [];
    /**
     * 是否开启软删除.
     *
     * @var bool
     */
    protected $softDelete = false;
    /**
     * 隐藏字段.
     *
     * @var bool
     */
    protected $hidden = ['userId'];
    /**
     * 显示字段.
     *
     * @var bool
     */
    protected $visible = [];
    /**
     * 默认软删除字段.
     *
     * @var bool
     */
    protected $delField = 'isDelete';

    /**
     * json 转换字段.
     *
     * @var bool
     */
    protected $jsonFields;
}
