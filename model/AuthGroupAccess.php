<?php

namespace app\index\model;


class AuthGroupAccess extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_auth_group_access';
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
	 * 三表联合查询，根据处理角色组成员管理提交数据，以角色为查询条件。 @return array
	*/
	public function roleSearchByRole($postroleName) {
		try {
			//$ret = $this->where('title',$postroleName)->field('status','rules')->select();
			$ret =$this->alias('a')
			->join('bigsys_auth_group b','a.group_id=b.id','LEFT')
			->join('bigsys_staff_info c','c.staff_info_employee_id=a.uid','LEFT')
			->join('bigsys_department_info d','c.staff_info_department=d.id','LEFT')
			->join('bigsys_subsection_info e','c.staff_info_subsection=e.id','LEFT')
			->join('bigsys_team_info f','c.staff_info_team=f.id','LEFT')
			->field('b.title,c.staff_info_employee_id,c.staff_info_name,d.department_info,
					e.subsection_info,f.team_info')
			->where('b.title',$postroleName)
			->select();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	
	
	
	/*
	 * 更新角色组 @return array
	*/
	public function saveRoleGroup($postUID, $postTitleID) {

		try {
			$this->save([
			'group_id' => $postTitleID
			],['uid' => $postUID]);
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 插入新成员到角色组 @return array
	*/
	public function insertRoleGroup($postUID, $postTitleID) {

		try {
			$this->save([
			'group_id' => $postTitleID,
			'uid' => $postUID
			]);
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 判断成员是否存在原有权限，有的话，更新；没有的话，插入。 @return array
	*/
	public function isInRoleGroupAccess($postUID){

		try {
			$count = $this
			->where('uid',$postUID)
			->count();
		} catch ( \Exception $e ) {
			return false;
		}
		return $count;
	}
	/*
	 *删除角色组中角色 @return array
	*/
	public function deleteRoleInGroup($postuDelID) {

		try {
			$this->where ( 'uid', $postuDelID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
