<?php

namespace app\index\model;


class DepartmentInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_department_info';
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
	 * 下拉列表部门信息查询 @return array
	 */
	public function departmentInfoListSearch() {
		return $this->select();
	}
	/*
	 * 新增新部门 @return bool
	 */
	public function departmentAdd($postDepartmentName) {
				// $this->name = "你好";//全局变量冲突
		// $this->title = "一级菜单";
		$this->data ( [ 
				'department_info' => $postDepartmentName
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
	 * 更新部门 @return bool
	 */
	public function departmentEdit($postDepartmentID,$postDepartmentName) {
		try {
			$this->save (
				['department_info' => $postDepartmentName],
				['id' => $postDepartmentID] );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 通过内容获取对应id
	 */
	public function getIndexByName($departmentName)
	{
		return $this->where('department_info',$departmentName)->column ( 'id' );
	}
	
	
	
}
