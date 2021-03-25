<?php
// +----------------------------------------------------------------------
// | Description: 取消订单
// +----------------------------------------------------------------------
// | Author:  orchief
// +----------------------------------------------------------------------
namespace app\base\behavior;
use think\Db;

class CancleOrder
{
    public function run($content)
    {
		isset($content['status']) ? : $content['status'] = 3;
		validates(
			[
				'order_no'		=>  'require',
				'status'		=>  'require|in:3,4'
			],
			[
				'order_no'		=>  '订单号',
				'status'		=>  '取消类型 默认超时取消 3, 任务异常取消 4'
			], $content
		);

		$orderInfo = Db::table('user_order')->where('order_no', $content['order_no'])->find();
        if($orderInfo && $orderInfo['status'] == 1){

			Db::startTrans();
			try{
				// 修改订单状态
				$cancle_data = [
					'status'  => $content['status']
				];

				Db::table('user_order')->where('order_no', $content['order_no'])->update($cancle_data);

				// 库存回滚
				Db::table('user_task')->where('id', $orderInfo['task_id'])->setInc('last_num', 1);

				Db::commit();
			}catch(\Exception $e){
				Db::rollback();
				abort(['msg' => '取消失败!']);
			}
        }else{
            abort(['msg' => '订单不能取消!']);
        }
    }
}