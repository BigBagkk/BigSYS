<?php

namespace app\index\model;

class NormalCheckInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_normal_check_info';
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
	 * EXCEL新增日常检查信息
	*/
	public function insert($array)
	{
		 
		$this->data ( [
				'normal_check_info_date' => $array[1],
				'normal_check_info_level' => $array[2],
				'normal_check_info_department' => $array[3],
				'normal_check_info_team' => $array[4],
				'normal_check_info_module' => $array[5],
				'normal_check_info_kind' => $array[6],
				'normal_check_info_item' => $array[7],
				'normal_check_info_opinin' => $array[8],
				'normal_check_info_deadline' => $array[9],
				'normal_check_info_principal' => $array[10],
				'normal_check_info_measure' => $array[11],
				'normal_check_info_rechecker' => $array[12],
				'normal_check_info_complete' => $array[13],
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("日常检查insert信息错误：".$e->getMessage()."序号ID：". $array[0]);
			return false;
		}
		return true;
	}

	/*
	 * 查询表中全部数据
	*/
	public function getAll($postSelectCheckLevel,$postSelectCheckModule,$postSelectCheckKind,$postBeginDate,$postEndDate) {
		try {
// 						echo $postSelectCheckLevel.$postSelectCheckModule.$postSelectCheckKind.$postBeginDate.$postEndDate;
// 						return;
				if($postSelectCheckModule!="0"&&$postSelectCheckKind!="0")
				{
					$_Map = [
								'normal_check_info_level' => $postSelectCheckLevel,
								'normal_check_info_module' => $postSelectCheckModule,
								'normal_check_info_kind' => $postSelectCheckKind
							];
				}
				if($postSelectCheckModule=="0"&&$postSelectCheckKind=="0")
				{
					$_Map = [
								'normal_check_info_level' => $postSelectCheckLevel
							];
				}
				if($postSelectCheckModule!="0"&&$postSelectCheckKind=="0")
				{
					$_Map = [
								'normal_check_info_level' => $postSelectCheckLevel,
								'normal_check_info_module' => $postSelectCheckModule
							];
				}
				else if($postSelectCheckModule=="0"&&$postSelectCheckKind!="0")
				{
					$_Map = [
								'normal_check_info_level' => $postSelectCheckLevel,
								'normal_check_info_kind' => $postSelectCheckKind
							];
				}
				$ret = $this
				->where('normal_check_info_date','between',[$postBeginDate,$postEndDate])->where ( $_Map )->select ();
				//$ret = $this->where ( $_Map )->select ();
				//$ret = $_Map;
		} catch ( \Exception $e ) {
			return false;
		}
		return $ret;
	}
	/*
	 * 查询检查层级
	 */
	public function normalCheckLevelListSearch(){
		return $this->distinct(true)->field('normal_check_info_level')->select();
	}
	/*
	 * 查询检查层级
	 */
	public function normalCheckModuleListSearch(){
		return $this->distinct(true)->field('normal_check_info_module')->select();
	}
	/*
	 * 查询检查层级
	 */
	public function normalCheckkindListSearch(){
		return $this->distinct(true)->field('normal_check_info_kind')->select();
	}

	/*
	 * 删除记录
	 */
	public function deleteEvent($postEventID)
	{
		try {
			$this->where ( 'id', $postEventID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
