<?php

namespace app\index\model;

class BirthControlInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_birth_control_info';
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
	 * 判断是否库里有对应员工号人员 @return bool 存在返回true ,不存在返回false
	*/
	public function isRepeatUserid($userID) {
		$count = $this->where ( 'birth_control_info_employee_id', $userID )->count();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	/*
	 * EXCEL新增员工计生信息
	*/
	public function insertNewStaff($arrayNewStaffInfo)
	{
		 
		$this->data ( [
				'birth_control_info_center' => $arrayNewStaffInfo[1],
				'birth_control_info_department' => $arrayNewStaffInfo[2],
				'birth_control_info_employee_id' => $arrayNewStaffInfo[3],
				'birth_control_info_employee_name' => $arrayNewStaffInfo[4],
				'birth_control_info_employee_sex' => $arrayNewStaffInfo[5],
				'birth_control_info_employee_birthday' => $arrayNewStaffInfo[6],
				'birth_control_info_employee_nation' => $arrayNewStaffInfo[7],
				'birth_control_info_employee_JIGUAN' => $arrayNewStaffInfo[8],
				'birth_control_info_employee_is_have_one' => $arrayNewStaffInfo[9],
				'birth_control_info_employee_in_company_date' => $arrayNewStaffInfo[10],
				'birth_control_info_nature_of_employment' => $arrayNewStaffInfo[11],
				'birth_control_info_employee_post' => $arrayNewStaffInfo[12],
				'birth_control_info_employee_identity' => $arrayNewStaffInfo[13],
				'birth_control_info_employee_census_register' => $arrayNewStaffInfo[14],
				'birth_control_info_marital_certify_date' => $arrayNewStaffInfo[15],
				'birth_control_info_marital_certify_expire_date' => $arrayNewStaffInfo[16],
				'birth_control_info_employee_household_register' => $arrayNewStaffInfo[17],
				'birth_control_info_employee_household_register_address' => $arrayNewStaffInfo[18],
				'birth_control_info_employee_always_live' => $arrayNewStaffInfo[19],
				'birth_control_info_employee_contract_term' => $arrayNewStaffInfo[20],
				'birth_control_info_employee_phone_number' => $arrayNewStaffInfo[21],
				'birth_control_info_employee_marital' => $arrayNewStaffInfo[22],
				'birth_control_info_employee_marital_date' => $arrayNewStaffInfo[23],
				'birth_control_info_is_two_employee' => $arrayNewStaffInfo[24],
				'birth_control_info_spouse_name' => $arrayNewStaffInfo[25],
				'birth_control_info_spouse_nation' => $arrayNewStaffInfo[26],
				'birth_control_info_spouse_birthday' => $arrayNewStaffInfo[27],
				'birth_control_info_spouse_identity' => $arrayNewStaffInfo[28],
				'birth_control_info_spousee_census_register' => $arrayNewStaffInfo[29],
				'birth_control_info_spouse_household_register' => $arrayNewStaffInfo[30],
				'birth_control_info_spouse_household_register_address' => $arrayNewStaffInfo[31],
				'birth_control_info_spouse_work' => $arrayNewStaffInfo[32],
				'birth_control_info_measure' => $arrayNewStaffInfo[33],
				'birth_control_info_spouse_is_have_one' => $arrayNewStaffInfo[34],
				'birth_control_info_spouse_phone_number' => $arrayNewStaffInfo[35],
				'birth_control_info_children_count' => $arrayNewStaffInfo[36],
				'birth_control_info_children_name' => $arrayNewStaffInfo[37],
				'birth_control_info_children_sex' => $arrayNewStaffInfo[38],
				'birth_control_info_children_birthday' => $arrayNewStaffInfo[39],
				'birth_control_info_children_nature' => $arrayNewStaffInfo[40],
				'birth_control_info_inout_plan' => $arrayNewStaffInfo[41],
				'birth_control_info_birth_certify_date' => $arrayNewStaffInfo[42],
				'birth_control_info_birth_certify_ID' => $arrayNewStaffInfo[43],
				'birth_control_info_birth_certify_address' => $arrayNewStaffInfo[44],
				'birth_control_info_employee_is_have_one_certify' => $arrayNewStaffInfo[45],
				'birth_control_info_employee_have_one_certify_date' => $arrayNewStaffInfo[46],
				'birth_control_info_employee_have_one_certify_ID' => $arrayNewStaffInfo[47],
				'birth_control_info_employee_have_one_certify_address' => $arrayNewStaffInfo[48],
				'birth_control_info_updata_info' => $arrayNewStaffInfo[49],
				'birth_control_info_updata_date' => date('Y-m-d')
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("新增员工计生信息错误：".$e->getMessage()."员工ID：". $arrayNewStaffInfo[3]);
			return false;
		}
		return true;
	}
	/*
	 * EXCEL更新员工计生信息
	*/
	public function updataNewStaff($arrayNewStaffInfo)
	{
		try {
			$this->where('birth_control_info_employee_id' , $arrayNewStaffInfo[3])->update ( [
			'birth_control_info_center' => $arrayNewStaffInfo[1],
			'birth_control_info_department' => $arrayNewStaffInfo[2],
			'birth_control_info_employee_name' => $arrayNewStaffInfo[4],
			'birth_control_info_employee_sex' => $arrayNewStaffInfo[5],
			'birth_control_info_employee_birthday' => $arrayNewStaffInfo[6],
			'birth_control_info_employee_nation' => $arrayNewStaffInfo[7],
			'birth_control_info_employee_JIGUAN' => $arrayNewStaffInfo[8],
			'birth_control_info_employee_is_have_one' => $arrayNewStaffInfo[9],
			'birth_control_info_employee_in_company_date' => $arrayNewStaffInfo[10],
			'birth_control_info_nature_of_employment' => $arrayNewStaffInfo[11],
			'birth_control_info_employee_post' => $arrayNewStaffInfo[12],
			'birth_control_info_employee_identity' => $arrayNewStaffInfo[13],
			'birth_control_info_employee_census_register' => $arrayNewStaffInfo[14],
			'birth_control_info_marital_certify_date' => $arrayNewStaffInfo[15],
			'birth_control_info_marital_certify_expire_date' => $arrayNewStaffInfo[16],
			'birth_control_info_employee_household_register' => $arrayNewStaffInfo[17],
			'birth_control_info_employee_household_register_address' => $arrayNewStaffInfo[18],
			'birth_control_info_employee_always_live' => $arrayNewStaffInfo[19],
			'birth_control_info_employee_contract_term' => $arrayNewStaffInfo[20],
			'birth_control_info_employee_phone_number' => $arrayNewStaffInfo[21],
			'birth_control_info_employee_marital' => $arrayNewStaffInfo[22],
			'birth_control_info_employee_marital_date' => $arrayNewStaffInfo[23],
			'birth_control_info_is_two_employee' => $arrayNewStaffInfo[24],
			'birth_control_info_spouse_name' => $arrayNewStaffInfo[25],
			'birth_control_info_spouse_nation' => $arrayNewStaffInfo[26],
			'birth_control_info_spouse_birthday' => $arrayNewStaffInfo[27],
			'birth_control_info_spouse_identity' => $arrayNewStaffInfo[28],
			'birth_control_info_spousee_census_register' => $arrayNewStaffInfo[29],
			'birth_control_info_spouse_household_register' => $arrayNewStaffInfo[30],
			'birth_control_info_spouse_household_register_address' => $arrayNewStaffInfo[31],
			'birth_control_info_spouse_work' => $arrayNewStaffInfo[32],
			'birth_control_info_measure' => $arrayNewStaffInfo[33],
			'birth_control_info_spouse_is_have_one' => $arrayNewStaffInfo[34],
			'birth_control_info_spouse_phone_number' => $arrayNewStaffInfo[35],
			'birth_control_info_children_count' => $arrayNewStaffInfo[36],
			'birth_control_info_children_name' => $arrayNewStaffInfo[37],
			'birth_control_info_children_sex' => $arrayNewStaffInfo[38],
			'birth_control_info_children_birthday' => $arrayNewStaffInfo[39],
			'birth_control_info_children_nature' => $arrayNewStaffInfo[40],
			'birth_control_info_inout_plan' => $arrayNewStaffInfo[41],
			'birth_control_info_birth_certify_date' => $arrayNewStaffInfo[42],
			'birth_control_info_birth_certify_ID' => $arrayNewStaffInfo[43],
			'birth_control_info_birth_certify_address' => $arrayNewStaffInfo[44],
			'birth_control_info_employee_is_have_one_certify' => $arrayNewStaffInfo[45],
			'birth_control_info_employee_have_one_certify_date' => $arrayNewStaffInfo[46],
			'birth_control_info_employee_have_one_certify_ID' => $arrayNewStaffInfo[47],
			'birth_control_info_employee_have_one_certify_address' => $arrayNewStaffInfo[48],
			'birth_control_info_updata_info' => $arrayNewStaffInfo[49],
			'birth_control_info_updata_date' => date('Y-m-d')
			]);
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("更新员工计生信息错误：".$e->getMessage()."员工ID：". $arrayNewStaffInfo[3]);
			return false;
		}
		return true;
	}
	
	/*
	 * 查询表中全部数据
	*/
	public function getAll($postSubsection,$postUserName,$postUserID) {
		try {
			if($postUserName!=""&&$postUserID!="")
			{
				$_Map = [
							'birth_control_info_employee_id' => $postUserID,
							'birth_control_info_employee_name' => $postUserName
						];
			}
			else if($postUserName==""&&$postUserID!="")
			{
				$_Map = [
							'birth_control_info_employee_id' => $postUserID
						];
			}
			else if($postUserName!=""&&$postUserID=="")
			{
				$_Map = [
							'birth_control_info_employee_name' => $postUserName
						];
			}
			else if($postSubsection != '——请选择分部、中心站——')
			{
				$_Map = [
							'birth_control_info_department' => $postSubsection
						];
			}
	
			$ret = $this->where ( $_Map )->select ();
// 			$ret = $this->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 导出表中全部数据
	*/
	public function exportAll($postSubsection,$postUserName,$postUserID) {
		try {
			if($postUserName!=""&&$postUserID!="")
			{
				$_Map = [
							'birth_control_info_employee_id' => $postUserID,
							'birth_control_info_employee_name' => $postUserName
						];
			}
			else if($postUserName==""&&$postUserID!="")
			{
				$_Map = [
							'birth_control_info_employee_id' => $postUserID
						];
			}
			else if($postUserName!=""&&$postUserID=="")
			{
				$_Map = [
							'birth_control_info_employee_name' => $postUserName
						];
			}
			else if($postSubsection != '——请选择分部、中心站——')
			{
				$_Map = [
							'birth_control_info_department' => $postSubsection
						];
			}
	
			$ret = $this->where ( $_Map )->select ();
// 			$ret = $this->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 删除人员
	*/
	public function deleteStaff($postStaffID)
	{
		try {
			$this->where ( 'birth_control_info_employee_id', $postStaffID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
