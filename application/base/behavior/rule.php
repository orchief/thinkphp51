<?php
// +----------------------------------------------------------------------
// | Description: 根据菜单自动生成后台系统权限
// +----------------------------------------------------------------------
// | Author:  orchief
// +----------------------------------------------------------------------
namespace app\base\behavior;

use think\Db;

class rule
{
    public function run($userId)
    {
		// 删除所有数据 重置表
		Db::query("TRUNCATE admin_rule");

		//  第一层  pid = 0 menu_type = 1
		Db::table('admin_menu')->where(
			[
				['status',    '=', 1],
				['pid',       '=', 0],
				['menu_type', '=', 1],
			]
		)->chunk(50, function($list) {
			foreach ($list as $key => $value) {
				# 插入 rules表数据
				$rule_data = [
					'title' =>  $value['title'],
					'name'  =>  $value['module'],
					'level' =>  1,
					'pid'   =>  0,
					'status'=>  1
				];
				$rule_id = Db::table('admin_rule')->insertGetId($rule_data);
				# 用rules 表的主键id 更新 menu表的rule_id
				Db::table('admin_menu')->where('id', $value['id'])->update(['rule_id' => $rule_id]);
			}
		});

		// 第二层
		Db::table('admin_menu')->where(
			[
				['status',    '=', 1],
				['pid',       '<>', 0],
				['menu_type', '=', 1],
			]
		)->chunk(50, function($list) {
			foreach ($list as $key => $value) {
				$p_menu = Db::table('admin_menu')->where('id', $value['pid'])->find();
				if(!$p_menu){
					continue;
				}
				$p_rule_id = $p_menu['rule_id'];

				#  插入 rules表数据
				$rule_data = [
					'title' =>  $value['title'],
					'name'  =>  $value['module'],
					'level' =>  2,
					'pid'   =>  $p_rule_id,
					'status'=>  1
				];
				$rule_id = Db::table('admin_rule')->insertGetId($rule_data);
				#  用rules 表的主键id 更新 menu表的rule_id
				Db::table('admin_menu')->where('id', $value['id'])->update(['rule_id' => $rule_id]);
			}
		});


		// 第三层
		Db::table('admin_menu')->where(
			[
				['status',    '=', 1],
				['pid',       '<>', 0],
				['menu_type', '=', 2],
			]
		)->chunk(50, function($list) {
			foreach ($list as $key => $value) {
				$p_menu = Db::table('admin_menu')->where('id', $value['pid'])->find();
				if(!$p_menu){
					continue;
				}
				$p_rule_id = $p_menu['rule_id'];

				# 插入 rules表数据
				$rule_data = [
					'title' =>  $value['title'],
					'name'  =>  $value['module'],
					'level' =>  3,
					'pid'   =>  $p_rule_id,
					'status'=>  1
				];
				$rule_id = Db::table('admin_rule')->insertGetId($rule_data);
				# 用rules 表的主键id 更新 menu表的rule_id
				Db::table('admin_menu')->where('id', $value['id'])->update(['rule_id' => $rule_id]);
			}
		});
	}
}