<?php

namespace app\index\controller;

use app\index\controller\CommAction;
use think\Request;
use app\index\model\DepartmentInfo;
use app\index\model\TeamInfo;
use app\index\model\SubsectionInfo;
use app\index\model\StaffInfo;

use PHPExcel_IOFactory;
use PHPExcel;
use think\session;
use app\index\model\RewardsPunishmentInfo;
use app\index\model\UpdataManagementInfo;
use app\index\model\NormalCheckInfo;
use app\index\model\OutsourcelCheckInfo;

class Normalcheck extends CommAction {
	/*
	 * 日常检查信息管理
	 */
	public function normalCheckRecord() {
		return $this->fetch ();
	}
	/*
	 * 委外检查信息管理
	 */
	public function outsourceCheckRecord() {
		return $this->fetch ();
	}
	/*
	 * 日常检查信息Excel表格导入
	*/
	public function importNormalCheckInfo() {
		// 获取POST上传的EXCEL表格
		$request = Request::instance ();
		$postMyfile = $request->file ( 'myfile' );
		$postMyfileName = $request->post( 'filename' );
		// 判断是否选择了要上传的表格,如果超过10M，也返回null,在PHP.INI post_max_size = 10M
		if (empty ( $postMyfile )) {
			return "noUpload";
			exit ();
		}
		// 获取表格的大小，限制上传表格的大小5M
		if (! $postMyfile->checkSize ( 5 * 1024 * 1024 )) {
			return "overMaxSize";
			exit ();
		}
		// 判断表格是否上传成功
		if (is_uploaded_file ( $postMyfile->getPathname () )) {
			$objPHPExcel = PHPExcel_IOFactory::load ( $postMyfile->getPathname ());
			$sheet = $objPHPExcel->getSheet(0);  //  取得第一张表
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn();// 取得总列数
// 			var_dump($highestRow."--".$highestColumn);
// 			return;
			//创建数据库对象及问题数据数组
			$normalCheckInfo =  new NormalCheckInfo();
			$faultCount = 0;
			$insertCount = 0;
			$faultArray = array();
			//循环读取excel表格,读取一条,插入一条
			for ($row = 3; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn .
						$row, NULL, TRUE, FALSE);
			foreach ($rowData as $k) {
					//$k就是EXCEL表每行的数据，取出具体单元格数据，使用$k[0],以此类推
					//以下具体分析处理每行数据
					//检查日期
					if($k[1]!=null)
					{
						$k[1] = date('Y-m-d', (($k[1]-25569) * 24*60*60));
					}	
					//数据库录入
					if(!$normalCheckInfo->insert($k))
					{
						$faultCount++;
						$faultArray[]=array('ID'=>$k[0]);//记录序号
					}
					else{
						$insertCount++;
					}
				}
			}
			$this->assign('faultCount',$faultCount);
			$this->assign('insertCount',$insertCount);
			$this->assign('faultArray',$faultArray);
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E008', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 * 委外检查信息Excel表格导入
	*/
	public function importOutsourceCheckInfo() {
		// 获取POST上传的EXCEL表格
		$request = Request::instance ();
		$postMyfile = $request->file ( 'myfile' );
		$postMyfileName = $request->post( 'filename' );
		// 判断是否选择了要上传的表格,如果超过10M，也返回null,在PHP.INI post_max_size = 10M
		if (empty ( $postMyfile )) {
			return "noUpload";
			exit ();
		}
		// 获取表格的大小，限制上传表格的大小5M
		if (! $postMyfile->checkSize ( 5 * 1024 * 1024 )) {
			return "overMaxSize";
			exit ();
		}
		// 判断表格是否上传成功
		if (is_uploaded_file ( $postMyfile->getPathname () )) {
			$objPHPExcel = PHPExcel_IOFactory::load ( $postMyfile->getPathname ());
			$sheet = $objPHPExcel->getSheet(0);  //  取得第一张表
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn();// 取得总列数
// 			var_dump($highestRow."--".$highestColumn);
// 			return;
			//创建数据库对象及问题数据数组
			$outsourcelCheckInfo =  new OutsourcelCheckInfo();
			$faultCount = 0;
			$insertCount = 0;
			$faultArray = array();
			//循环读取excel表格,读取一条,插入一条
			for ($row = 3; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn .
						$row, NULL, TRUE, FALSE);
			foreach ($rowData as $k) {
					//$k就是EXCEL表每行的数据，取出具体单元格数据，使用$k[0],以此类推
					//以下具体分析处理每行数据
					//检查日期
					if($k[1]!=null)
					{
						$k[1] = date('Y-m-d', (($k[1]-25569) * 24*60*60));
					}	
					//数据库录入
					if(!$outsourcelCheckInfo->insert($k))
					{
						$faultCount++;
						$faultArray[]=array('ID'=>$k[0]);//记录序号
					}
					else{
						$insertCount++;
					}
				}
			}
			$this->assign('faultCount',$faultCount);
			$this->assign('insertCount',$insertCount);
			$this->assign('faultArray',$faultArray);
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E009', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 *  查询检查层级
	 */
	public function getNormalCheckLevelList(){
			$normalCheckInfo =  new NormalCheckInfo();
			return $normalCheckInfo->normalCheckLevelListSearch();
	}
	/*
	 *  查询检查层级
	 */
	public function getOutsourceCheckLevelList(){
			$outsourcelCheckInfo =  new OutsourcelCheckInfo();
			return $outsourcelCheckInfo->outsourceCheckLevelListSearch();
	}
	/*
	 *  查询检查模块
	 */
	public function getNormalCheckModuleList(){
			$normalCheckInfo =  new NormalCheckInfo();
			return $normalCheckInfo->normalCheckModuleListSearch();
	}
	/*
	 *  查询检查单位
	 */
	public function getOutsourceCheckTeamList(){
			$outsourcelCheckInfo =  new OutsourcelCheckInfo();
			return $outsourcelCheckInfo->outsourceCheckTeamListSearch();
	}
	/*
	 *  查询检查类别
	 */
	public function getNormalCheckkindList(){
			$normalCheckInfo =  new NormalCheckInfo();
			return $normalCheckInfo->normalCheckkindListSearch();
	}
	/*
	 *  查询检查类别
	 */
	public function getOutsourceCheckkindList(){
			$outsourcelCheckInfo =  new OutsourcelCheckInfo();
			return $outsourcelCheckInfo->outsourceCheckKindListSearch();
	}

	/*
	 * 查询全部记录
	 */
	public function normalcheckRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$postSelectCheckLevel = $request->post ( 'selectCheckLevel' );
		$postSelectCheckModule = $request->post ( 'selectCheckModule' );
		$postSelectCheckKind = $request->post ( 'selectCheckKind' );
		$postBeginDate = $request->post ( 'beginDate' );
		$postEndDate = $request->post ( 'endDate' );
// 		echo $postSelectCheckLevel.$postSelectCheckModule.$postSelectCheckKind.$postBeginDate.$postEndDate;
// 		return;
		if($postBeginDate==""||$postEndDate=="")
		{
			//echo "请选择检查日期!!!";
			return "noData";
		}
		if($postSelectCheckLevel=="0")
		{
			//echo "请选择检查日期!!!";
			return "noLevel";
		}
		$normalCheckInfo = new NormalCheckInfo();
		$selectNormalcheckRecordSearch = $normalCheckInfo->getAll($postSelectCheckLevel,$postSelectCheckModule,$postSelectCheckKind,$postBeginDate,$postEndDate);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectNormalcheckRecordSearch',$selectNormalcheckRecordSearch);
		return $this->fetch();
// 		var_dump($ret);
	}
	/*
	 * 查询全部记录
	 */
	public function outsourceCheckRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$postSelectCheckLevel = $request->post ( 'selectCheckLevel' );
		$postSelectCheckTeam = $request->post ( 'selectCheckTeam' );
		$postSelectCheckKind = $request->post ( 'selectCheckKind' );
		$postBeginDate = $request->post ( 'beginDate' );
		$postEndDate = $request->post ( 'endDate' );
// 		echo $postSelectCheckLevel.$postSelectCheckModule.$postSelectCheckKind.$postBeginDate.$postEndDate;
// 		return;
		if($postBeginDate==""||$postEndDate=="")
		{
			//echo "请选择检查日期!!!";
			return "noData";
		}
		if($postSelectCheckLevel=="0")
		{
			//echo "请选择检查日期!!!";
			return "noLevel";
		}
		$outsourcelCheckInfo = new OutsourcelCheckInfo();
		$selectOutsourcecheckRecordSearch = $outsourcelCheckInfo->getAll($postSelectCheckLevel,$postSelectCheckTeam,$postSelectCheckKind,$postBeginDate,$postEndDate);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectOutsourcecheckRecordSearch',$selectOutsourcecheckRecordSearch);
		return $this->fetch();
// 		var_dump($ret);
	}
	/*
	 * 删除记录 
	 */
	public function normalcheckInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postEventID = $request->post( 'ID' );
		$normalCheckInfo = new NormalCheckInfo();
		$result = $normalCheckInfo->deleteEvent($postEventID);
		return $result;
	}
	/*
	 * 删除记录 
	 */
	public function outsourcecheckInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postEventID = $request->post( 'ID' );
		$outsourcelCheckInfo = new OutsourcelCheckInfo();
		$result = $outsourcelCheckInfo->deleteEvent($postEventID);
		return $result;
	}
	/*
	 * 日常检查信息Excel表格导出
	*/
	public function exportNormalcheckRecordInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postSelectCheckLevel = $request->get ( 'selectCheckLevel' );
		$postSelectCheckModule = $request->get ( 'selectCheckModule' );
		$postSelectCheckKind = $request->get ( 'selectCheckKind' );
		$postBeginDate = $request->get ( 'beginDate' );
		$postEndDate = $request->get ( 'endDate' );
// 				echo $postBeginDate.$postEndDate.$postUserName.$postUserID;
// 				return;
		$normalCheckInfo = new NormalCheckInfo();
		$dataResult = $normalCheckInfo->getAll($postSelectCheckLevel,$postSelectCheckModule,$postSelectCheckKind,$postBeginDate,$postEndDate);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "日常检查记录表";
		$title = "日常检查记录表";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:70px;'>检查日期</th>
               <th style='width:70px;'>层级</th>
               <th style='width:70px;'>责任室/分部/中心站</th>
               <th style='width:70px;'>班组（车站、车队、工班等）</th>
               <th style='width:70px;'>模块</th>
               <th style='width:70px;'>类别</th>
               <th style='width:70px;'>检查事项</th>
               <th style='width:70px;'>整改措施及其它意见</th>
               <th style='width:70px;'>整改期限</th>
               <th style='width:70px;'>责任人</th>
               <th style='width:70px;'>整改情况</th>
               <th style='width:70px;'>复核人</th>
               <th style='width:70px;'>整改完成情况</th>
           </tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headTitle,$filename);
// 		var_dump($dataResult);
	}
	/*
	 *委外检查信息Excel表格导出
	*/
	public function exportOutsourceCheckRecordInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postSelectCheckLevel = $request->get ( 'selectCheckLevel' );
		$postSelectCheckTeam = $request->get ( 'selectCheckTeam' );
		$postSelectCheckKind = $request->get ( 'selectCheckKind' );
		$postBeginDate = $request->get ( 'beginDate' );
		$postEndDate = $request->get ( 'endDate' );
// 				echo $postBeginDate.$postEndDate.$postUserName.$postUserID;
// 				return;
		$outsourcelCheckInfo = new OutsourcelCheckInfo();
		$dataResult = $outsourcelCheckInfo->getAll($postSelectCheckLevel,$postSelectCheckTeam,$postSelectCheckKind,$postBeginDate,$postEndDate);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "日常检查记录表";
		$title = "日常检查记录表";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:70px;'>检查日期</th>
               <th style='width:70px;'>层级</th>
               <th style='width:70px;'>责任室/分部/中心站</th>
               <th style='width:70px;'>单位</th>
               <th style='width:70px;'>类别</th>
               <th style='width:70px;'>检查事项</th>
               <th style='width:70px;'>整改措施及其它意见</th>
               <th style='width:70px;'>整改期限</th>
               <th style='width:70px;'>责任人</th>
               <th style='width:70px;'>整改情况</th>
               <th style='width:70px;'>复核人</th>
               <th style='width:70px;'>整改完成情况</th>
           </tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headTitle,$filename);
// 		var_dump($dataResult);
	}
	/*
	 *处理Excel导出(百度搜到的)
	*@param $datas array 设置表格数据
	*@param $titlename string 设置head
	*@param $title string 设置表头
	*/
	public function excelData($datas,$titlename,$title,$filename){
		$str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>";
		$str .="<table border=1><head>".$titlename."</head>";
		$str .= $title;
		foreach ($datas  as $key=> $rt )
		{
			$str .= "<tr>";
			foreach ( $rt as $k => $v )
			{
					$str .= "<td>{$v}</td>";
			}
			$str .= "</tr>\n";
		}
		$str .= "</table></body></html>";
		header( "Content-Type: application/vnd.ms-excel; name='excel'" );
		//header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header( "Content-type: application/octet-stream" );
		header( "Content-Disposition: attachment; filename=".$filename );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		exit( $str );
	}
	
}                   