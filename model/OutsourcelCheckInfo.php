<?php

namespace app\index\model;

class OutsourcelCheckInfo extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_outsource_check_info';
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
				'outsource_check_info_date' => $array[1],
				'outsource_check_info_level' => $array[2],
				'outsource_check_info_department' => $array[3],
				'outsource_check_info_team' => $array[4],
				'outsource_check_info_kind' => $array[5],
				'outsource_check_info_item' => $array[6],
				'outsource_check_info_opinin' => $array[7],
				'outsource_check_info_deadline' => $array[8],
				'outsource_check_info_principal' => $array[9],
				'outsource_check_info_measure' => $array[10],
				'outsource_check_info_rechecker' => $array[11],
				'outsource_check_info_complete' => $array[12]
		] );
		try {
			$this->isUpdate(false)->save ();
		} catch ( \Exception $e ) {
			//echo  $e->getMessage();
			//echo  $e->getMessage();
			\SeasLog::error("委外检查insert信息错误：".$e->getMessage()."序号ID：". $array[0]);
			return false;
		}
		return true;
	}

	/*
	 * 查询表中全部数据
	*/
	public function getAll($postSelectCheckLevel,$postSelectCheckTeam,$postSelectCheckKind,$postBeginDate,$postEndDate) {
		try {
// 						echo $postSelectCheckLevel.$postSelectCheckModule.$postSelectCheckKind.$postBeginDate.$postEndDate;
// 						return;
				if($postSelectCheckTeam!="0"&&$postSelectCheckKind!="0")
				{
					$_Map = [
								'outsource_check_info_level' => $postSelectCheckLevel,
								'outsource_check_info_team' => $postSelectCheckTeam,
								'outsource_check_info_kind' => $postSelectCheckKind
							];
				}
				if($postSelectCheckTeam=="0"&&$postSelectCheckKind=="0")
				{
					$_Map = [
								'outsource_check_info_level' => $postSelectCheckLevel
							];
				}
				if($postSelectCheckTeam!="0"&&$postSelectCheckKind=="0")
				{
					$_Map = [
								'outsource_check_info_level' => $postSelectCheckLevel,
								'outsource_check_info_team' => $postSelectCheckTeam
							];
				}
				else if($postSelectCheckTeam=="0"&&$postSelectCheckKind!="0")
				{
					$_Map = [
								'outsource_check_info_level' => $postSelectCheckLevel,
								'outsource_check_info_kind' => $postSelectCheckKind
							];
				}
				$ret = $this
				->where('outsource_check_info_date','between',[$postBeginDate,$postEndDate])->where ( $_Map )->select ();
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
	public function outsourceCheckLevelListSearch(){
		return $this->distinct(true)->field('outsource_check_info_level')->select();
	}
	/*
	 * 查询检查层级
	 */
	public function outsourceCheckTeamListSearch(){
		return $this->distinct(true)->field('outsource_check_info_team')->select();
	}
	/*
	 * 查询检查层级
	 */
	public function outsourceCheckKindListSearch(){
		return $this->distinct(true)->field('outsource_check_info_kind')->select();
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
