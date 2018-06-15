<?php

namespace app\index\model;

class EvaluateCollectiveInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_evaluate_collective_info';
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
		$count = $this
		->where ( 'id', $ID )
		->count();
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
					'id'=>$array[0],
					'evaluate_collective_info_details' => $array[1],
					'evaluate_collective_info_record_ID' => $array[2],
					'evaluate_collective_info_team' => $array[3],
					'evaluate_collective_info_kind' => $array[4],
					'evaluate_collective_info_value' => $array[5],
					'evaluate_collective_info_record_date' => $array[6]
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("集体考评insert信息错误：".$e->getMessage()."ID：". $array[0]);
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
					'evaluate_collective_info_details' => $array[1],
					'evaluate_collective_info_record_ID' => $array[2],
					'evaluate_collective_info_team' => $array[3],
					'evaluate_collective_info_kind' => $array[4],
					'evaluate_collective_info_value' => $array[5],
					'evaluate_collective_info_record_date' => $array[6]
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("集体考评updata信息错误：".$e->getMessage()."ID：". $array[0]);
			return false;
		}
		return true;
	}
	/*
	 * 查询表中全部数据
	*/
	public function getAll($postBeginDate,$postEndDate,$postDutyTeam,$postDutyType) {
		try {
				if($postDutyTeam!=" "&&$postDutyType!=" ")
				{
					$_Map = [
								'evaluate_collective_info_team' => $postDutyTeam,
								'evaluate_collective_info_kind' => $postDutyType
							];
				}
				else if($postDutyTeam==" "&&$postDutyType!=" ")
				{
					$_Map = [
								'evaluate_collective_info_kind' => $postDutyType
							];
				}
				else if($postDutyTeam!=" "&&$postDutyType==" ")
				{
					$_Map = [
								'evaluate_collective_info_team' => $postDutyTeam
							];
				}
				$ret = $this
				->where('evaluate_collective_info_record_date','between',[$postBeginDate,$postEndDate])->where ( $_Map )->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		//var_dump($_Map);
		return $ret;
	}
	/*
	 * 查询责任单位
	 */
	public function dutyTeamListSearch(){
		return $this->distinct(true)->field('evaluate_collective_info_team')->select();
	}
	/*
	 * 查询奖惩类型单位
	 */
	public function dutyTypeListSearch(){
		return $this->distinct(true)->field('evaluate_collective_info_kind')->select();
	}

	/*
	 * 删除记录
	 */
	public function deleteEvaluateCollectiveInfo($postID)
	{
		try {
			$this->where ( 'id', $postID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
