<?php
namespace app\index\model;

class UserLogin extends  \think\Model
{
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_user_login';
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
			'debug' => false,
			];
	//检查用户是否存在
	public function userIsIn($userID){
		//echo $userName.$passWord;
		$count = $this
		->where('user_login_name', $userID)
		->count();
		
		if($count==1)
		{
			return true;
		}
		else{
			return false;
		}
	}
	//用户名密码校对
	public function loginCheck($userName,$passWord)
	{
		//echo $userName.$passWord;
		$count = $this
		->where('user_login_name', $userName)
		->where('user_login_password',$passWord)
		->count();
		return $count;
	}
	//用户状态检查，返回1为正常，返回0为被禁用
	public function statusCheck($userName)
	{
		return $this->where('user_login_name',$userName)->column ( 'user_login_status' )[0];
	}
	//记录登陆时间、IP和登陆状态
	public function loginRecord($userName,$loginTime,$loginIP,$loginStatus)
	{
		return $this->save([
				'user_login_logintime' => $loginTime,
				'user_login_loginip' => $loginIP,
				'user_login_status' => $loginStatus
				],['user_login_name' => $userName]);
	}
	/*
	 * 修改更新密码
	 */
	public function passwordChange($userID,$postNewpass)
	{
		try {
			$this->save([
					'user_login_password' => $postNewpass,
					],['user_login_name' => $userID]);
			
		} catch (\Exception $e) {
			return false;
		}
		return true;
	}
	/*
	 * 删除用户
	 */
	public function delUser($userID){
		try {
			$this->where ( 'user_login_name', $userID )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 更新用户登录权限
	*/
	public function updataStatus($userID,$status){
		try {
			$this->save ( 
					['user_login_status' => $status],
					['user_login_name' => $userID] 
			);
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 新增用户
	*/
	public function addUser($userID,$password,$status){
		$this->data ( [
				'user_login_name' => $userID,
				'user_login_password' => $password,
				'user_login_status' =>$status
				] );
		try {
			$this->save ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
