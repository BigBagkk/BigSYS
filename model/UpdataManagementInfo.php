<?php

namespace app\index\model;

class UpdataManagementInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_updata_management_info';
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
	 * 查询表中全部数据
	*/
	public function getAll() {
		try {
				$ret = $this->select ();
				//var_dump($ret);
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 更新记录
	 */
	public function updataRcord($fileID,$new_filename,$result,$datetime,$userID,$username)
	{
		try {
		$this
		->where('updata_management_info_fileID' , $fileID)
		->update ( [
					'updata_management_info_new_filename' => $new_filename,
					'updata_management_info_result' => $result,
					'updata_management_info_datetime' => $datetime,
					'updata_management_info_userID' => $userID,
					'updata_management_info_username' => $username
				]);
		}
		 catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			//\SeasLog::error("工时update信息错误：".$e->getMessage()."年月ID：". $array[17].$array[18].$array[3]);
			return false;
		}
		return true;
	}
	
}
