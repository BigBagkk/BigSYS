<?php

namespace app\index\model;

class RewardsPunishmentInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_rewards_punishment_info';
	// 设置当前模型的数据库连接
	protected $connection = [ 
			// 数据库类型
			'type' => 'mysql',
			// 服务器地址
			'hostname' => '127.0.0.1',
			// 数据库名
			'database' => 'bigsystemdatabase',
			// 数据库用户名
			'username' => 'root',
			// 数据库密码
			'password' => '',
			// 数据库编码默认采用utf8
			'charset' => 'utf8',
			// 数据库表前缀
			'prefix' => 'bigsys_',
			// 数据库调试模式
			'debug' => false 
	];
	/*
	 * 判断是否库里有对应序号 @return bool 存在返回true ,不存在返回false
	*/
	public function isRepeatID($ID) {
		$count = $this->where ( 'id', $ID )->count();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	/*
	 * EXCEL新增个人考评信息
	*/
	public function insert($array)
	{
		 
		$this->data ( [
				'id' => $array[0],
				'rewards_punishment_info_record_date' => $array[1],
				'rewards_punishment_info_record_ID' => $array[2],
				'rewards_punishment_info_properties' => $array[3],
				'rewards_punishment_info_kind' => $array[4],
				'rewards_punishment_info_employee_id' => $array[5],
				'rewards_punishment_info_employee_name' => $array[6],
				'rewards_punishment_info_post' => $array[7],
				'rewards_punishment_info_center' => $array[8],
				'rewards_punishment_info_department' => $array[9],
				'rewards_punishment_info_subsection' => $array[10],
				'rewards_punishment_info_politics' => $array[11],
				'rewards_punishment_info_evenet_kind' => $array[12],
				'rewards_punishment_info_evenet_date' => $array[13],
				'rewards_punishment_info_evenet_rule' => $array[14],
				'rewards_punishment_info_money' => $array[15],
				'rewards_punishment_info_details' => $array[16],
				'rewards_punishment_info_tips' => $array[17],
				'rewards_punishment_info_statistics_month' => $array[18],
				'rewards_punishment_info_statistics_year' => $array[19],
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("奖惩insert信息错误：".$e->getMessage()."序号ID：". $array[0]);
			return false;
		}
		return true;
	}
	/*
	 * EXCEL更新个人考评信息
	*/
	public function updata($array)
	{
		try {
			$this->where('id' , $array[0])->update ( [
				'rewards_punishment_info_record_date' => $array[1],
				'rewards_punishment_info_record_ID' => $array[2],
				'rewards_punishment_info_properties' => $array[3],
				'rewards_punishment_info_kind' => $array[4],
				'rewards_punishment_info_employee_id' => $array[5],
				'rewards_punishment_info_employee_name' => $array[6],
				'rewards_punishment_info_post' => $array[7],
				'rewards_punishment_info_center' => $array[8],
				'rewards_punishment_info_department' => $array[9],
				'rewards_punishment_info_subsection' => $array[10],
				'rewards_punishment_info_politics' => $array[11],
				'rewards_punishment_info_evenet_kind' => $array[12],
				'rewards_punishment_info_evenet_date' => $array[13],
				'rewards_punishment_info_evenet_rule' => $array[14],
				'rewards_punishment_info_money' => $array[15],
				'rewards_punishment_info_details' => $array[16],
				'rewards_punishment_info_tips' => $array[17],
				'rewards_punishment_info_statistics_month' => $array[18],
				'rewards_punishment_info_statistics_year' => $array[19],
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("奖惩update信息错误：".$e->getMessage()."序号ID：". $array[0]);
			return false;
		}
		return true;
	}
	/*
	 * 查询表中全部数据
	*/
	public function getAll($postBeginDate,$postEndDate,$postUserID,$postUserName) {
		try {
				if($postUserName!=""&&$postUserID!="")
				{
					$_Map = [
								'rewards_punishment_info_employee_id' => $postUserID,
								'rewards_punishment_info_employee_name' => $postUserName
							];
				}
				else if($postUserName==""&&$postUserID!="")
				{
					$_Map = [
								'rewards_punishment_info_employee_id' => $postUserID
							];
				}
				else if($postUserName!=""&&$postUserID=="")
				{
					$_Map = [
								'rewards_punishment_info_employee_name' => $postUserName
							];
				}
				$ret = $this
				->where('rewards_punishment_info_record_date','between',[$postBeginDate,$postEndDate])->where ( $_Map )->select ();
				//$ret = $this->where ( $_Map )->select ();
				//$ret = $_Map;
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 查询发文号
	 */
	public function recordIDListSearch(){
		return $this->distinct(true)->field('rewards_punishment_info_record_ID')->select();
	}
	/*
	 * 查询奖惩性质
	 */
	public function propertiesListSearch(){
		return $this->distinct(true)->field('rewards_punishment_info_properties')->select();
	}
	/*
	 * 查询奖惩性质
	 */
	public function kindListSearch(){
		return $this->distinct(true)->field('rewards_punishment_info_kind')->select();
	}
	/*
	 * 删除记录
	 */
	public function deleteEvent($postEventID)
	{
		try {
			$this->where ( 'id', $postEventID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
