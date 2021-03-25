<?php
// +----------------------------------------------------------------------
// | Description: 权限规则
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 17:59:39
// +----------------------------------------------------------------------

namespace app\admin\model;

use Utility\Model;

class Rule extends Model
{
    protected $name = 'admin_rule';
    protected $param;
    protected $whereIn = ['id'];
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
    protected $likeCons = ["level","name","pid","status","title",];
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
    protected $jsonFields = ["api_list","extra",];


    /**
     * 多条件获取数据列表.
     */
    public function getDataList($param)
    {
        $res = $this->parseUrl($param)->select();
        $total = $this->getTotals($param)->count();
        $res = $this->filter($res);

        // $res
        $tree = new \com\Tree();
        $res = $res->toArray();
        $menusList = $tree->list_to_tree($res, 'id', 'pid', 'child', 0, true, array('pid'));

        $resData['list'] = $menusList;
        $resData['dataCount'] = $total;
        return $resData;
    }
}
