<?php

namespace app\index\model;


class SubsectionInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_subsection_info';
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
	public function subsectionInfoListSearch() {
		return $this->select();
	}
	/*
	 * 更新部门 @return bool
	*/
	public function subsectionEdit($postSubsectionID,$postSubsectionName) {
		try {
			$this->save (
					['subsection_info' => $postSubsectionName],
					['id' => $postSubsectionID] );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 新增分部、中心站 @return bool
	*/
	public function subsectionAdd($postSubsectionName) {
		// $this->name = "你好";//全局变量冲突
		// $this->title = "一级菜单";
		$this->data ( [
				'subsection_info' => $postSubsectionName
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
	 * 通过内容获取对应id
	*/
	public function getIndexByName($name)
	{
		return $this->where('subsection_info',$name)->column ( 'id' );
	}
	
	
}
