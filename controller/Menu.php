<?php

namespace app\index\controller;

use app\index\controller\CommAction;
use app\index\model\AuthRule;
use think\session;
use think\Request;

class Menu extends CommAction {
	public function index() {
		return $this->fetch ();
	}
	public function firstNavsManagement() {
		return $this->fetch ();
	}
	public function secondNavsManagement() {
		return $this->fetch ();
	}
	public function thirdNavsManagement() {
		return $this->fetch ();
	}
	/*
	 * 新增一级菜单 @return array
	*/
	public function addFirstNav()
	{
		//获取POST数据
		$request = Request::instance();
		$postFirstNav = $request->post('firstNav');
// 		echo $postFirstNav;
// 		return;
		$authRule = new AuthRule;
		$ret = $authRule->addFirstNav($postFirstNav);
		if($ret){
			return "新增成功！！";
		}
		else {
			return "新增失败！！";
		}
	}
	/*
	 * 新增二级菜单 @return array
	*/
	public function addSecondNav()
	{
		//获取POST数据
		$request = Request::instance();
		$postFirstNav = $request->post('firstNav');
		$postaddSecondNav = $request->post('addSecondNav');
		$authRule = new AuthRule;
		$ret = $authRule->addSecondNav($postFirstNav,$postaddSecondNav);
		if($ret){
			return "新增成功！！";
		}
		else {
			return "新增失败！！";
		}
	}
	/*
	 * 新增三级菜单 @return array
	*/
	public function addThirdNav()
	{
		//获取POST数据
		$request = Request::instance();
		$postSecondNav = $request->post('secondNav');
		$postThirdNav = $request->post('thirdNav');
		$postThirdURL = $request->post('thirdURL');
		$authRule = new AuthRule;
		$ret = $authRule->addThirdNav($postSecondNav,$postThirdNav,$postThirdURL);
		if($ret){
			return "新增成功！！";
		}
		else {
			return "新增失败！！";
		}
	}
	/*
	 * 修改一级菜单查询 @return array
	*/
	public function changFirstNav()
	{
		//获取POST数据
		$request = Request::instance();
		$oldpostFirstNav = $request->post('oldFirstNav');
		$newpostFirstNav = $request->post('newFirstNav');
		$authRule = new AuthRule;
		$ret = $authRule->changFirstNav($oldpostFirstNav,$newpostFirstNav);
		if($ret){
			return "修改成功！！";
		}
		else {
			return "修改失败！！";
		}
	}
	/*
	 * 修改二级菜单查询 @return array
	*/
	public function changSecondNav()
	{
		//获取POST数据
		$request = Request::instance();
		$oldpostSecondNav = $request->post('oldSecondNav');
		$newpostSecondNav = $request->post('newSecondNav');
		$authRule = new AuthRule;
		$ret = $authRule->changSecondNav($oldpostSecondNav,$newpostSecondNav);
		if($ret){
			return "修改成功！！";
		}
		else {
			return "修改失败！！";
		}
	}
	/*
	 * 修改三级菜单查询 @return array
	*/
	public function changThirdNav()
	{
		//获取POST数据
		$request = Request::instance();
		$postOldThirdNav = $request->post('oldThirdNav');
		$postNewThirdNav = $request->post('thirdNav');
		$postNewThirdURL = $request->post('thirdURL');
		$authRule = new AuthRule;
		$ret = $authRule->changThirdNav($postOldThirdNav,$postNewThirdNav,$postNewThirdURL);
		if($ret){
			return "修改成功！！";
		}
		else {
			return "修改失败！！";
		}
	}
	/*
	 * 删除一级菜单 @return array
	*/
	public function removeFirstNav()
	{
		//获取POST数据
		$request = Request::instance();
		$deleteFirstNav = $request->post('deleteFirstNav');
		$authRule = new AuthRule;
		$ret = $authRule->deleteNav($deleteFirstNav);
		if($ret){
			return "删除成功！！";
		}
		else {
			return "删除失败！！";
		}
	}
	/*
	 * 删除二级菜单 @return array
	*/
	public function removeSecondNav()
	{
		//获取POST数据
		$request = Request::instance();
		$deleteSecondNav = $request->post('deleteSecondNav');
		$authRule = new AuthRule;
		$ret = $authRule->deleteNav($deleteSecondNav);
		if($ret){
			return "删除成功！！";
		}
		else {
			return "删除失败！！";
		}
	}
	/*
	 * 删除三级菜单 @return array
	*/
	public function removeThirdNav()
	{
		//获取POST数据
		$request = Request::instance();
		$deleteThirdNav = $request->post('thirdNav');
		$authRule = new AuthRule;
		$ret = $authRule->deleteNav($deleteThirdNav);
		$ret = $authRule->deleteURL($deleteThirdNav);
		if($ret){
			return "删除成功！！";
		}
		else {
			return "删除失败！！";
		}
	}

	/*
	 * 全部菜单查询展示 @return array
	 */
	public function navsSearch() {
		//创建一个二维数组，以便模板使用volist遍历。
		$tables = array();
		$table = array();
		//查询一级菜单
		$authRules = new AuthRule ();
		//获取POST数据
		$request = Request::instance();
		$postFirstNav = $request->post('firstNav');
		//echo $postFirstNav;
		if($postFirstNav =='0')
		{
			$fistNavs = $authRules->navsSearch ( '一级菜单' );
		}
		else
		{
			$fistNavs [] = $postFirstNav;
		}
		//遍历一级菜单，作为二级菜单条件
		//查询对应二级菜单,拼接为数组
		foreach ($fistNavs as $key => $fistNav)
		{
			$secondNavs  = $authRules->navsSearch ($fistNav);
			if(empty($secondNavs)){
				unset($table);
				$table['first']=$fistNav;
				$tables []= $table;
				continue;
			}
			foreach ($secondNavs as $key => $secondNav)
			{
				$thirdNavs  = $authRules->navsSearch ($secondNav);
				if(empty($thirdNavs)){
					unset($table);
					$table['first']=$fistNav;
					$table['second']=$secondNav;
					$tables []= $table;
					continue;
				}
				foreach ($thirdNavs as $key => $thirdNav)
				{
					$urlNavs  = $authRules->navsSearch ($thirdNav);
					if(empty($urlNavs)){
						unset($table);
						$table['first']=$fistNav;
						$table['second']=$secondNav;
						$table['third']=$thirdNav;
						$tables []= $table;
						continue;
					}
					foreach ($urlNavs as $key => $urlNav){
						unset($table);
						$table['first']=$fistNav;
						$table['second']=$secondNav;
						$table['third']=$thirdNav;
						$table['url']=$urlNav;
						$tables []= $table;
					}
				}
			}
		}
// 		var_dump($tables);
		$this->assign('navs',$tables);
		return $this->fetch ();
		
	}
	/*
	 * 一级菜单查询 @return array
	 */
	public function getFirstNavs() {
		$authRules = new AuthRule ();
		return $authRules->navsSearch ( '一级菜单' );
	}
	/*
	 *二级菜单查询 @return array
	 */
	public function getSecondNavs() {
		//获取POST数据
		$request = Request::instance();
		$firstNav = $request->post('FirstNav');
		$authRules = new AuthRule ();
		return $authRules->navsSearch ($firstNav);
// 		var_dump($authRules->navsSearch ($firstNav));
	}
	/*
	 *三级菜单查询 @return array
	 */
	public function getThirdNavs() {
		//获取POST数据
		$request = Request::instance();
		$secondNav = $request->post('secondNav');
		$authRules = new AuthRule ();
		//创建数组
		$ret = array();
		//查询出二级菜单对应的全部三级菜单
		$thirdNavs =  $authRules->navsSearch ($secondNav);
		//var_dump($thirdNavs);
		//遍历三级菜单
		foreach ( $thirdNavs as $key => $thirdNav ) {
			//根据每个三级菜单查询对应的URL，一对一的关系
			$thirdURL = $authRules->navsSearch ($thirdNav);
			//组成三维数组
			$ret[]=array(0 => $thirdNav,1 => $thirdURL[0]);
		}
		return $ret;
		//var_dump($ret);
		/* 返回格式
		 array (size=3)
		  0 => 
		    array (size=1)
		      '人员信息录入' => 
		        array (size=1)
		          0 => string '/index/Staff/staffInfoRecord' (length=28)
		 */
	}
	/*
	 * 二级菜单查询 @param $title 一级菜单 @return array
	 */
	public function getSencondNavs($title = NULL) {
		if ($title) {
			$authRules = new AuthRule ();
			return $authRules->navsSearch ( $title );
		} else {
			$firstNavs = $this->getFirstNavs (); // 获取一级菜单
			$authRules = new AuthRule ();
			$secondNavs = array();
			foreach ( $firstNavs as $key => $firstNav ) {
				// echo $FirstNav['name'];
				$secondNavs [] = $authRules->navsSearch ( $firstNav );
			}
			return $secondNavs;//二维数组
		}
	}
}                   