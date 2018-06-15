<?php

namespace app\index\model;

class WorkHourInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_work_hour_info';
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
	public function isRepeatID($userID,$year,$month) {
		$count = $this
		->where ( 'work_hour_info_employee_id', $userID )
		->where ( 'work_hour_info_year', $year )
		->where ( 'work_hour_info_month', $month )
		->count();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	/*
	 * EXCEL新增员工计生信息
	*/
	public function insert($array)
	{
		 
		$this->data ( [
				'work_hour_info_department' => $array[1],
				'work_hour_info_team' => $array[2],
				'work_hour_info_employee_id' => $array[3],
				'work_hour_info_employee_name' => $array[4],
				'work_hour_info_last_turn' => $array[5],
				'work_hour_info_standard_hour' => $array[6],
				'work_hour_info_real_hour' => $array[7],
				'work_hour_info_year_vacation' => $array[8],
				'work_hour_info_home_vacation' => $array[9],
				'work_hour_info_maternity_vacation' => $array[10],
				'work_hour_info_care_vacation' => $array[11],
				'work_hour_info_marriage_leave' => $array[12],
				'work_hour_info_sick_leave' => $array[13],
				'work_hour_info_other_vacation' => $array[14],
				'work_hour_info_this_turn' => $array[15],
				'work_hour_info_all_turn' => $array[16],
				'work_hour_info_year' => $array[17],
				'work_hour_info_month' => $array[18]
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("工时insert信息错误：".$e->getMessage()."年月ID：". $array[17].$array[18].$array[3]);
			return false;
		}
		return true;
	}
	/*
	 * EXCEL更新员工计生信息
	*/
	public function updata($array)
	{
		try {
			$this
			->where('work_hour_info_employee_id' , $array[3])
			->where('work_hour_info_year' , $array[17])
			->where('work_hour_info_month' , $array[18])->update ( [
					'work_hour_info_department' => $array[1],
					'work_hour_info_team' => $array[2],
					'work_hour_info_employee_id' => $array[3],
					'work_hour_info_employee_name' => $array[4],
					'work_hour_info_last_turn' => $array[5],
					'work_hour_info_standard_hour' => $array[6],
					'work_hour_info_real_hour' => $array[7],
					'work_hour_info_year_vacation' => $array[8],
					'work_hour_info_home_vacation' => $array[9],
					'work_hour_info_maternity_vacation' => $array[10],
					'work_hour_info_care_vacation' => $array[11],
					'work_hour_info_marriage_leave' => $array[12],
					'work_hour_info_sick_leave' => $array[13],
					'work_hour_info_other_vacation' => $array[14],
					'work_hour_info_this_turn' => $array[15],
					'work_hour_info_all_turn' => $array[16],
					'work_hour_info_year' => $array[17],
					'work_hour_info_month' => $array[18]
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("工时update信息错误：".$e->getMessage()."年月ID：". $array[17].$array[18].$array[3]);
			return false;
		}
		return true;
	}
	/*
	 * 查询表中全部数据
	*/
	public function getAll($postYear,$postMonth,$postDepartment,$postTeam,$postUserID,$postUserName) {
		try {
				if($postUserName!=""&&$postUserID!="")
				{
					$_Map = [
								'work_hour_info_employee_id' => $postUserID,
								'work_hour_info_employee_name' => $postUserName
							];
				}
				else if($postUserName==""&&$postUserID!="")
				{
					$_Map = [
								'work_hour_info_employee_id' => $postUserID
							];
				}
				else if($postUserName!=""&&$postUserID=="")
				{
					$_Map = [
								'work_hour_info_employee_name' => $postUserName
							];
				}
				else 
				{
					if($postDepartment!=" "&&$postTeam!=" ")
					{
						$_Map = [
									'work_hour_info_department' => $postDepartment,
									'work_hour_info_team' => $postTeam
								];
					}
					else if($postDepartment!=" "&&$postTeam==" ")
					{
						$_Map = [
									'work_hour_info_department' => $postDepartment
							    ];
					}
					else if($postDepartment==" "&&$postTeam!=" ")
					{
						$_Map = [
									'work_hour_info_team' => $postTeam
							    ];
					}
				}
				$ret = $this
				->where('work_hour_info_year',$postYear)
				->where('work_hour_info_month',$postMonth)
				->where ( $_Map )
				->select ();
				//var_dump($ret);
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 查询部门
	 */
	public function getWorkhourDepartmentList(){
		return $this->distinct(true)->field('work_hour_info_department')->select();
	}
	/*
	 * 查询班组
	 */
	public function getWorkhourTeamList(){
		return $this->distinct(true)->field('work_hour_info_team')->select();
	}
	/*
	 * 删除工时记录
	 */
	public function deleteRecord($postUserID,$postYear,$postMonth)
	{
		try {
			$this
			->where ( 'work_hour_info_employee_id', $postUserID )
			->where ( 'work_hour_info_year', $postYear )
			->where ( 'work_hour_info_month', $postMonth )
			->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
