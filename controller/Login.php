<?php
namespace app\index\controller;

use app\index\model\UserLogin;
use think\Session;

class Login extends \think\Controller
{
	public function login()
	{
		return $this->fetch();
	}
	public function  loginCheck()
	{
		//控制访问
		if(!isset($_GET['userName'])||!isset($_GET['passWord']))
		{
			$this->error("不允许直接访问！！！",'/');
		}
		//防止SQL注入漏洞
		$userName = addslashes($_GET['userName']);
		$passWord =addslashes($_GET['passWord']);

		$user = new UserLogin;
		//判断申请登录人员是否存在，且为1个。
		if($user->loginCheck($userName, $passWord) ==1)
		{
			//判断申请登录人员是否被禁用，返回1为正常 返回0为被禁用
			if($user->statusCheck($userName)==1)
			{
				//登陆成功，服务器端记录session信息，php.ini控制，session.save_path = "d:/wamp/tmp"，还需要定时清理session，在php.ini中设置。
				Session::set('logintime', time());
				Session::set('userid', $userName);
					
				//框架内使用PHP扩展类需要加\
				\SeasLog::info("用户登录 ID：".$userName." 用户登录 IP：".$_SERVER['REMOTE_ADDR']);
				//记录登陆时间、IP和登陆状态
				if(false !==$user->loginRecord($userName, date("Y-m-d H:i:s",time()),$_SERVER['REMOTE_ADDR'], '1'))
				{
					return "yes";
				}
				else {
					\SeasLog::error("用户登录 ID：".$userName."DBError->loginRecord 数据库异常。");
					return "DBError";
				}
			}
			else
			{
				return "statusOFF";
			}

		}
		else 
		{
			return "no";
		}

	}
	/*
	 * 签退方法
	 * 清空session
	 * 退回登陆界面
	 */
	public function  signOut()
	{
		Session::start();
		Session::destroy();
		$this->redirect('/index');
	}
}