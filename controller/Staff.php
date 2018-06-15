<?php

namespace app\index\controller;

use app\index\controller\CommAction;
use think\Request;
use app\index\model\DepartmentInfo;
use app\index\model\TeamInfo;
use app\index\model\SubsectionInfo;
use app\index\model\StaffInfo;
use app\index\model\PostInfo;
use app\index\model\RankInfo;
use app\index\model\NotOnTheJobInfo;
use app\index\model\MaritalInfo;
use app\index\model\PoliticsInfo;
use app\index\model\NationInfo;
use app\index\model\HouseholdRegisterInfo;
use app\index\model\EducationInfo;
use app\index\model\BirthControlInfo;

use PHPExcel_IOFactory;
use PHPExcel;
use app\index\model\SexInfo;
use app\index\model\UserLogin;
use think\session;
use app\index\model\UpdataManagementInfo;

class Staff extends CommAction {
	public function staffInfoQuery() {
		$request = Request::instance ();
		echo $request->module () . '-' . $request->controller () . '-' . $request->action ();
		// return $this->fetch();
	}
	/*
	 * 人员基本信息管理
	 */
	public function staffInfoRecord() {
		return $this->fetch ();
	}
	/*
	 * 计生信息管理
	 */
	public function staffBrithControlRecord()
	{
		return $this->fetch();
	}
	/*
	 * 人员登录密码管理
	 */
	public  function  staffPasswordAdmin()
	{
		echo "11";
	}
	/*
	 * 个人登录密码管理
	 */
	public  function  staffSelfPasswordAdmin()
	{
		//echo "22";
		return $this->fetch();
	}
	/*
	 * 个人密码修改 
	 */
	public function changePassword()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postOldpass = $request->post( 'oldpass' );
		$postNewpass1 = $request->post ( 'newpass1' );
		$postNewpass2 = $request->post ( 'newpass2' );
		$userID = Session::get('userid');
		
		$userLogin = new UserLogin();
		if($userLogin->loginCheck($userID, $postOldpass)==1)
		{
			if($userLogin->passwordChange($userID,$postNewpass2))
			{
				return "yes";
			}
			else {
				return "insertError";
			}
		}
		else {
			return "passError";
		}
		//return  "yes";
		//echo $postOldpass.$postOldpass.$postNewpass1;
	}
	/*
	 * 人员信息查询
	 */
	public  function  staffInfoRecordSearch()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postDepartmentID = $request->post( 'departmentID' );
		$postSubsectionID = $request->post ( 'subsectionID' );
		$postTeamID = $request->post ( 'teamID' );
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
		
		$staffInfo =new StaffInfo();
		$selectStaffInfo = $staffInfo->getAll($postDepartmentID,$postSubsectionID,$postTeamID,$postUserName,$postUserID);
		//var_dump($selectStaffInfo);
		$selectStaffInfo =json_encode($selectStaffInfo,JSON_UNESCAPED_UNICODE);
// 		echo $postDepartmentID.$postSubsectionID.$postTeamID.$postUserName.$postUserID;
// 		return;
		//var_dump($selectStaffInfo);
		$this->assign('selectStaffInfo',$selectStaffInfo);
		return $this->fetch();
	}
	/*
	 * 人员信息删除
	 */
	public  function  staffInfoRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postStaffID = $request->post( 'staffID' );
		$staffInfo =new StaffInfo();
		$result = $staffInfo->deleteStaff($postStaffID);
		return $result;
	}
	/*
	 * 人员基本信息Excel表格导出
	*/
	public function exportStaffInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postDepartmentID = $request->get( 'departmentID' );
		$postSubsectionID = $request->get( 'subsectionID' );
		$postTeamID = $request->get( 'teamID' );
		$postUserName = $request->get( 'userName' );
		$postUserID = $request->get( 'userID' );
		//$dataResult = array();      //todo:导出数据（自行设置）
		$staffInfo = new StaffInfo();
		$dataResult = $staffInfo->exportAll($postDepartmentID,$postSubsectionID,$postTeamID,$postUserName,$postUserID);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "人员基本信息记录表";
		$title = "人员基本信息记录表";
		//$headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
		$titlename = "<tr>
               <th style='width:70px;'>员工编号</th>
               <th style='width:70px;'>姓名</th>
               <th style='width:70px;'>姓名缩写</th>
               <th style='width:150px;'>部门</th>
               <th style='width:70px;'>分部、中心站</th>
               <th style='width:100px;'>班组</th>
               <th style='width:70px;'>岗位</th>
               <th style='width:70px;'>岗位职级</th>
               <th style='width:70px;'>性别</th>
               <th style='width:70px;'>身份证</th>
               <th style='width:90px;'>出生年月</th>
               <th style='width:90px;'>入司日期</th>
               <th style='width:90px;'>现岗位起聘日期</th>
               <th style='width:90px;'>大学生信息</th>
               <th style='width:90px;'>班别</th>
               <th style='width:90px;'>是否不在岗</th>
               <th style='width:90px;'>本地常住地址</th>
               <th style='width:90px;'>常用手机号码</th>
               <th style='width:90px;'>婚姻情况</th>
               <th style='width:90px;'>籍贯</th>
               <th style='width:90px;'>政治面貌</th>
               <th style='width:90px;'>民族</th>
               <th style='width:90px;'>户籍性质</th>
               <th style='width:90px;'>户口所在地</th>
               <th style='width:90px;'>家乡地址</th>
               <th style='width:90px;'>学历</th>
               <th style='width:90px;'>毕业院校</th>
               <th style='width:90px;'>专业 </th>
               <th style='width:90px;'>短号</th>
               <th style='width:90px;'>紧急联系人</th>
               <th style='width:90px;'>紧急联系人电话</th>
           </tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headtitle,$filename);
	}
	/*
	 * 计生信息Excel表格导出
	*/
	public function exportBirthControlInfo() {
		//接收前台请求数据
		$request = Request::instance ();
		$postSubsection = $request->get( 'subsection' );
		$postUserName = $request->get( 'userName' );
		$postUserID = $request->get( 'userID' );
// 		echo $postSubsectionID.$postUserName.$postUserID;
// 		return;
		//$dataResult = array();      //todo:导出数据（自行设置）
// 		$staffInfo = new StaffInfo();
// 		$dataResult = $staffInfo->exportAll($postDepartmentID,$postSubsectionID,$postTeamID,$postUserName,$postUserID);
		$birthControlInfo = new BirthControlInfo();
		$dataResult =$birthControlInfo->exportAll($postSubsection, $postUserName, $postUserID);
		$dataResult = collection($dataResult)->toArray();//将select()查询结果集（对象），转为数组！！！
		$headTitle = "计生信息记录表";
		$title = "计生信息记录表";
		//$headtitle= "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>";
		$titlename = "<tr>
               <th style='width:70px;'>序号</th>
               <th style='width:70px;'>中心(不能为空)</th>
               <th style='width:70px;'>部门(不能为空)</th>
               <th style='width:150px;'>员工工作证号(不能为空)</th>
               <th style='width:70px;'>员工姓名(不能为空)</th>
               <th style='width:100px;'>性别(不能为空)</th>
               <th style='width:70px;'>出生年月日(不能为空)（必须为日期格式）</th>
               <th style='width:70px;'>民族(不能为空)</th>
               <th style='width:70px;'>籍贯(不能为空)</th>
               <th style='width:70px;'>是否独生子女（指本人）(不能为空)</th>
               <th style='width:90px;'>入司时间(不能为空)（必须为日期格式）</th>
               <th style='width:90px;'>用工性质(不能为空)</th>
               <th style='width:90px;'>职务(不能为空)</th>
               <th style='width:90px;'>身份证号(不能为空)</th>
               <th style='width:90px;'>户口性质(不能为空)</th>
               <th style='width:90px;'>流动人口婚育证明发证时间</th>
               <th style='width:90px;'>流动人口婚育证明到期时间</th>
               <th style='width:90px;'>户籍性质</th>
               <th style='width:90px;'>户籍地址</th>
               <th style='width:90px;'>现住址</th>
               <th style='width:90px;'>合同期</th>
               <th style='width:90px;'>联系电话</th>
               <th style='width:90px;'>婚姻状况(指本人)</th>
               <th style='width:90px;'>婚姻时间</th>
               <th style='width:90px;'>是否双职工</th>
               <th style='width:90px;'>配偶姓名</th>
               <th style='width:90px;'>民族</th>
               <th style='width:90px;'>出生年月日（必须为日期格式） </th>
               <th style='width:90px;'>身份证号</th>
               <th style='width:90px;'>户口性质</th>
               <th style='width:90px;'>户籍性质</th>
               <th style='width:90px;'>户籍地址</th>
               <th style='width:90px;'>工作单位</th>
               <th style='width:90px;'>节育措施</th>
               <th style='width:90px;'>是否独生子女（指配偶）</th>
               <th style='width:90px;'>联系电话</th>
               <th style='width:90px;'>子女个数</th>
               <th style='width:90px;'>子女姓名</th>
               <th style='width:90px;'>性别</th>
               <th style='width:90px;'>出生年月日</th>
               <th style='width:90px;'>子女属性</th>
               <th style='width:90px;'>计划内外</th>
               <th style='width:90px;'>生育证办证时间</th>
               <th style='width:90px;'>生育证编号</th>
               <th style='width:90px;'>办证部门</th>
               <th style='width:90px;'>是否办理独生子女证</th>
               <th style='width:90px;'>独生子女证办理时间</th>
               <th style='width:90px;'>独生子女证号</th>
               <th style='width:90px;'>发证单位</th>
               <th style='width:90px;'>修改内容</th>
               <th style='width:90px;'>修改月份</th>
           </tr>";
		$filename = $title.date('Y-m-d H:i:s').".xls";
		$this->excelData($dataResult,$titlename,$headTitle,$filename);
	}
	/*
	 * 计生信息Excel表格导入
	 */
	public function importStaffBirthControlInfo() {
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
			//创建数据库对象及问题数据数组
			$birthControlInfo = new BirthControlInfo();
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
					//出生年月日
					if($k[6]!=null)
					{
						$k[6] = date('Y-m-d', (($k[6]-25569) * 24*60*60));
					}
					//入司时间
					if($k[10]!=null)
					{
						$k[10] = date('Y-m-d', (($k[10]-25569) * 24*60*60));
					}
// 					//流动人口婚育证明发证时间,混合模式，遇到时间，转为字符串
// 					if($k[15]!=null)
// 					{
// 						if($temp = date('Y-m-d', (($k[10]-25569) * 24*60*60)))
// 						{
// 							$k[15]=$temp;
// 						}
// 					}
					//婚姻时间
					if($k[23]!=null)
					{
						$k[23] = date('Y-m-d', (($k[23]-25569) * 24*60*60));
					}
					//配偶出生年月日
					if($k[27]!=null)
					{
						$k[27] = date('Y-m-d', (($k[27]-25569) * 24*60*60));
					}
					//修改时间
					if($k[50]!=null)
					{
						$k[50] = date('Y-m-d', (($k[50]-25569) * 24*60*60));
					}
					//判断员工号是否重复
					if($birthControlInfo->isRepeatUserid($k[3]))
					{
						//如果存在，则更新
						if(!$birthControlInfo->updataNewStaff($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[3],'userName'=>$k[4]);
						}
						else {
							$updateCount++;
						}
					}
					else {
						//如果不存在，则新增
						if(!$birthControlInfo->insertNewStaff($k))
						{
							$faultCount++;
							$faultArray[]=array('userID'=>$k[3],'userName'=>$k[4]);
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
			$updataManagementInfonew->updataRcord('E002', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
			//return $this->fetch();
			//循环读取excel表格,读取一条,插入一条
			//j表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
			//$a表示列号
// 			for($count=2;$count<=$highestRow;$count++)
// 			{
// 				$a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();
// 				$b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();
// 				$c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();
// 				$d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();
// 				$e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();
// 				$f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();
// 				$h = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$i = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$j = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$k = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$l = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$m = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$n = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$o = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$p = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$q = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$r = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$s = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$t = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$u = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$v = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$w = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$x = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$y = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$z = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$aa = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ab = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ac = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ad = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ae = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$af = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ag = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ah = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ai = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$aj = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ak = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$al = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$am = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$an = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ao = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ap = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$aq = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$ar = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$as = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$at = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$au = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$av = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				$aw = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
// 				ECHO $a.$b.$c.$d.$e.$f.'</br>';
// 			}
			
		}
		
		
	}
	/*
	 * 计生信息查询
	*/
	public  function  birthControlInfoRecordSearch()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postSubsectionID = $request->post ( 'subsectionID' );
		$postUserName = $request->post ( 'userName' );
		$postUserID = $request->post ( 'userID' );
// 				echo $postDepartmentID.$postSubsectionID.$postTeamID.$postUserName.$postUserID;
// 				return;
		$BirthControlInfo =new BirthControlInfo();
		$selectBirthControlInfo = $BirthControlInfo->getAll($postSubsectionID,$postUserName,$postUserID);
// 		var_dump($selectBirthControlInfo);
// 		return;
		$selectBirthControlInfo =json_encode($selectBirthControlInfo,JSON_UNESCAPED_UNICODE);
		//var_dump($selectBirthControlInfo);
		$this->assign('selectBirthControlInfo',$selectBirthControlInfo);
		return $this->fetch();
	}
	/*
	 * 计生信息删除
	*/
	public  function  staffBirthControlRecordDelete()
	{
		// 获取POST数据
		$request = Request::instance ();
		$postStaffID = $request->post( 'staffID' );
		$BirthControlInfo =new BirthControlInfo();
		$result = $BirthControlInfo->deleteStaff($postStaffID);
		return $result;
	}
	
	
	/*
	 * 人员基本信息Excel表格导入
	 */
	public function importStaffInfo() {

		// 获取POST上传的EXCEL表格
		$request = Request::instance ();
		$postMyfile = $request->file ( 'myfile' );
		$postMyfileName = $request->post( 'filename' );
		// var_dump($postMyfile);
		
		// 判断是否选择了要上传的表格,如果超过10M，也返回null,在PHP.INI post_max_size = 10M
		if (empty ( $postMyfile )) {
// 			echo "<script>alert('您未选择表格');history.go(-1);</script>";
			return "noUpload";
			exit ();
		}
		
		// 获取表格的大小，限制上传表格的大小5M
		if (! $postMyfile->checkSize ( 5 * 1024 * 1024 )) {
// 			echo "<script>alert('上传失败，上传的表格不能超过5M的大小');history.go(-1);</script>";
			return "overMaxSize";
			exit ();
		}
		
		// 判断表格是否上传成功
		if (is_uploaded_file ( $postMyfile->getPathname () )) {
			$objPHPExcel = PHPExcel_IOFactory::load ( $postMyfile->getPathname ());
			//$sheet = $objPHPExcel->getSheet(0)->toArray();
			$sheet = $objPHPExcel->getSheet(0);  //  取得第一张表
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			//$highestcol = $sheet->getHighestColumn(1); // 取得总列数
			//var_dump($sheet);
			//echo  $highestRow.$highestcol;
			
			//创建数据库对象及问题数据数组
			$staffInfo = new StaffInfo();
			$departmentInfo = new DepartmentInfo();
			$subsectionInfo = new SubsectionInfo();
			$teamInfo = new TeamInfo();
			$postInfo = new PostInfo();
			$rankInfo = new RankInfo();
			$sexInfo = new SexInfo();
			$notOnTheJobInfo = new NotOnTheJobInfo();
			$maritalInfo = new MaritalInfo();
			$politicsInfo = new PoliticsInfo();
			$nationInfo = new NationInfo();
			$householdRegisterInfo = new HouseholdRegisterInfo();
			$educationInfo = new EducationInfo();
			$duplicationName  = array();
			$noDepartment = array();
			$noSubsection = array();
			$noTeam = array();
			$noPost = array();
			$noRank = array();
			$noSex = array();
			$noNotOnTheJob = array();
			$noMarital = array();
			$noPolitics = array();
			$noNation = array();
			$noHouseholdRegister =array();
			$noeducation =array();
			$errorData =array();
			$okCount=0;
			$faultCount = 0;
			$insertCount = 0;
			$updateCount = 0;
			//循环读取excel表格,读取一条,插入一条
			//j表示从哪一行开始读取  从第二行开始读取，因为第一行是标题不保存
			//$a表示列号
			for($j=2;$j<=$highestRow;$j++)
			{
				$a=null;$b=null;$c=null;$d=null;$e=null;$e1[0]=null;$f=null;$f1[0]=null;$g=null;$g1[0]=null;
				$h1[0]=null;$h=null;$i1[0]=null;$i=null;$jj=null;$jj1[0]=null;$k=null;$l=null;$m=null;$n=null;
				$o=null;$p=null;$q1[0]=null;$q=null;$r=null;$s=null;$t1[0]=null;$t=null;$u=null;$v1[0]=null;
				$v=null;$w1[0]=null;$w=null;$x1[0]=null;$x=null;$y=null;
				$z=null;$aa1[0]=null;$aa=null;$ab=null;$ac=null;$ad=null;$ae=null;$af=null;
	
				$a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();
				$b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();
				$c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();
				$d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();
				$e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();
				
				//根据部门信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
				$e1 = $departmentInfo->getIndexByName($e);
				//var_dump($e[0]);
				if($e1[0]==null)
				{
					$noDepartment[]=array('userName'=>$c,'department'=>$e);
					//此条不插入，进行下一条处理
					continue;
				}
				
				$f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();
				//根据分部、中心站信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
				$f1 = $subsectionInfo->getIndexByName($f);
				//var_dump($e[0]);
				if($f1[0]==null)
				{
					$noSubsection[]=array('userName'=>$c,'subsection'=>$f);
					//此条不插入，进行下一条处理
					continue;
				}
				
				$g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();
				//班组字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($g!=null)
				{
					//根据班组信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$g1 = $teamInfo->getIndexByName($g);
					//var_dump($e[0]);
					if($g1[0]==null)
					{
						$noTeam[]=array('userName'=>$c,'team'=>$g);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				
				$h = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();
				//岗位字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($h!=null)
				{
					//根据岗位信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$h1 = $postInfo->getIndexByName($h);
					//var_dump($e[0]);
					if($h1[0]==null)
					{
						$noPost[]=array('userName'=>$c,'post'=>$h);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				
				$i = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($i))  $i= $i->__toString();
				
				//岗位职级字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($i!=null)
				{
					//根据岗位职级信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$i1 = $rankInfo->getIndexByName($i);
					//var_dump($i1[0]);
					if($i1[0]==null)
					{
						$noRank[]=array('userName'=>$c,'rank'=>$i);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$jj = $objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($jj))  $jj= $jj->__toString();

				//性别字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($jj!=null)
				{
					//根据性别信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$jj1 = $sexInfo->getIndexByName($jj);
					//var_dump($i1[0]);
					if($jj1[0]==null)
					{
						$noSex[]=array('userName'=>$c,'sex'=>$jj);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$k = $objPHPExcel->getActiveSheet()->getCell("K".$j)->getValue();
				//生日字段，允许为空，不为空则时间需要转换
				$l = $objPHPExcel->getActiveSheet()->getCell("L".$j)->getValue();
				if($l!=null)
				{
					$l = date('Y-m-d', (($l-25569) * 24*60*60));
				}

				//入司时间字段，时间需要转换
				$m = $objPHPExcel->getActiveSheet()->getCell("M".$j)->getValue();
				if($m!=null)
				{
					$m = date('Y-m-d', (($m-25569) * 24*60*60));
				}

				//现岗位起聘日期字段，时间需要转换
				$n = $objPHPExcel->getActiveSheet()->getCell("N".$j)->getValue();
				if($n!=null)
				{
					$n = date('Y-m-d', (($n-25569) * 24*60*60));
				}
				$o = $objPHPExcel->getActiveSheet()->getCell("O".$j)->getValue();
				$p = $objPHPExcel->getActiveSheet()->getCell("P".$j)->getValue();
				$q = $objPHPExcel->getActiveSheet()->getCell("Q".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($q))  $q= $q->__toString();
				
				//不在岗字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($q!=null)
				{
					//根据不在岗信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$q1 = $notOnTheJobInfo->getIndexByName($q);
					//var_dump($i1[0]);
					if($q1[0]==null)
					{
						$noNotOnTheJob[]=array('userName'=>$c,'notOnTheJob'=>$q);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$r = $objPHPExcel->getActiveSheet()->getCell("R".$j)->getValue();
				$s = $objPHPExcel->getActiveSheet()->getCell("S".$j)->getValue();
				$t = $objPHPExcel->getActiveSheet()->getCell("T".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($t))  $t= $t->__toString();
				
				//婚姻情况字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($t!=null)
				{
					//根据婚姻情况信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$t1 = $maritalInfo->getIndexByName($t);
					//var_dump($i1[0]);
					if($t1[0]==null)
					{
						$noMarital[]=array('userName'=>$c,'marital'=>$t);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$u = $objPHPExcel->getActiveSheet()->getCell("U".$j)->getValue();
				$v = $objPHPExcel->getActiveSheet()->getCell("V".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($v))  $v= $v->__toString();
				
				//政治面貌字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($v!=null)
				{
					//根据政治面貌信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$v1 = $politicsInfo->getIndexByName($v);
					//var_dump($i1[0]);
					if($v1[0]==null)
					{
						$noPolitics[]=array('userName'=>$c,'politics'=>$v);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$w = $objPHPExcel->getActiveSheet()->getCell("W".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($w))  $w= $w->__toString();
				
				//政治面貌字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($w!=null)
				{
					//根据政治面貌信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$w1 = $nationInfo->getIndexByName($w);
					//var_dump($i1[0]);
					if($w1[0]==null)
					{
						$noNation[]=array('userName'=>$c,'nation'=>$w);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$x = $objPHPExcel->getActiveSheet()->getCell("X".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($x))  $x= $x->__toString();
				
				//政治面貌字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($x!=null)
				{
					//根据政治面貌信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$x1 = $householdRegisterInfo->getIndexByName($x);
					//var_dump($i1[0]);
					if($x1[0]==null)
					{
						$noHouseholdRegister[]=array('userName'=>$c,'householdRegister'=>$x);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$y = $objPHPExcel->getActiveSheet()->getCell("Y".$j)->getValue();
				$z = $objPHPExcel->getActiveSheet()->getCell("Z".$j)->getValue();
				$aa = $objPHPExcel->getActiveSheet()->getCell("AA".$j)->getValue();
				/******************************************************************/
				/*为防止读取EXCEL表格内格式为PHPExcel_RichText对象，使用以下语句转化格式，否则会报错。**/
				/******************************************************************/
				if(is_object($aa))  $aa= $aa->__toString();
				
				//政治面貌字段可以为空，如果为空，什么都不做，不为空再做判断。
				if($aa!=null)
				{
					//根据政治面貌信息，查询对应id,返回是数组，无数据返回null,将不符合的数据存入数组。
					$aa1 = $educationInfo->getIndexByName($aa);
					//var_dump($i1[0]);
					if($aa1[0]==null)
					{
						$noeducation[]=array('userName'=>$c,'education'=>$aa);
						//此条不插入，进行下一条处理
						continue;
					}
				}
				$ab = $objPHPExcel->getActiveSheet()->getCell("AB".$j)->getValue();
				$ac = $objPHPExcel->getActiveSheet()->getCell("AC".$j)->getValue();
				$ad = $objPHPExcel->getActiveSheet()->getCell("AD".$j)->getValue();
				$ae = $objPHPExcel->getActiveSheet()->getCell("AE".$j)->getValue();
				$af = $objPHPExcel->getActiveSheet()->getCell("AF".$j)->getValue();
				//echo $a.$b.$c.$d.$e.$f.$g.$h.$i.$jj.$k.$l.$m.$n.$o.$p.$q.$r.$s.$t.$u.$v.$w.$x.$y.$z.$aa.$ab.$ac.$ad.$ae.$af.'<br/>';
				//echo $a.$b.$c.$d.$e1[0].$f1[0].$g1[0].$h1[0].$i1[0].$jj1[0].$k.$l.$m.$n.$o.$p.$q1[0].$r.$s.$t1[0].$u.$v1[0].$w1[0].$x1[0].$y.$z.$aa1[0].$ab.$ac.$ad.$ae.$af.'<br/>';
				//echo "当前计数值：".$j."<br/>";
				//continue;
				//判断是否库里有对应员工号人员,如果存在，则更新；不存在，则新增
				
				
			//	$returnMessage = $staffInfo->insertNewStaff($a,$b,$c,$d,$e1[0],$f1[0],$g1[0],$h1[0],$i1[0],$jj1[0],$k,$l,$m,$n,$o,$p,$q1[0],$r,$s,$t1[0],$u,$v1[0],$w1[0],$x1[0],$y,$z,$aa1[0],$ab,$ac,$ad,$ae,$af);
			    //将经过处理的EXCEL数据导入数据库
				if($staffInfo->isRepeatUserid($b))
					{
						//记录重复姓名
						$duplicationName[]=array('userName'=>$c);
// 						echo  $c."已存在";
// 						echo $a.$b.$c.$d.$e1[0].$f1[0].$g1[0].$h1[0].$i1[0].$jj1[0].$k.$l.$m.$n.$o.$p.$q1[0].$r.$s.$t1[0].$u.$v1[0].$w1[0].$x1[0].$y.$z.$aa1[0].$ab.$ac.$ad.$ae.$af.'<br/>';
// 						//更新员工信息
						$returnMessage =$staffInfo->updataNewStaff($a,$b,$c,$d,$e1[0],$f1[0],$g1[0],$h1[0],$i1[0],$jj1[0],$k,$l,$m,$n,$o,$p,$q1[0],$r,$s,$t1[0],$u,$v1[0],$w1[0],$x1[0],$y,$z,$aa1[0],$ab,$ac,$ad,$ae,$af);
						if(!$returnMessage)
						{
// 							$this->assign('isSuccess',"新增成功！！");
// 							return $this->fetch();
// 							exit();
							//echo $c."更新成功"."<br/>";
							$errorData[]=array('userName'=>$c);
							$faultCount++;
							continue;
						}
						$okCount++;
						$updateCount++;
					}
					else {
						//新增员工信息
// 						echo $a.$b.$c.$d.$e1[0].$f1[0].$g1[0].$h1[0].$i1[0].$jj1[0].$k.$l.$m.$n.$o.$p.$q1[0].$r.$s.$t1[0].$u.$v1[0].$w1[0].$x1[0].$y.$z.$aa1[0].$ab.$ac.$ad.$ae.$af.'<br/>';
						$returnMessage = $staffInfo->insertNewStaff($a,$b,$c,$d,$e1[0],$f1[0],$g1[0],$h1[0],$i1[0],$jj1[0],$k,$l,$m,$n,$o,$p,$q1[0],$r,$s,$t1[0],$u,$v1[0],$w1[0],$x1[0],$y,$z,$aa1[0],$ab,$ac,$ad,$ae,$af);
						if(!$returnMessage)
						{
							//echo $c."新增成功"."<br/>";
// 							$this->assign('isSuccess',"新增成功！！");
// 							return $this->fetch();
							$errorData[]=array('userName'=>$c);
							$faultCount++;
							continue;
						}
						$okCount++;
						$insertCount++;
					}
			}
			$this->assign('isSuccess',"共".($highestRow-1)."条数据,成功导入".$okCount."条数据");
			$this->assign('noDepartment',$noDepartment);
			$this->assign('noSubsection',$noSubsection);
			$this->assign('noTeam',$noTeam);
			$this->assign('noPost',$noPost);
			$this->assign('noRank',$noRank);
			$this->assign('noSex',$noSex);
			$this->assign('noNotOnTheJob',$noNotOnTheJob);
			$this->assign('noMarital',$noMarital);
			$this->assign('noPolitics',$noPolitics);
			$this->assign('noNation',$noNation);
			$this->assign('noHouseholdRegister',$noHouseholdRegister);
			$this->assign('noeducation',$noeducation);
			$this->assign('duplicationName',$duplicationName);
			$this->assign('errorData',$errorData);
			//记录导入事件
			$updataManagementInfonew =new  UpdataManagementInfo();
			$StaffInfo = new StaffInfo;
			$result =  '共新增'.$insertCount.'条，共更新'.$updateCount.'条，共发现错误'.$faultCount.'条';
			$updataManagementInfonew->updataRcord('E001', $postMyfileName,$result,date('y-m-d H:i:s',time()),Session::get('userid'), $StaffInfo->getName(Session::get('userid')));
			return $this->fetch();
			//return $this->fetch();
		}
	}
	public function staffInfoBaseAdmin() {
		return $this->fetch ();
	}
	// 部门信息管理
	public function departmentAdmin() {
		$departmentInfo = new DepartmentInfo ();
		$selectDepartmentInfo = $departmentInfo->departmentInfoListSearch ();
		$selectDepartmentInfo = json_encode ( $selectDepartmentInfo, JSON_UNESCAPED_UNICODE );
		$this->assign ( 'selectDepartmentInfo', $selectDepartmentInfo );
		return $this->fetch ();
	}
	// 分部信息管理
	public function subsectionAdmin() {
		$subsectionInfo = new SubsectionInfo ();
		$selectSubsectionInfo = $subsectionInfo->subsectionInfoListSearch ();
		$selectSubsectionInfo = json_encode ( $selectSubsectionInfo, JSON_UNESCAPED_UNICODE );
		$this->assign ( 'selectSubsectionInfo', $selectSubsectionInfo );
		return $this->fetch ();
	}
	// 班组信息管理
	public function teamAdmin() {
		$teamInfo = new TeamInfo ();
		$selectTeamInfo = $teamInfo->teamInfoListSearch ();
		$selectTeamInfo = json_encode ( $selectTeamInfo, JSON_UNESCAPED_UNICODE );
		$this->assign ( 'selectTeamInfo', $selectTeamInfo );
		return $this->fetch ();
	}
	// 新增部门
	public function departmentAdd() {
		// 获取POST数据
		$request = Request::instance ();
		$postDepartmentName = $request->post ( 'departmentName' );
		$departmentInfo = new DepartmentInfo ();
		if ($departmentInfo->departmentAdd ( $postDepartmentName )) {
			return true;
		} else {
			return false;
		}
	}
	// 新增分部、中心站
	public function subsectionAdd() {
		// 获取POST数据
		$request = Request::instance ();
		$postSubsectionName = $request->post ( 'subsectionName' );
		$subsectionInfo = new SubsectionInfo ();
		if ($subsectionInfo->subsectionAdd ( $postSubsectionName )) {
			return true;
		} else {
			return false;
		}
	}
	// 新增班组
	public function teamAdd() {
		// 获取POST数据
		$request = Request::instance ();
		$postTeamName = $request->post ( 'teamName' );
		$teamInfo = new TeamInfo ();
		if ($teamInfo->teamAdd ( $postTeamName )) {
			return true;
		} else {
			return false;
		}
	}
	// 更新部门
	public function departmentEdit() {
		// 获取POST数据
		$request = Request::instance ();
		$postDepartmentName = $request->post ( 'departmentName' );
		$postDepartmentID = $request->post ( 'departmentID' );
		$departmentInfo = new DepartmentInfo ();
		if ($departmentInfo->departmentEdit ( $postDepartmentID, $postDepartmentName )) {
			return true;
		} else {
			return false;
		}
	}
	// 更新分部、中心站
	public function subsectionEdit() {
		// 获取POST数据
		$request = Request::instance ();
		$postSubsectionName = $request->post ( 'subsectionName' );
		$postSubsectionID = $request->post ( 'subsectionID' );
		$subsectionInfo = new SubsectionInfo ();
		if ($subsectionInfo->subsectionEdit ( $postSubsectionID, $postSubsectionName )) {
			return true;
		} else {
			return false;
		}
	}
	// 更新分部、中心站
	public function teamEdit() {
		// 获取POST数据
		$request = Request::instance ();
		$postTeamName = $request->post ( 'teamName' );
		$postTeamID = $request->post ( 'teamID' );
		$teamInfo = new TeamInfo ();
		if ($teamInfo->teamEdit ( $postTeamID, $postTeamName )) {
			return true;
		} else {
			return false;
		}
	}
	// 获取部门列表
	public function getDepartmentInfoList() {
		$departmentInfo = new DepartmentInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $departmentInfo->departmentInfoListSearch ();
	}
	// 获取分部列表
	public function getSubsectionInfoList() {
		$subsectionInfo = new SubsectionInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $subsectionInfo->subsectionInfoListSearch ();
	}
	// 获取班组列表
	public function getTeamInfoList() {
		$teamInfo = new TeamInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $teamInfo->teamInfoListSearch ();
	}
	// 获取岗位列表
	public function getPostInfoList() {
		$postInfo = new PostInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $postInfo->postInfoListSearch ();
	}
	// 获取班组列表
	public function getRankInfoList() {
		$rankInfo = new RankInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $rankInfo->rankInfoListSearch ();
	}
	// 获取性别列表
	public function getSexInfoList() {
		$sexInfo = new SexInfo ();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $sexInfo->sexInfoListSearch ();
	}
	// 获取是否不在岗列表
	public function getNotOnTheJobInfoList() {
		$notOnTheJobInfo = new NotOnTheJobInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $notOnTheJobInfo->notOnTheJobInfoListSearch ();
	}
	// 获取婚姻情况列表
	public function getMaritalInfoList() {
		$maritalInfo = new MaritalInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $maritalInfo->maritalInfoListSearch ();
	}
	// 获取政治面貌情况列表
	public function getPoliticsInfoList() {
		$politicsInfo = new PoliticsInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $politicsInfo->politicsInfoListSearch ();
	}
	// 获取民族列表
	public function getNationInfoList() {
		$nationInfo = new NationInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $nationInfo->nationInfoListSearch ();
	}
	// 获取户籍性质列表
	public function getHouseholdRegisterInfoList() {
		$householdRegisterInfo = new HouseholdRegisterInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $householdRegisterInfo->householdRegisterInfoListSearch ();
	}
	// 获取学历列表
	public function getEducationBackgroundInfoList() {
		$educationInfo = new EducationInfo();
		// return json_encode($departmentInfo->departmentInfoListSearch(),JSON_UNESCAPED_UNICODE);
		return $educationInfo->educationInfoListSearch ();
	}
	public function updataStaffInfo()
	{
		$request = Request::instance();
		$postStaffInfo = $request->post();//可接收全部POST值，并且以数组形式保存。
		 $staffInfo  = new StaffInfo();
		 if($staffInfo->updataNewStaffByForm($postStaffInfo))
		 {
		 	return true;
		 }
		 else
		 {
		 	return false;
		 }
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
				//锁定  身份证  列
				if($k=="staff_info_identity"||$k=="birth_control_info_employee_identity"||$k=="birth_control_info_spouse_identity"||$k=="birth_control_info_birth_certify_ID")
				{
					//将身份证列格式设置为文本
					$str .= "<td style='vnd.ms-excel.numberformat:@'>{$v}</td>";
				}
				else 
				{
					$str .= "<td>{$v}</td>";
				}
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