<?php
// +----------------------------------------------------------------------
// | Description: 培训报名
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-02 19:00:52
// +----------------------------------------------------------------------

namespace app\admin\model;

use Utility\Model;

class Registration extends Model
{
    protected $name = 'registration';
    protected $param;
    /**
     * 排序字段
     *
     * @var string
     */
    protected $sorts = ['create_time', 'regist_time'];
    /**
     * 允许主表 模糊查询的字段
     *
     * @var array
     */
    protected $likeCons = ["student_id","train_id",];
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
    protected $ranges = ["create_time","regist_time",];
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
        ],
        [
            'student',
            'student_id',
            'student_id'
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
