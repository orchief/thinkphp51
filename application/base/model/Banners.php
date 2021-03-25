<?php
// +----------------------------------------------------------------------
// | Description: 轮播图
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2019-09-29 13:35:38
// +----------------------------------------------------------------------

namespace app\base\model;

use Utility\Model;

class Banners extends Model
{
    protected $name = 'plant_img';
    protected $param;
    /**
     * 排序字段
     *
     * @var string
     */
    protected $sorts = ['sortBy'=>"desc"];
    /**
     * 允许主表 模糊查询的字段
     *
     * @var array
     */
    protected $likeCons = ["path","detail","sortBy","uri","bannerType","title","position"];
    /**
     * 允许主表 精确查询的字段
     *
     * @var array
     */
    protected  $eqCons  = ["imgType"];
    /**
     * 允许范围查询的字段 支持单段查询
     *
     * @var array
     */
    protected $ranges = ["createTime",];
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

    public function filter($data)
    {
         return $data;
        $data = $data->toArray();
        $temp = [];
        foreach ($data as $k => $v) {
            $temp[$v['position']][] = $v;
        }

        return $temp;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDataById($id){
        // 联合查询
        if ($this->leftJoin) {
            foreach ($this->leftJoin as $k => $v) {
                $this->join($v[0], $v[0].'.'.$v[1] . '=' . $this->name . '.' . $v[2], 'LEFT');
            }
        }
        $res = $this->where($this->name . '.' . $this->pk, $id)->field($this->returnFields)->find();
        return  $res;
    }
}
