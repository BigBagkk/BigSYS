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
use app\index\model\WorkHourInfo;
use app\index\model\DriveHourInfo;
use app\index\model\UpdataManagementInfo;

class Manhour extends CommAction {
	/*
	 * 工时信息管理
	 */
	public function workHourInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 客车队驾驶时长信息管理
	 */
	public function driveHourInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 工时信息Excel表格导入
	*/
	public function importWorkHourInfo() {
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
			//$newFilename = $postMyfile->getFilename();//获取文件名称
// 			var_dump($highestRow."--".$highestColumn);
// 			return;
			//创建数据库对象及问题数据数组
			$workHourInfo =  new WorkHourInfo();
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
					//判断记录是否重复，判断条件员工编号+年份+月份
					if($workHourInfo->isRepeatID($k[3],$k[17],$k[18]))
					{
						//如果存在，则更新
						if(!$workHourInfo->updata($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[3],'year'=>$k[17],'month'=>$k[18]);//记录序号
						}
						else {
							$updateCount++;
						}
					}
					else {
						//如果不存在，则新增
						if(!$workHourInfo->insert($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[3],'year'=>$k[17],'month'=>$k[18]);//记录序号
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
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共更新'.$updateCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E006', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 * 驾驶时长信息Excel表格导入
	*/
	public function importDriveHourInfo() {
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
			$driveHourInfo =  new DriveHourInfo();
			$faultCount = 0;
			$insertCount = 0;
			$updateCount = 0;
			$faultArray = array();
			//循环读取excel表格,读取一条,插入一条
			for ($row = 3; $row <= $highestRow; $row++) {
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn .
						$row, NULL, TRUE, FALSE);
				foreach ($rowData as $k) {
					//$k就是EXCEL表每行的数据，取出具体单元格数据，使用$k[0],以此类推
					//以下具体分析处理每行数据
					//判断记录是否重复，判断条件员工编号+年份+月份
					if($driveHourInfo->isRepeatID($k[0],$k[17]))
					{
						//如果存在，则更新
						if(!$driveHourInfo->updata($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[0],'year'=>$k[17]);//记录序号
						}
						else {
							$updateCount++;
						}
					}
					else {
						//如果不存在，则新增
						if(!$driveHourInfo->insert($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[0],'year'=>$k[17]);//记录序号
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
			$updataManagementInfonew->updataRcord('E007', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
		}
	}
	/*
	 *  查询部门
	 */
	public function getWorkhourDepartmentList(){
			$workHourInfo = new WorkHourInfo();
			return $workHourInfo->getWorkhourDepartmentList ();
	}
	/*
	 *  查询班组
	 */
	public function getWorkhourTeamList(){
			$workHourInfo = new WorkHourInfo();
			return $workHourInfo->getWorkhourTeamList ();
	}
	/*
	 *  查询线路
	 */
	public function getDrivehourLineList(){
			$driveHourInfo = new DriveHourInfo();
			return $driveHourInfo->getDriveHourLineList ();
	}

	/*
	 * 查询工时全部记录
	 */
	public function workHourInfoRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
		$postYear = $request->post ( 'year' );
		$postMonth = $request->post ( 'month' );
		$postDepartment = $request->post ( 'department' );
		$postTeam = $request->post ( 'team' );
// 		echo $postYear.$postMonth.$postDepartment.$postTeam.$postUserID.$postUserName;
// 		return;
		$workHourInfo = new WorkHourInfo();
		$selectWorkHourInfo = $workHourInfo->getAll($postYear,$postMonth,$postDepartment,$postTeam,$postUserID,$postUserName);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectWorkHourInfo',$selectWorkHourInfo);
		return $this->fetch();
// 		var_dump($ret);
	}
	/*
	 * 查询驾驶时长全部记录
	 */
	public function driveHourInfoRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
		$postYear = $request->post ( 'year' );
		$postLine = $request->post ( 'line' );
// 		echo $postYear.$postMonth.$postDepartment.$postTeam.$postUserID.$postUserName;
// 		return;
        $driveHourInfo =  new DriveHourInfo();
		$selectDriveHourInfo = $driveHourInfo->getAll($postUserName,$postUserID,$postYear,$postLine);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectDriveHourInfo',$selectDriveHourInfo);
		return $this->fetch();
// 		var_dump($ret);
	}
	/*
	 * 删除工时记录 
	 */
	public function workHourInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postUserID = $request->post( 'userID' );
		$postYear = $request->post( 'year' );
		$postMonth = $request->post( 'month' );
		$workHourInfo = new WorkHourInfo();
		$result = $workHourInfo->deleteRecord($postUserID,$postYear,$postMonth);
		return $result;
	}
	/*
	 * 删除驾驶时长记录 
	 */
	public function driveHourInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postUserID = $request->post( 'userID' );
		$postYear = $request->post( 'year' );
// 		var_dump($postUserID.$postYear);
// 		return;
		$driveHourInfo = new DriveHourInfo();
		$result = $driveHourInfo->deleteRecord($postUserID,$postYear);
		return $result;
	}
	/*
	 * 工时信息Excel表格导出
	*/
	public function exportWorkHourInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postUserName = $request->get ( 'userName' );
		$postUserID = $request->get ( 'userID' );
		$postYear = $request->get ( 'year' );
		$postMonth = $request->get ( 'month' );
		$postDepartment = $request->get ( 'department' );
		$postTeam = $request->get ( 'team' );
// 				echo $postYear.$postMonth.$postDepartment.$postTeam.$postUserName.$postUserID;
// 				return;
		$workHourInfo = new WorkHourInfo();
		$dataResult = $workHourInfo->getAll($postYear,$postMonth,$postDepartment,$postTeam,$postUserID,$postUserName);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = $postYear."年".$postMonth."月"."工时信息记录表";
		$title = "工时信息记录表";
		$titlename = "
					<tr>
					<th >序号</th>
					<th >部门/分部</th>
					<th >班组</th>
					<th >员工编号</th>
					<th >员工姓名</th>
					<th >上月结转（±）A</th>
					<th >月标准工时 B1</th>
					<th >实际出勤工时B2</th>
					<th >年休假C1</th>
					<th >探亲假C2</th>
					<th >产假/计生假C3</th>
					<th >独生/看护假C4</th>
					<th >婚假/丧假C5</th>
					<th >病假/事假C6</th>
					<th >病假/事假C6</th>
					<th >本月结转（±）D</th>
					<th >累计结转（±）E</th>
					<th >统计年份</th>
					<th >统计月份</th>
				</tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headTitle,$filename);
// 		var_dump($dataResult);
	}
	/*
	 * 驾驶时长信息Excel表格导出
	*/
	public function exportDriveHourInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postUserName = $request->get ( 'userName' );
		$postUserID = $request->get ( 'userID' );
		$postYear = $request->get ( 'year' );
		$postLine = $request->get ( 'line' );
// 				echo $postYear.$postMonth.$postDepartment.$postTeam.$postUserName.$postUserID;
// 				return;
		$driveHourInfo = new DriveHourInfo();
		$dataResult = $driveHourInfo->getAll($postUserName,$postUserID,$postYear,$postLine);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = $postYear."年"."工时信息记录表";
		$title = "工时信息记录表";
		$titlename = "
				<tr>
					<th >序号</th>
					<th >员工编号</th>
					<th >姓名</th>
					<th >中心</th>
					<th >岗位名称</th>
					<th >所属线路</th>
					<th >1月</th>
					<th >2月</th>
					<th >3月</th>
					<th >4月</th>
					<th >5月</th>
					<th >6月</th>
					<th >7月</th>
					<th >8月</th>
					<th >9月</th>
					<th >10月</th>
					<th >11月</th>
					<th >12月</th>
					<th >统计时间</th>
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