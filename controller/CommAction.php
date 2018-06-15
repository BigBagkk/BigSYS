<?php
namespace app\index\controller;

use think\Session;

use app\index\controller\Auth;

use think\Request;

class CommAction extends \think\Controller
{
    public function __construct()
    {
        parent::__construct();//调用父类的构造函数
        $this->checkAdminSession();//在此基础上，运行自己的检测代码
        $this->checkAuth();
    }
    /*
     * 做为父类中的函数，子类继承后直接自动执行，不需要在子类中使用parent::__construct();方法
     * 每一个Action都需要校验Auth
     */
    public function checkAuth()
    {
		$auth = new Auth ();
		$request = Request::instance();
		//规则 格式  "/index/Menu/index"
		$rule_name =  '/'.$request->module() . '/' . $request->controller() . '/' . $request->action();
// 		echo $rule_name;
		$rule_name = strtolower($rule_name);//转为小写匹配
		//var_dump($auth->getNotAuthAction());
		if ($auth->isAuthOn() &&!in_array($rule_name,$auth->getNotAuthAction())) {
			$result = $auth->check ($rule_name,Session::get('userid'));
			if (!$result) {
				$this->error ( '您没有权限访问' ,'/index');
			}
		}
    }
    public function checkAdminSession()
    {  	
        $nowtime = time();      
        $s_time = Session::get('logintime');
        // 判断是否同一用户(待完善，同一浏览器登陆问题，只能保存最后session)

        // 设置超时为10分，如超时，跳转后主页面
        if (($nowtime - $s_time) > 600) {
            Session::delete('logintime');
// 			$this->redirect('/');//存在问题，局部页面跳转
			echo "<script>parent.location.replace('/');</script> ";//解决问题，整个页面跳转
        } else {
        	//如果未超时，修改session文件中logintime的值，重新设定计算起点
            session::set('logintime',$nowtime);
        }
    }
}