<?php
// +----------------------------------------------------------------------
// | Description: 角色管理
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2020-06-04 17:35:14
// +----------------------------------------------------------------------

namespace app\admin\model;

use Utility\Model;
use think\Db;

class Group extends Model
{
    protected $name = 'admin_group';
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
    protected $likeCons = ["title", 'remark'];
    /**
     * 允许主表 精确查询的字段
     *
     * @var array
     */
    protected  $eqCons  = ["status",];
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
    protected $jsonFields = ["rules",];

    /**
     * 可批量修改的字段
     */
    protected $batchField = ['status'];

    /**
     * 多条件获取数据列表.
     */
    public function getDataList($param)
    {
        $res = $this->parseUrl($param)->select()->toArray();
        $total = $this->getTotals($param)->count();
        $res = $this->filter($res);

        // rules字段
        foreach($res as $k => $v){
            $arr = Db::table('admin_rule')->where('id', 'in', $v['rules'])->select();
            foreach($arr as $kk => $vv){
                $arr[$kk]['extra'] = \str2Arr($vv['extra']);
            }
            $res[$k]['rules'] = $arr;
        }

        $resData['list'] = $res;
        $resData['dataCount'] = $total;
        return $resData;
    }
}
