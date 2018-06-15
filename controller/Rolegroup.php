<?php
/*
 * 系统管理-角色组成员管理 界面控制。
 * 
 */
namespace app\index\controller;
use app\index\model\AuthGroup;
use app\index\model\StaffInfo;
use app\index\controller\CommAction;
use think\Request;
use app\index\model\AuthRule;
use Zend\Validator\InArray;
use app\index\model\AuthGroupAccess;
use app\index\model\UserLogin;
class Rolegroup extends CommAction {
	public function index() {
		return $this->fetch ();
	}
	public function saveRoleGroup() {
		//获取POST数据
		$request = Request::instance();
		$postUID = $request->post('uID');//员工ID
		$postTitleID = $request->post('titleID');//角色组ID
// 							<option value="0">禁用</option>
// 							<option value="1">启用</option>
// 							<option value="2">未定义</option>
		$postLoginStatusID = $request->post('loginStatusID');//登录权限状态
		if($postTitleID==0)//角色组不能不选择
		{
			return false;
		}
		$authGroupAccess = new AuthGroupAccess();
		//成员原来在角色组，更新就可以
		$count=$authGroupAccess->isInRoleGroupAccess($postUID);
		if($count==1)
		{
			if(!$authGroupAccess->saveRoleGroup($postUID, $postTitleID))
			{
				return false;
			}

		}
		//成员原来不在角色组，需要插入
		else if($count==0){
			if(!$authGroupAccess->insertRoleGroup($postUID, $postTitleID))
			{
				return false;
			}
		}
		else {
			return  false;
		}
		$userLogin  =  new  UserLogin();
		//判断用户是否存在
		//存在
		if($userLogin->userIsIn($postUID))
		{
			//未定义，将用户删除
			if($postLoginStatusID==2)
			{
				//删除用户
				if(!$userLogin->delUser($postUID))
				{
					return false;
				}
			}
			//禁用或启用都是改变原状态即可
			else if($postLoginStatusID==1||$postLoginStatusID==0){
				//更新用户登录权限
				if(!$userLogin->updataStatus($postUID, $postLoginStatusID))
				{
					return false;
				}
			}
		}
		//不存在
		else{
			//禁用或启用都是新建用户信息，密码默认123456
			if($postLoginStatusID==1||$postLoginStatusID==0){
				//新增用户
				if(!$userLogin->addUser($postUID, md5('123456'), $postLoginStatusID))
				{
					return false;
				}
			}
		}
		return true;
	}
	public function deleteRoleInGroup() {
		//获取POST数据
		$request = Request::instance();
		$postuDelID = $request->post('uDelID');
		$authGroupAccess = new AuthGroupAccess();
		if($authGroupAccess->deleteRoleInGroup($postuDelID))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function roleSearchByRole() {
		//获取POST数据
		$request = Request::instance();
		$postRoleName = $request->post('roleName');
		$authGroupAccess = new AuthGroupAccess();
		$searchByRole = $authGroupAccess->roleSearchByRole($postRoleName);
		//$searchByRole = collection($searchByRole)->toArray();//将select()查询数据转为数组格式
		//将select()查询数据转为JSON格式,参数JSON_UNESCAPED_UNICODEd的意思是转义中文，否则输出为十六进制
		$searchByRole =json_encode($searchByRole,JSON_UNESCAPED_UNICODE);
		$this->assign('searchByRole',$searchByRole);
		return $this->fetch();
	}
	public function roleSearchByUser() {
		//获取POST数据
		$request = Request::instance();
		$postDepartmentID = $request->post('departmentID');
		$postSubsectionID = $request->post('subsectionID');
		$postTeamID = $request->post('teamID');
		$staffInfo = new StaffInfo();
		$searchByUser = $staffInfo->roleSearchByUser($postDepartmentID,$postSubsectionID,$postTeamID);
		$searchByUser =json_encode($searchByUser,JSON_UNESCAPED_UNICODE);
		$this->assign('searchByUser',$searchByUser);
		//var_dump($searchByUser);
		return $this->fetch();
	}
}                 