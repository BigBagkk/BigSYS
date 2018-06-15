<?php
namespace app\index\controller;

use app\index\controller\CommAction;

use think\session;

use think\Request; 

use app\index\model\StaffInfo;

class Index  extends CommAction
{
		public function index()         
		    {        
		    	session('PHPSESSID', cookie('PHPSESSID'));//登陆成功后，首先记录SESSIONID

		    	$navigation = $this->navigationCreat();//生成一级菜单，付给模板
		    	$BUIConfig =  $this->BUIConfigCreat();//生成二三级菜单，付给模板
	
		    	$StaffInfo = new StaffInfo;
		    	$this->assign('userName',$StaffInfo->getName(Session::get('userid')));//将用户名赋值给模板
		    	$this->assign('userID',Session::get('userid'));//将用户ID赋值给模板,用于修改密码
		    	$this->assign('userAuth',$this->getAuthGroupName(Session::get('userid')));//将权限名赋值给模板
		    	$this->assign('navigation',$navigation);//生成一级菜单，付给模板
		    	$this->assign('config',$BUIConfig);//生成二三级菜单，付给模板
		    	
		    	return $this->fetch();

// 		    	$auth=new Auth();
		    	
// 		    	$authList = array();
// 		    	$grouplist = array();
		    	
// 		    	var_dump(Session::get('userid'));
// 		    	$authList = $auth->getAuthList(Session::get('userid'));
// 		    	//$grouplist = $auth->getGroups(Session::get('userid'));
// 		    	var_dump($authList);
		    }
		    
		/*
		 * 获取权限组名称
		 */
		public  function getAuthGroupName($uid)
		{
			$auth=new Auth();
			return $auth->getAuthGroupName($uid);
		}
		    /*
		     * 一级菜单创建
		    */
		public function navigationCreat()
		    {   
		    	$auth=new Auth();
		    	$authList = $auth->getNavigationList(Session::get('userid'),'一级菜单');
		    	$navigation="";
		    	foreach($authList as $data){
		    		$navigation.="<li class='nav-item dl-selected'><div class='nav-item-inner nav-home'>".$data."</div></li>";
		    	};
				return $navigation;
		    } 
		
		    /*
		     * 二三级菜单创建
		     */
		public function BUIConfigCreat()
			{
				$auth=new Auth();
				$userid = Session::get('userid');
				$fistNavs = $auth->getNavigationList($userid,'一级菜单');
				$BUIConfig="var config = [";
				$i = 1;
				//var_dump($fistNavs);
				foreach($fistNavs as $fistNav){
					//var_dump($fistNav);
					$BUIConfig .="{id:'".$i."',homePage : '1',";
					$BUIConfig .="menu:[";
					$secondNavs = $auth->getNavigationList($userid,$fistNav);
					
					//var_dump($secondNavs);
					$j = 1;
					foreach($secondNavs as $secondNav){
						
						$BUIConfig .="{text:'".$secondNav."',items:[";
						$thirdNavs = $auth->getNavigationList($userid,$secondNav);

						////$j = 1;
						//var_dump($thirdNavs);
						foreach($thirdNavs as $thirdNav){

							$href = $auth->getNavigationList($userid,$thirdNav);
							//var_dump($href[0]);
							//exit();
							$href_str =$href[0];
							$BUIConfig .="{id:'".$j."',text:'".$thirdNav."',href:'".$href_str."'},";
							$j++;
						};
						////$j = 1;
						//去除最后一个逗号
						$BUIConfig = rtrim($BUIConfig, ',');
						$BUIConfig .="]},";
					};
					$i++;
					//去除最后一个逗号
					$BUIConfig = rtrim($BUIConfig, ',');
					$BUIConfig .="]},";
				};
				$BUIConfig = rtrim($BUIConfig, ',');
				$BUIConfig .="];";
				return $BUIConfig;
/*				
// 				return $BUIConfig ="
// 				var config = [
// 		        {id:'1',homePage : '1',
// 		           	menu:[
// 		           	{text:'系统管理',items:[
// 		   				        	{id:'1',text:'机构管理',href:'../Node/index'},
// 		  				        	{id:'2',text:'角色管理',href:'../Role/index'},
// 		  				        	{id:'3',text:'用户管理',href:'../User/index'},
// 		 				        	{id:'4',text:'菜单管理',href:'../Menu/index'}
// 		           	]}
// 		        ]},
// 		        {id:'2',homePage : '1',
// 		        	menu:[
// 		        	{text:'人员信息管理',items:[
// 							        	{id:'1',text:'人员信息查询',href:'/index/Staff/staffInfoQuery'},
// 							        	{id:'2',text:'个人信息录入',href:'/index/Staff/staffInfoRecord'},
// 							        	{id:'3',text:'持证信息录入',href:'../Staff/index'},
// 							        	{id:'4',text:'计生信息录入',href:'../Staff/index'},
// 							        	{id:'5',text:'公里数录入',href:'../Staff/index'}
// 							        	]},
// 		        	{text:'考评信息管理',items:[
// 		        	                	{id:'1',text:'查询业务',href:'../Assessment/index'},
// 		        	                	{id:'2',text:'角色管理',href:'../Assessment/index'}
// 		        	                	]},
// 		           	{text:'工时信息管理',items:[
// 		           	                	{id:'1',text:'查询业务',href:'../Node/index'},
// 		           	                	{id:'2',text:'角色管理',href:'../Role/index'}
// 		           	                	]},
// 		           	{text:'奖惩信息管理',items:[
// 		           	                	{id:'1',text:'查询业务',href:'../Node/index'},
// 		           	                	{id:'2',text:'角色管理',href:'../Role/index'}
// 		           	                	]}
// 		        ]},
// 		        {id:'3',homePage : '1',
// 		        	menu:[{text:'安全管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]},
// 		        {id:'4',homePage : '1',
// 		        	menu:[{text:'生产管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]},
// 		        {id:'5',homePage : '1',
// 		        	menu:[{text:'技术管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]},
// 		        {id:'6',homePage : '1',
// 		        	menu:[{text:'票务管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]},
// 		        {id:'7',homePage : '1',
// 		        	menu:[{text:'服务管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]},
// 		        {id:'8',homePage : '1',
// 		        	menu:[{text:'培训管理',
// 		        	items:[
// 		        	{id:'1',text:'查询业务',href:'../Node/index.html'},
// 		        	{id:'2',text:'角色管理',href:'../Role/index'}
// 		        	]}]}
// 		        ];
// 				";
 */
			}                           
		  
}           