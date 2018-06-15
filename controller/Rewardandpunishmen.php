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

class Rewardandpunishmen extends CommAction {
	/*
	 * 人员基本信息管理
	 */
	public function rewardAndPunishmenInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 奖惩信息Excel表格导入
	*/
	public function import() {
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
			$rewardsPunishmentInfo =  new RewardsPunishmentInfo();
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
					//发文日期
					if($k[1]!=null)
					{
						$k[1] = date('Y-m-d', (($k[1]-25569) * 24*60*60));
					}
					//事件发生日期
					if($k[13]!=null)
					{
						$k[13] = date('Y-m-d', (($k[13]-25569) * 24*60*60));
					}
					//统计月份
					if($k[18]!=null)
					{
						$k[18] = date('Y-m-d', (($k[18]-25569) * 24*60*60));
					}
					
					//判断员工号是否重复
					if($rewardsPunishmentInfo->isRepeatID($k[0]))
					{
						//如果存在，则更新
						if(!$rewardsPunishmentInfo->updata($k))
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
						if(!$rewardsPunishmentInfo->insert($k))
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
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共更新'.$updateCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E003', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
			
			
			//return $this->fetch();
		}
	}
	/*
	 *  查询发文号
	 */
	public function getRecordIDList(){
			$rewardsPunishmentInfo = new RewardsPunishmentInfo();
			return $rewardsPunishmentInfo->recordIDListSearch ();
	}
	/*
	 *  查询奖惩性质
	 */
	public function getPropertiesList(){
			$rewardsPunishmentInfo = new RewardsPunishmentInfo();
			return $rewardsPunishmentInfo->propertiesListSearch ();
	}
	/*
	 *  查询奖惩性质
	 */
	public function getKindList(){
			$rewardsPunishmentInfo = new RewardsPunishmentInfo();
			return $rewardsPunishmentInfo->kindListSearch ();
	}
	/*
	 * 查询全部记录
	 */
	public function rewardAndPunishmenInfoRecordSearch(){
		// 获取POST数据
		$request = Request::instance ();
// 		$postRecordID = $request->post( 'recordID' );
// 		$postProperties = $request->post ( 'properties' );
// 		$postKind = $request->post ( 'kind' );
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
		$postBeginDate = $request->post ( 'beginDate' );
		$postEndDate = $request->post ( 'endDate' );
		//echo $postRecordID.$postProperties.$postKind.$postUserName.$postUserID.$postBeginDate.$postEndDate;
		if($postBeginDate==""||$postEndDate=="")
		{
			//echo "请选择发文日期!!!";
			return "noData";
		}
		$rewardsPunishmentInfo = new RewardsPunishmentInfo();
		$selectRewardsPunishmentInfo = $rewardsPunishmentInfo->getAll($postBeginDate,$postEndDate,$postUserID,$postUserName);
		//$selectRewardsPunishmentInfo =json_encode($selectRewardsPunishmentInfo,JSON_UNESCAPED_UNICODE);
		$this->assign('selectRewardsPunishmentInfo',$selectRewardsPunishmentInfo);
		return $this->fetch();
// 		var_dump($ret);
	}
	/*
	 * 删除记录 
	 */
	public function rewardAndPunishmenInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postEventID = $request->post( 'eventID' );
		$rewardsPunishmentInfo = new RewardsPunishmentInfo();
		$result = $rewardsPunishmentInfo->deleteEvent($postEventID);
		return $result;
	}
	/*
	 * 奖惩信息Excel表格导出
	*/
	public function exportRewardAndPunishmenInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postBeginDate = $request->get( 'beginDate' );
		$postEndDate = $request->get( 'endDate' );
		$postUserName = $request->get( 'userName' );
		$postUserID = $request->get( 'userID' );
// 				echo $postBeginDate.$postEndDate.$postUserName.$postUserID;
// 				return;
		$rewardsPunishmentInfo = new RewardsPunishmentInfo();
		$dataResult =$rewardsPunishmentInfo->getAll($postBeginDate, $postEndDate, $postUserID, $postUserName);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "奖惩信息记录表";
		$title = "奖惩信息记录表";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:70px;'>发文日期</th>
               <th style='width:70px;'>发文号</th>
               <th style='width:70px;'>奖惩性质</th>
               <th style='width:70px;'>奖惩种类</th>
               <th style='width:70px;'>员工编号</th>
               <th style='width:70px;'>姓名</th>
               <th style='width:70px;'>岗位</th>
               <th style='width:70px;'>所在中心</th>
               <th style='width:70px;'>所在部门</th>
               <th style='width:70px;'>所在分部/中心站</th>
               <th style='width:70px;'>政治面貌</th>
               <th style='width:70px;'>事件分类</th>
               <th style='width:70px;'>事件发生日期</th>
               <th style='width:70px;'>引用条款</th>
               <th style='width:70px;'>发生金额</th>
               <th style='width:70px;'>奖惩事实</th>
               <th style='width:70px;'>备注</th>
               <th style='width:70px;'>统计月份</th>
               <th style='width:70px;'>年度</th>
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