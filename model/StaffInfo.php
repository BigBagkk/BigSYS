<?php

namespace app\index\model;

class StaffInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_staff_info';
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
	 * 获取登陆用户用户姓名
	 */
	public function getName($useID) {
		$name = $this->where ( 'staff_info_employee_id', $useID )->column ( 'staff_info_name' );
		// ->field('staff_info_name')
		// ->select();
		return $name [0];
	}
	/*
	 * 删除人员
	 */
	public function deleteStaff($postStaffID)
	{
		try {
			$this->where ( 'staff_info_employee_id', $postStaffID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 查询表中全部数据
	 */
	public function getAll($postDepartmentID,$postSubsectionID,$postTeamID,$postUserName,$postUserID) {
		try {
			
			if($postUserName==""&&$postUserID=="")
			{
				if ($postSubsectionID == 0 && $postTeamID != 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_team' => $postTeamID
							];
				} else if ($postSubsectionID != 0 && $postTeamID == 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_subsection' => $postSubsectionID
							];
				} else if ($postSubsectionID != 0 && $postTeamID != 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_subsection' => $postSubsectionID,
							'a.staff_info_team' => $postTeamID
							];
				} else {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID
							];
				}
			}
			else if($postUserName!=""&&$postUserID=="") 
			{
				$_Map = [
						'a.staff_info_name' => $postUserName
						];
				
			}
			else if($postUserName==""&&$postUserID!="") 
			{
				$_Map = [
						'a.staff_info_employee_id' => $postUserID
						];
				
			}
			else {
				$_Map = [
						'a.staff_info_name' => $postUserName,
						'a.staff_info_employee_id' => $postUserID
						];
			}

			$ret = $this->alias ( 'a' )
			->join ( 'bigsys_department_info d', 'a.staff_info_department=d.id', 'LEFT' )
			->join ( 'bigsys_subsection_info e', 'a.staff_info_subsection=e.id', 'LEFT' )
			->join ( 'bigsys_team_info f', 'a.staff_info_team=f.id', 'LEFT' )
			//->field ( 'd.department_info,e.subsection_info,f.team_info,
			//		a.staff_info_employee_id,a.staff_info_name,c.title' )
			//->field(true)
			->where ( $_Map )
			//->order ( 'd.department_info', 'e.subsection_info', 'f.team_info', 'asc' )
			->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 导出表中全部数据
	 */
	public function exportAll($postDepartmentID,$postSubsectionID,$postTeamID,$postUserName,$postUserID) {
		try {
			
			if($postUserName==""&&$postUserID=="")
			{
				if ($postSubsectionID == 0 && $postTeamID != 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_team' => $postTeamID
							];
				} else if ($postSubsectionID != 0 && $postTeamID == 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_subsection' => $postSubsectionID
							];
				} else if ($postSubsectionID != 0 && $postTeamID != 0) {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID,
							'a.staff_info_subsection' => $postSubsectionID,
							'a.staff_info_team' => $postTeamID
							];
				} else {
					$_Map = [
							'a.staff_info_department' => $postDepartmentID
							];
				}
			}
			else if($postUserName!=""&&$postUserID=="") 
			{
				$_Map = [
						'a.staff_info_name' => $postUserName
						];
				
			}
			else if($postUserName==""&&$postUserID!="") 
			{
				$_Map = [
						'a.staff_info_employee_id' => $postUserID
						];
				
			}
			else {
				$_Map = [
						'a.staff_info_name' => $postUserName,
						'a.staff_info_employee_id' => $postUserID
						];
			}

			$ret = $this->alias ( 'a' )
			->join ( 'bigsys_department_info b', 'a.staff_info_department=b.id', 'LEFT' )
			->join ( 'bigsys_subsection_info c', 'a.staff_info_subsection=c.id', 'LEFT' )
			->join ( 'bigsys_team_info d', 'a.staff_info_team=d.id', 'LEFT' )
			->join ( 'bigsys_post_info e', 'a.staff_info_post=e.id', 'LEFT' )
			->join ( 'bigsys_rank_info f', 'a.staff_info_rank=f.id', 'LEFT' )
			->join ( 'bigsys_sex_info g', 'a.staff_info_sex=g.id', 'LEFT' )
			->join ( 'bigsys_not_on_the_job_info h', 'a.staff_info_not_on_the_job=h.id', 'LEFT' )
			->join ( 'bigsys_marital_info i', 'a.staff_info_marital=i.id', 'LEFT' )
			->join ( 'bigsys_politics_info j', 'a.staff_info_politics=j.id', 'LEFT' )
			->join ( 'bigsys_nation_info k', 'a.staff_info_nation=k.id', 'LEFT')
			->join ( 'bigsys_household_register_info l', 'a.staff_info_household_register=l.id', 'LEFT')
			->join ( 'bigsys_education_info m', 'a.staff_info_education_background=m.id', 'LEFT')
			->field( 'a.staff_info_employee_id,a.staff_info_name,a.staff_info_name_abbreviation,b.department_info,
					  c.subsection_info,d.team_info,e.post_info,f.rank_info,g.sex_info,a.staff_info_identity,
					  a.staff_info_birthday,a.staff_info_in_company_date,a.staff_info_on_post_date,
					  a.staff_info_academician,a.staff_info_class,h.not_on_the_job_info,a.staff_info_always_live,a.staff_info_always_phone_number,
					  i.marital_info,a.staff_info_JIGUAN,j.politics_info,k.nation_info,l.household_register_info,
					  a.staff_info_household_register_address,a.staff_info_hometown,m.education_info,
					  a.staff_info_school,a.staff_info_major,a.staff_info_short_phone_number,a.staff_info_emergency_person_name,
					  a.staff_info_emergency_person_phone_number' )
			//->field(true)
			->where ( $_Map )
			//->order ( 'd.department_info', 'e.subsection_info', 'f.team_info', 'asc' )
			->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 三表联合查询，以部门-分部-班组为查询条件。 @return array
	 */
	public function roleSearchByUser($postDepartmentID, $postSubsectionID, $postTeamID) {
		try {
			
			if ($postSubsectionID == 0 && $postTeamID != 0) {
				$_Map = [ 
						'a.staff_info_department' => $postDepartmentID,
						'a.staff_info_team' => $postTeamID 
				];
			} else if ($postSubsectionID != 0 && $postTeamID == 0) {
				$_Map = [ 
						'a.staff_info_department' => $postDepartmentID,
						'a.staff_info_subsection' => $postSubsectionID 
				];
			} else if ($postSubsectionID != 0 && $postTeamID != 0) {
				$_Map = [ 
						'a.staff_info_department' => $postDepartmentID,
						'a.staff_info_subsection' => $postSubsectionID,
						'a.staff_info_team' => $postTeamID 
				];
			} else {
				$_Map = [ 
						'a.staff_info_department' => $postDepartmentID 
				];
			}
			$ret = $this->alias ( 'a' )
						->join ( 'bigsys_auth_group_access b', 'a.staff_info_employee_id=b.uid', 'LEFT' )
						->join ( 'bigsys_auth_group c', 'b.group_id=c.id', 'LEFT' )
						->join ( 'bigsys_department_info d', 'a.staff_info_department=d.id', 'LEFT' )
						->join ( 'bigsys_subsection_info e', 'a.staff_info_subsection=e.id', 'LEFT' )
						->join ( 'bigsys_team_info f', 'a.staff_info_team=f.id', 'LEFT' )
						->join ( 'bigsys_user_login g', 'a.staff_info_employee_id=g.user_login_name', 'LEFT' )
						->field ( 'd.department_info,e.subsection_info,f.team_info,a.staff_info_employee_id,a.staff_info_name,c.title,g.user_login_status' )
						->where ( $_Map )
						->order ( 'd.department_info', 'e.subsection_info', 'f.team_info', 'asc' )
						->select ();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 判断是否库里有对应员工号人员 @return bool 存在返回true ,不存在返回false
	 */
	public function isRepeatUserid($userID) {
		$count = $this->where ( 'staff_info_employee_id', $userID )->count ();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	/*
	 * EXCEL新增员工
	 */
	public function insertNewStaff($a, $b, $c, $d, $e, $f, $g, $h, $i, $jj, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $aa, $ab, $ac, $ad, $ae, $af)
	 {
	 	
	 	$this->data ( [
	 			'staff_info_employee_id' => $b,'staff_info_name' => $c,'staff_info_name_abbreviation' => $d,
	 			'staff_info_department' => $e,'staff_info_subsection' => $f,'staff_info_team' => $g,
	 			'staff_info_post' => $h,'staff_info_rank' => $i,'staff_info_sex' => $jj,
	 			'staff_info_identity' => $k,'staff_info_birthday' => $l,'staff_info_in_company_date' => $m,
	 			'staff_info_on_post_date' => $n,'staff_info_academician' => $o,'staff_info_class' => $p,
	 			'staff_info_not_on_the_job' => $q,'staff_info_always_live' => $r,'staff_info_always_phone_number' => $s,
	 			'staff_info_marital' => $t,'staff_info_JIGUAN' => $u,'staff_info_politics' => $v,
	 			'staff_info_nation' => $w,'staff_info_household_register' => $x,'staff_info_household_register_address' => $y,
	 			'staff_info_hometown' => $z,'staff_info_education_background' => $aa,'staff_info_school' => $ab,
	 			'staff_info_major' => $ac,'staff_info_short_phone_number' => $ad,'staff_info_emergency_person_name' => $ae,
	 			'staff_info_emergency_person_phone_number' => $af,'staff_info_register_date' => date('Y-m-d')
	 			] );
	 	try {
	 		$this->isUpdate(false)->save ();
	 	} catch ( \Exception $e ) {
	 		//echo  $e->getMessage();
	 		//echo  $e->getMessage();
	 		return false;
	 	}
	 	return true;

	 }
	/*
	 * EXCEL更新员工
	 */
	public function updataNewStaff($a, $b, $c, $d, $e, $f, $g, $h, $i, $jj, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $aa, $ab, $ac, $ad, $ae, $af)
	 {
		try {
// 			$this->update ( [ 
// 	 			'staff_info_name' => $c,'staff_info_name_abbreviation' => $d,
// 	 			'staff_info_department' => $e,'staff_info_subsection' => $f,'staff_info_team' => $g,
// 	 			'staff_info_post' => $h,'staff_info_rank' => $i,'staff_info_sex' => $jj,
// 	 			'staff_info_identity' => $k,'staff_info_birthday' => $l,'staff_info_in_company_date' => $m,
// 	 			'staff_info_on_post_date' => $n,'staff_info_academician' => $o,'staff_info_class' => $p,
// 	 			'staff_info_not_on_the_job' => $q,'staff_info_always_live' => $r,'staff_info_always_phone_number' => $s,
// 	 			'staff_info_marital' => $t,'staff_info_JIGUAN' => $u,'staff_info_politics' => $v,
// 	 			'staff_info_nation' => $w,'staff_info_household_register' => $x,'staff_info_household_register_address' => $y,
// 	 			'staff_info_hometown' => $z,'staff_info_education_background' => $aa,'staff_info_school' => $ab,
// 	 			'staff_info_major' => $ac,'staff_info_short_phone_number' => $ad,'staff_info_emergency_person_name' => $ae,
// 	 			'staff_info_emergency_person_phone_number' => $af,'staff_info_register_date' => date('Y-m-d')
// 			], [ 
// 					'staff_info_employee_id' => $b 
// 			] );
			$this->where('staff_info_employee_id' , $b)->update ( [ 
	 			'staff_info_name' => $c,'staff_info_name_abbreviation' => $d,
	 			'staff_info_department' => $e,'staff_info_subsection' => $f,'staff_info_team' => $g,
	 			'staff_info_post' => $h,'staff_info_rank' => $i,'staff_info_sex' => $jj,
	 			'staff_info_identity' => $k,'staff_info_birthday' => $l,'staff_info_in_company_date' => $m,
	 			'staff_info_on_post_date' => $n,'staff_info_academician' => $o,'staff_info_class' => $p,
	 			'staff_info_not_on_the_job' => $q,'staff_info_always_live' => $r,'staff_info_always_phone_number' => $s,
	 			'staff_info_marital' => $t,'staff_info_JIGUAN' => $u,'staff_info_politics' => $v,
	 			'staff_info_nation' => $w,'staff_info_household_register' => $x,'staff_info_household_register_address' => $y,
	 			'staff_info_hometown' => $z,'staff_info_education_background' => $aa,'staff_info_school' => $ab,
	 			'staff_info_major' => $ac,'staff_info_short_phone_number' => $ad,'staff_info_emergency_person_name' => $ae,
	 			'staff_info_emergency_person_phone_number' => $af,'staff_info_register_date' => date('Y-m-d')
			] );
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			return false;
		}
		return true;
	}
	/*
	 * 表单更新员工
	 */
	public function updataNewStaffByForm($postArray)
	 {
		try {
			$this->where('staff_info_employee_id' , $postArray['textUserID'])->update ( [ 
	 			'staff_info_name' => $postArray['textName'],'staff_info_name_abbreviation' => $postArray['textNameAbbreviation'],
	 			'staff_info_department' => $postArray['modalSelectDepartmentList'],
				'staff_info_subsection' => $postArray['modalSelectSubsectionList'],
				'staff_info_team' => $postArray['modalSelectTeamList'],
	 			'staff_info_post' => $postArray['modalSelectPostList'],
				'staff_info_rank' =>  $postArray['modalSelectRankList'],
				'staff_info_sex' => $postArray['modalSelectSexList'],
	 			'staff_info_birthday' => $postArray['datepickerBirthday'],
				'staff_info_in_company_date' => $postArray['datepickerIndate'],
	 			'staff_info_on_post_date' => $postArray['datepickerOndate'],
				'staff_info_academician' => $postArray['textAcademician'],'staff_info_class' => $postArray['textClass'],
	 			'staff_info_not_on_the_job' => $postArray['modalSelectNotOnTheJobList'],
				'staff_info_always_live' => $postArray['textareaAlwaysLive'],
				'staff_info_always_phone_number' => $postArray['textAlwaysPhoneNumber'],
	 			'staff_info_marital' => $postArray['modalSelectMaritalList'],
				'staff_info_JIGUAN' => $postArray['textJIGUAN'],
				'staff_info_politics' => $postArray['modalSelectPoliticsList'],
	 			'staff_info_nation' => $postArray['modalSelectNationList'],
				'staff_info_household_register' => $postArray['modalSelectHouseholdRegisterList'],
				'staff_info_household_register_address' => $postArray['textareaHouseholdRegisterAddress'],
	 			'staff_info_hometown' => $postArray['textareaHometown'],
				'staff_info_education_background' => $postArray['modalSelectEducationBackgroundList'],
				'staff_info_school' => $postArray['textareaSchool'],
	 			'staff_info_major' => $postArray['textareaMajor'],
				'staff_info_short_phone_number' => $postArray['textShortPhoneNumber'],
				'staff_info_emergency_person_name' => $postArray['textEmergencyPersonName'],
	 			'staff_info_emergency_person_phone_number' => $postArray['textEmergencyPersonPhoneNumber'],
				'staff_info_register_date' => date('Y-m-d')
			] );
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			return false;
		}
		return true;
	}
}
