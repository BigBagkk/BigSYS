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
use app\index\model\EvaluateSelfInfo;
use app\index\model\EvaluateCollectiveInfo;
use app\index\model\UpdataManagementInfo;

class Evaluate extends CommAction {
	/*
	 * 个人考评基本信息管理
	 */
	public function evaluateSelfInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 集体考评基本信息管理
	 */
	public function evaluateCollectiveInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 个人考评信息Excel表格导入
	*/
	public function importSelfInfoRecord() {
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
			$evaluateSelfInfo =  new EvaluateSelfInfo();
			$faultCount = 0;
			$insertCount = 0;
			$updateCount = 0;
			$faultArray = array();
			//循环读取excel表格,读取一条,插入一条
			for ($row = 2; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn .
						$row, NULL, TRUE, FALSE);
				foreach ($rowData as $k) {
					//$k就是EXCEL表每行的数据，取出具体单元格数据，使用$k[0],以此类推
					//以下具体分析处理每行数据
					//判断员工号是否重复
					if($evaluateSelfInfo->isRepeatID($k[6],$k[11],$k[12]))
					{
						//如果存在，则更新
						if(!$evaluateSelfInfo->updata($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[6],'year'=>$k[11],'month'=>$k[12]);//记录序号
						}
						else {
							$updateCount++;
						}
					}
					else {
						//如果不存在，则新增
						if(!$evaluateSelfInfo->insert($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[6],'year'=>$k[11],'month'=>$k[12]);//记录序号
						}
						else{
							$insertCount++;
						}
					}
				}
			}
			$this->assign('faultCount',$faultCount);
			$this->assign('insertCount',$insertCount);
			$this->assign('updateCount',$updateCount);
			$this->assign('faultArray',$faultArray);
			//return $this->fetch();
			
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共更新'.$updateCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E004', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 * 集体考评信息Excel表格导入
	*/
	public function importCollectiveInfoRecord() {
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
			$evaluateCollectiveInfo =  new EvaluateCollectiveInfo();
			$faultCount = 0;
			$insertCount = 0;
			$updateCount = 0;
			$faultArray = array();
			//循环读取excel表格,读取一条,插入一条
			for ($row = 2; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn .
						$row, NULL, TRUE, FALSE);
				foreach ($rowData as $k) {
					//$k就是EXCEL表每行的数据，取出具体单元格数据，使用$k[0],以此类推
					//以下具体分析处理每行数据
					//婚姻时间
					if($k[6]!=null)
					{
						$k[6] = date('Y-m-d', (($k[6]-25569) * 24*60*60));
					}
					
					
					//判断员工号是否重复
					if($evaluateCollectiveInfo->isRepeatID($k[0]))
					{
						//如果存在，则更新
						if(!$evaluateCollectiveInfo->updata($k))
						{
							$faultCount++;
							$faultArray[]=array('ID'=>$k[0]);//记录序号
						}
						else {
							$updateCount++;
						}
					}
					else {
						//如果不存在，则新增
						if(!$evaluateCollectiveInfo->insert($k))
						{
							$faultCount++;
							$faultArray[]=array('ID'=>$k[0]);//记录序号
						}
						else{
							$insertCount++;
						}
					}
				}
			}
			$this->assign('faultCount',$faultCount);
			$this->assign('insertCount',$insertCount);
			$this->assign('updateCount',$updateCount);
			$this->assign('faultArray',$faultArray);
			//return $this->fetch();
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共更新'.$updateCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E005', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 *  查询部门信息列表
	 */
	public function getSelfInfoDepartmentList(){
			$evaluateSelfInfo = new EvaluateSelfInfo();
			return $evaluateSelfInfo->selfInfoDepartmentListSearch ();
	}
	/*
	 *  查询班组信息列表
	 */
	public function getSelfInfoTeamList(){
			$evaluateSelfInfo = new EvaluateSelfInfo();
			return $evaluateSelfInfo->selfInfoTeamListSearch ();
	}
	/*
	 *  查询集体考评班组信息列表
	 */
	public function getSelectDutyTeamList(){
			$evaluateCollectiveInfo = new EvaluateCollectiveInfo();
			return $evaluateCollectiveInfo->dutyTeamListSearch ();
	}
	/*
	 *  查询集体考评类型信息列表
	 */
	public function getSelectDutyType(){
			$evaluateCollectiveInfo = new EvaluateCollectiveInfo();
			return $evaluateCollectiveInfo->dutyTypeListSearch ();
	}
	/*
	 * 查询个人考评全部记录
	 */
	public function selfInfoRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$request = Request::instance ();
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
		$postYear = $request->post ( 'year' );
		$postMonth = $request->post ( 'month' );
		$postDepartment = $request->post ( 'department' );
		$postTeam = $request->post ( 'team' );
		//echo $postRecordID.$postProperties.$postKind.$postUserName.$postUserID.$postBeginDate.$postEndDate;
		$evaluateSelfInfo = new EvaluateSelfInfo();
		$selectEvaluateSelfInfo = $evaluateSelfInfo->getAll($postYear,$postMonth,$postDepartment,$postTeam,$postUserID,$postUserName);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectEvaluateSelfInfo',$selectEvaluateSelfInfo);
		return $this->fetch();
	}
	/*
	 * 查询集体考评全部记录
	 */
	public function collectiveInfoRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$postBeginDate = $request->post ( 'beginDate' );
		$postEndDate = $request->post ( 'endDate' );
		$postDutyTeam = $request->post ( 'dutyTeam' );
		$postDutyType = $request->post ( 'dutyType' );
// 		echo $postBeginDate.$postEndDate.$postDutyTeam.$postDutyType;
// 		RETURN;
		$evaluateCollectiveInfo = new EvaluateCollectiveInfo();
		$selectEvaluateCollectiveInfo = $evaluateCollectiveInfo->getAll($postBeginDate,$postEndDate,$postDutyTeam,$postDutyType);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectEvaluateCollectiveInfo',$selectEvaluateCollectiveInfo);
		return $this->fetch();
	}
	/*
	 * 个人考评删除记录 
	 */
	public function selfInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postUserID = $request->post( 'userID' );
		$postYear = $request->post( 'year' );
		$postMonth = $request->post( 'month' );
		$evaluateSelfInfo = new EvaluateSelfInfo();
		$result = $evaluateSelfInfo->deleteEvaluateSelfInfo($postUserID,$postYear,$postMonth);
		return $result;
	}
	/*
	 * 集体考评删除记录 
	 */
	public function collectiveInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postID = $request->post( 'id' );
		$evaluateCollectiveInfo = new EvaluateCollectiveInfo();
		$result = $evaluateCollectiveInfo->deleteEvaluateCollectiveInfo($postID);
		return $result;
	}
	/*
	 * 个人考评信息Excel表格导出
	*/
	public function exportEvaluateSelfInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postYear = $request->get( 'year' );
		$postMonth = $request->get( 'month' );
		$postDepartment = $request->get( 'department' );
		$postTeam = $request->get( 'team' );
		$postUserName = $request->get( 'userName' );
		$postUserID = $request->get( 'userID' );
// 				echo $postBeginDate.$postEndDate.$postUserName.$postUserID;
// 				return;
		$evaluateSelfInfo = new EvaluateSelfInfo();
		$dataResult =$evaluateSelfInfo->getAll($postYear,$postMonth,$postDepartment,$postTeam,$postUserID,$postUserName);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "个人考评信息记录表";
		$title = "个人考评信息记录表";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:70px;'>生产序列</th>
               <th style='width:70px;'>专业</th>
               <th style='width:70px;'>分部、中心站</th>
               <th style='width:70px;'>工班、自然站</th>
               <th style='width:70px;'>员工姓名</th>
               <th style='width:70px;'>员工编号</th>
               <th style='width:70px;'>在聘岗位</th>
               <th style='width:70px;'>月度工作绩效评价得分</th>
               <th style='width:70px;'>排名</th>
               <th style='width:70px;'>月度评价结果（A\\B\\C\\D档）</th>
               <th style='width:70px;'>年份</th>
               <th style='width:70px;'>月份</th>
               <th style='width:70px;'>结果备注</th>
           </tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headTitle,$filename);
// 		var_dump($dataResult);
	}
	/*
	 * 集体考评信息Excel表格导出
	*/
	public function exportEvaluateCollectiveInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postBeginDate = $request->get( 'beginDate' );
		$postEndDate = $request->get( 'endDate' );
		$postDutyTeam = $request->get( 'dutyTeam' );
		$postDutyType = $request->get( 'dutyType' );
// 				echo $postDutyTeam.$postDutyType.$postBeginDate.$postEndDate;
// 				return;
		$evaluateCollectiveInfo = new EvaluateCollectiveInfo();
		$dataResult =$evaluateCollectiveInfo->getAll($postBeginDate,$postEndDate,$postDutyTeam,$postDutyType);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "集体考评信息记录表";
		$title = "集体考评信息记录表";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:400px;'>通报内容</th>
               <th style='width:200px;'>发文号</th>
               <th style='width:70px;'>责任单位</th>
               <th style='width:70px;'>惩处类型</th>
               <th style='width:70px;'>考核分值</th>
               <th style='width:70px;'>发文日期</th>
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