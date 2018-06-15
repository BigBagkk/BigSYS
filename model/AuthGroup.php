<?php

namespace app\index\model;


class AuthGroup extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_auth_group';
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
	 * 下拉列表角色列表查询 @return array
	 */
	public function roleListSearch() {
// 		return $this->column ( 'title','status' );
		//以title列为索引，status列为值的数组
		//return $this->column ( 'status','title' );
		//return $this->column ('title');
		return $this->select();
	}
	/*
	 * 新增角色 @return array
	 */
	public function addNewRole($postroleName,$poststatus,$rulesID) {
		$this->data ( [
				'title' => $postroleName,
				'status' => $poststatus,
				'rules' =>$rulesID
				] );
		// 防止用户新增一级菜单重复。
		try {
			$this->save ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 删除角色 @return array
	 */
	public function roleDelete($postroleName) {
		// 防止用户新增一级菜单重复。
		try {
			$this->where ( 'title', $postroleName )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 更新角色 @return array
	 */
	public function editRole($postroleName,$poststatus,$rulesID) {
// 		$this->data ([
// 				'title' => $postroleName,
// 				'status' => $poststatus,
// 				'rules' =>$rulesID
// 				]);
		// 防止用户新增一级菜单重复。
		try {
			$this->save ([
				'title' => $postroleName,
				'status' => $poststatus,
				'rules' =>$rulesID
				],['title'=>$postroleName]);
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 获取角色信息 @return array
	 */
	public function getRoleInfo($postroleName) {
		try {
			//$ret = $this->where('title',$postroleName)->field('status','rules')->select();
			$ret =$this->where('title',$postroleName)->select();
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
// 	/*
// 	 * 三表联合查询，根据处理角色组成员管理提交数据，以角色为查询条件。 @return array
// 	*/
// 	public function roleSearchByRole($postroleName) {
// 		try {
// 			//$ret = $this->where('title',$postroleName)->field('status','rules')->select();
// 			$ret =$this->alias('a')
// 			->join('bigsys_auth_group_access b','a.group_id=b.id','LEFT')
// 			->join('bigsys_staff_info c','c.staff_info_employee_id=b.uid','LEFT')
// 			->join('bigsys_department_info d','c.staff_info_department=d.id','LEFT')
// 			->join('bigsys_subsection_info e','c.staff_info_subsection=e.id','LEFT')
// 			->join('bigsys_team_info f','c.staff_info_team=f.id','LEFT')
// 			->field('a.title,c.staff_info_employee_id,c.staff_info_name,d.department_info,
// 					e.subsection_info,f.team_info')
// 			->where('a.title',$postroleName)
// 			->select();
// 		} catch ( \Exception $e ) {
// 			return false;
// 		}
// 		return $ret;
// 	}
	
	
	
	
	
	
}
