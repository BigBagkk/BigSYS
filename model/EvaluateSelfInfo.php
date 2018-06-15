<?php

namespace app\index\model;

class EvaluateSelfInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_evaluate_self_info';
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
		->where ( 'evaluate_self_info_employee_id', $userID )
		->where ( 'evaluate_self_info_year', $year )
		->where ( 'evaluate_self_info_month', $month )
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
				'evaluate_self_info_sequence' => $array[1],
				'evaluate_self_info_profession' => $array[2],
				'evaluate_self_info_department' => $array[3],
				'evaluate_self_info_team' => $array[4],
				'evaluate_self_info_employee_name' => $array[5],
				'evaluate_self_info_employee_id' => $array[6],
				'evaluate_self_info_post' => $array[7],
				'evaluate_self_info_score' => $array[8],
				'evaluate_self_info_ranking' => $array[9],
				'evaluate_self_info_grade' => $array[10],
				'evaluate_self_info_year' => $array[11],
				'evaluate_self_info_month' => $array[12],
				'evaluate_self_info_tips' => $array[13]
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("个人考评insert信息错误：".$e->getMessage()."年月ID：". $array[11].$array[12].$array[6]);
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
			$this
			->where('evaluate_self_info_employee_id' , $array[6])
			->where('evaluate_self_info_year' , $array[11])
			->where('evaluate_self_info_month' , $array[12])->update ( [
					'evaluate_self_info_sequence' => $array[1],
					'evaluate_self_info_profession' => $array[2],
					'evaluate_self_info_department' => $array[3],
					'evaluate_self_info_team' => $array[4],
					'evaluate_self_info_employee_name' => $array[5],
					'evaluate_self_info_post' => $array[7],
					'evaluate_self_info_score' => $array[8],
					'evaluate_self_info_ranking' => $array[9],
					'evaluate_self_info_grade' => $array[10],
					'evaluate_self_info_tips' => $array[13]
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("个人考评updata信息错误：".$e->getMessage()."年月ID：". $array[11].$array[12].$array[6]);
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
								'evaluate_self_info_employee_id' => $postUserID,
								'evaluate_self_info_employee_name' => $postUserName
							];
				}
				else if($postUserName==""&&$postUserID!="")
				{
					$_Map = [
								'evaluate_self_info_employee_id' => $postUserID
							];
				}
				else if($postUserName!=""&&$postUserID=="")
				{
					$_Map = [
								'evaluate_self_info_employee_name' => $postUserName
							];
				}
				else 
				{
					if($postDepartment!=" "&&$postTeam!=" ")
					{
						$_Map = [
									'evaluate_self_info_department' => $postDepartment,
									'evaluate_self_info_team' => $postTeam
								];
					}
					else if($postDepartment!=" "&&$postTeam==" ")
					{
						$_Map = [
									'evaluate_self_info_department' => $postDepartment
							    ];
					}
					else if($postDepartment==" "&&$postTeam!=" ")
					{
						$_Map = [
									'evaluate_self_info_team' => $postTeam
							    ];
					}
				}
				$ret = $this
				->where('evaluate_self_info_year',$postYear)
				->where('evaluate_self_info_month',$postMonth)
				->where ( $_Map )
				->select ();
				//var_dump($ret);
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 查询部门信息列表
	 */
	public function selfInfoDepartmentListSearch(){
		return $this->distinct(true)->field('evaluate_self_info_department')->select();
	}
	/*
	 * 查询班组信息列表
	 */
	public function selfInfoTeamListSearch(){
		return $this->distinct(true)->field('evaluate_self_info_team')->select();
	}

	/*
	 * 删除记录
	 */
	public function deleteEvaluateSelfInfo($postUserID,$postYear,$postMonth)
	{
		try {
			$this
			->where ( 'evaluate_self_info_employee_id', $postUserID )
			->where ( 'evaluate_self_info_year', $postYear )
			->where ( 'evaluate_self_info_month', $postMonth )
			->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
