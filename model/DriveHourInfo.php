<?php

namespace app\index\model;

class DriveHourInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_drive_hour_info';
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
	public function isRepeatID($userID,$year) {
		$count = $this
		->where ( 'drive_hour_info_employee_id', $userID )
		->where ( 'drive_hour_info_year', $year )
		->count();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	/*
	 * EXCEL新增员工驾驶时长信息
	*/
	public function insert($array)
	{
		 
		$this->data ( [
				'drive_hour_info_employee_id' => $array[0],
				'drive_hour_info_employee_name' => $array[1],
				'drive_hour_info_center' => $array[2],
				'drive_hour_info_post' => $array[3],
				'drive_hour_info_line' => $array[4],
				'drive_hour_info_january' => $array[5],
				'drive_hour_info_february' => $array[6],
				'drive_hour_info_march' => $array[7],
				'drive_hour_info_april' => $array[8],
				'drive_hour_info_may' => $array[9],
				'drive_hour_info_june' => $array[10],
				'drive_hour_info_july' => $array[11],
				'drive_hour_info_august' => $array[12],
				'drive_hour_info_september' => $array[13],
				'drive_hour_info_october' => $array[14],
				'drive_hour_info_november' => $array[15],
				'drive_hour_info_december' => $array[16],
				'drive_hour_info_year' => $array[17]
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("客车队驾驶时insert信息错误：".$e->getMessage()."年ID：". $array[17].$array[0]);
			return false;
		}
		return true;
	}
	/*
	 * EXCEL更新员工驾驶时长信息
	*/
	public function updata($array)
	{
		try {
			$this
			->where('drive_hour_info_employee_id' , $array[0])
			->where('drive_hour_info_year' , $array[17])->update ( [
					'drive_hour_info_employee_name' => $array[1],
					'drive_hour_info_center' => $array[2],
					'drive_hour_info_post' => $array[3],
					'drive_hour_info_line' => $array[4],
					'drive_hour_info_january' => $array[5],
					'drive_hour_info_february' => $array[6],
					'drive_hour_info_march' => $array[7],
					'drive_hour_info_april' => $array[8],
					'drive_hour_info_may' => $array[9],
					'drive_hour_info_june' => $array[10],
					'drive_hour_info_july' => $array[11],
					'drive_hour_info_august' => $array[12],
					'drive_hour_info_september' => $array[13],
					'drive_hour_info_october' => $array[14],
					'drive_hour_info_november' => $array[15],
					'drive_hour_info_december' => $array[16],
					'drive_hour_info_year' => $array[17]
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("客车队驾驶时update信息错误：".$e->getMessage()."年ID：". $array[17].$array[0]);
			return false;
		}
		return true;
	}
	/*
	 * 查询表中全部数据
	*/
	public function getAll($postUserName,$postUserID,$postYear,$postLine) {
		try {
				if($postUserName!=""&&$postUserID!="")
				{
					$_Map = [
								'drive_hour_info_employee_id' => $postUserID,
								'drive_hour_info_employee_name' => $postUserName
							];
				}
				else if($postUserName==""&&$postUserID!="")
				{
					$_Map = [
								'drive_hour_info_employee_id' => $postUserID
							];
				}
				else if($postUserName!=""&&$postUserID=="")
				{
					$_Map = [
								'drive_hour_info_employee_name' => $postUserName
							];
				}
				else 
				{
					if($postLine!=" ")
					{
						$_Map = [
									'drive_hour_info_line' => $postLine
								];
					}
				}
				$ret = $this
				->where('drive_hour_info_year',$postYear)
				->where ( $_Map )
				->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 查询发文号
	 */
	public function getDriveHourLineList(){
		return $this->distinct(true)->field('drive_hour_info_line')->select();
	}

	/*
	 * 删除记录
	 */
	public function deleteRecord($postUserID,$postYear)
	{
		try {
			$this
			->where ( 'drive_hour_info_employee_id', $postUserID )
			->where ( 'drive_hour_info_year', $postYear )
			->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
