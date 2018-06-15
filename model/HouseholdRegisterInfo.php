<?php

namespace app\index\model;


class HouseholdRegisterInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_household_register_info';
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
	 * 通过内容获取对应id
	*/
	public function getIndexByName($name)
	{
		return $this->where('household_register_info',$name)->column ( 'id' );
	}
	
	/*
	 * 下拉列表户籍性质查询 @return array
	*/
	public function householdRegisterInfoListSearch() {
		return $this->select();
	}
	
	
}
