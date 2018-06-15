<?php

namespace app\index\controller;
use app\index\model\AuthGroup;
use app\index\controller\CommAction;
use think\Request;
use app\index\model\AuthRule;
use Zend\Validator\InArray;
class Role extends CommAction {
	public function index() {
		return $this->fetch ();
	}
	public function roleSearch() {
		$authGroup = new AuthGroup ();
		$tables = $authGroup->roleListSearch ();
		$this->assign('roles',$tables);
		return $this->fetch ();
	}
	public function roleAdd() {
		$roleList = $this->roleListgCreat();
		$this->assign('roleList',$roleList);//生成权限列表，付给模板
		return $this->fetch ();
	}
	public function roleDelete() {
		//角色名称，从前台接收
		$request = Request::instance();
		$postroleName = $request->post('roleName');
		$authGroup = new AuthGroup ();
		if($authGroup->roleDelete($postroleName))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function roleEdit() {
		//角色名称，从前台接收
		$request = Request::instance();
		$postroleName = $request->post('roleName');
		//在数据库中查询对应名称的相关信息
		$authGroup = new AuthGroup ();
		$roleInfo = $authGroup->getRoleInfo($postroleName);
		if(!$roleInfo)
		{
			echo "数据库错误，请联系管理员！！！";
			return;
		}
		$roleArray =explode(',',$roleInfo[0]['rules']);
		$roleList = $this->roleListgCreat($roleArray);
		if($roleInfo[0]['status']==1)
		{
			$this->assign('statusChecked1',"checked");
		}
		else{
			$this->assign('statusChecked2',"checked");
		}
		$this->assign('roleList',$roleList);//生成权限列表，付给模板
		$this->assign('roleName',$postroleName);
		return $this->fetch();
	}
	//拼接角色列表
	public function roleListgCreat_backup()
	{
		$authRule=new AuthRule();
		$roleList = "";
		$roleList .="<ul>";
		//查询出一级菜单
		$fistNavs = $authRule->roleSearch('一级菜单');
		foreach($fistNavs as $fistNav){
// 			echo $fistNav['id'];
// 			echo $fistNav['name'];
			$roleList .="
						<li>
						<label class='checkbox inline'>
						<input type='checkbox' name='group[]' value='".$fistNav['id']."' /> ".$fistNav['name']."
						</label>
						   <ul>";
			   //查询出二级菜单
			   $secondNavs = $authRule->roleSearch($fistNav['name']);
			   foreach($secondNavs as $secondNav){
// 			   				echo $secondNav['id'];
// 			   				echo $secondNav['name'];
			   	$roleList .="
					   	<li>
					   	<label class='checkbox inline'>
					   	<input type='checkbox' name='nodeTwo[]' value='".$secondNav['id']."' /> ".$secondNav['name']."
					   	</label>
					   	<ul>";
					   	//查询出三级菜单
					   	$thirdNavs = $authRule->roleSearch($secondNav['name']);
					   	foreach($thirdNavs as $thirdNav){
					   		$roleList .="<li>
					   		<label class='checkbox inline'>
					   		<input type='checkbox' name='nodeThree[]' value='".$thirdNav['id']."' /> ".$thirdNav['name']."
					   		</label>";
					   		//查询三级菜单URL
					   		$thirdURLs = $authRule->roleSearch($thirdNav['name']);
					   		foreach($thirdURLs as $thirdURL){
					   			$roleList .="
						   		<label class='checkbox inline'>
						   		<input type='checkbox' name='nodeThree[]' value='".$thirdURL['id']."' /> ".$thirdURL['name']."
						   		</label>";
					   		}
					   	}
					   	$roleList .="</ul></li>";
			   }
			   $roleList .="</ul></li>"; 
		}
		$roleList .="</ul>";
		return $roleList;
		/* 参照格式
		 <ul>
			<li>
			<label class='checkbox inline'> 
				<input type='checkbox' name='group[]' value='1' /> 行政管理
			</label>
				<ul><!--分割-->
					<li>
					<label class='checkbox inline'>
					 	<input type='checkbox' name='nodeTwo[]' value='28' /> 人员信息管理
					</label>
						<ul><!--分割-->
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label><!--分割-->
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='18' /> 人员信息查询
								</label><!--分割-->
						</ul>
					</li>
				</ul>
			</li>
			<li>
			<label class='checkbox inline'> 
				<input type='checkbox' name='group[]' value='1' /> 行政管理
			</label>
				<ul>
					<li>
					<label class='checkbox inline'>
					 	<input type='checkbox' name='nodeTwo[]' value='28' /> 人员信息管理
					</label>
						<ul>
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='18' /> 人员信息查询
								</label>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
		*/
	}
	//拼接角色列表
	public function roleListgCreat($roleArray = 0)
	{
		$authRule=new AuthRule();
		$roleList = "";
		$roleList .="<ul>";
		//查询出一级菜单
		$fistNavs = $authRule->roleSearch('一级菜单');
		foreach($fistNavs as $fistNav){
// 			echo $fistNav['id'];
// 			echo $fistNav['name'];

			if($roleArray == 0)
			{
				$roleList .="
						<li>
						<label class='checkbox inline'>
						<input type='checkbox' name='group[]' value='".$fistNav['id']."' /> ".$fistNav['name']."
						</label>
						   <ul>";
			}
			else if(in_array($fistNav['id'],$roleArray))
			{
				$roleList .="
						<li>
						<label class='checkbox inline'>
						<input type='checkbox' name='group[]' value='".$fistNav['id']."' checked/> ".$fistNav['name']."
						</label>
						   <ul>";
			}
			else
			{
				$roleList .="
						<li>
						<label class='checkbox inline'>
						<input type='checkbox' name='group[]' value='".$fistNav['id']."' /> ".$fistNav['name']."
						</label>
						   <ul>";
			}

			   //查询出二级菜单
			   $secondNavs = $authRule->roleSearch($fistNav['name']);
			   foreach($secondNavs as $secondNav){
// 			   				echo $secondNav['id'];
// 			   				echo $secondNav['name'];
			   	if($roleArray == 0)
			   	{
			   	 
			   	$roleList .="
					   	<li>
					   	<label class='checkbox inline'>
					   	<input type='checkbox' name='nodeTwo[]' value='".$secondNav['id']."' /> ".$secondNav['name']."
					   	</label>
					   	<ul>";
			   	}
			   	else if(in_array($secondNav['id'],$roleArray))
			   	{
				   	 
			   	$roleList .="
					   	<li>
					   	<label class='checkbox inline'>
					   	<input type='checkbox' name='nodeTwo[]' value='".$secondNav['id']."' checked/> ".$secondNav['name']."
					   	</label>
					   	<ul>";
			   	}
			   	else
			   	{
			   	 
			   	$roleList .="
					   	<li>
					   	<label class='checkbox inline'>
					   	<input type='checkbox' name='nodeTwo[]' value='".$secondNav['id']."' /> ".$secondNav['name']."
					   	</label>
					   	<ul>";
			   	}

					   	//查询出三级菜单
					   	$thirdNavs = $authRule->roleSearch($secondNav['name']);
					   	foreach($thirdNavs as $thirdNav){
					   		
					   		if($roleArray == 0)
					   		{
					   		$roleList .="<li>
					   		<label class='checkbox inline'>
					   		<input type='checkbox' name='nodeThree[]' value='".$thirdNav['id']."' /> ".$thirdNav['name']."
					   		</label>";
					   		}
					   		else if(in_array($thirdNav['id'],$roleArray))
					   		{
					   			$roleList .="<li>
					   		<label class='checkbox inline'>
					   		<input type='checkbox' name='nodeThree[]' value='".$thirdNav['id']."'checked /> ".$thirdNav['name']."
					   		</label>";
					   		}
					   		else
					   		{
					   			$roleList .="<li>
					   		<label class='checkbox inline'>
					   		<input type='checkbox' name='nodeThree[]' value='".$thirdNav['id']."' /> ".$thirdNav['name']."
					   		</label>"; 		
					   		}
	
					   		//查询三级菜单URL
					   		$thirdURLs = $authRule->roleSearch($thirdNav['name']);
					   		foreach($thirdURLs as $thirdURL){
					   			if($roleArray == 0)
					   			{
					   			$roleList .="
						   		<label class='checkbox inline'>
						   		<input type='checkbox' name='nodeThree[]' value='".$thirdURL['id']."' /> ".$thirdURL['name']."
						   		</label>";
					   			}
					   			else if(in_array($thirdURL['id'],$roleArray))
					   			{
					   			$roleList .="
						   		<label class='checkbox inline'>
						   		<input type='checkbox' name='nodeThree[]' value='".$thirdURL['id']."'checked /> ".$thirdURL['name']."
						   		</label>";
					   			}
					   			else
					   			{
					   			$roleList .="
						   		<label class='checkbox inline'>
						   		<input type='checkbox' name='nodeThree[]' value='".$thirdURL['id']."' /> ".$thirdURL['name']."
						   		</label>";
					   			}

					   		}
					   	}
					   	$roleList .="</ul></li>";
			   }
			   $roleList .="</ul></li>"; 
		}
		$roleList .="</ul>";
		return $roleList;
		/* 参照格式
		 <ul>
			<li>
			<label class='checkbox inline'> 
				<input type='checkbox' name='group[]' value='1' /> 行政管理
			</label>
				<ul><!--分割-->
					<li>
					<label class='checkbox inline'>
					 	<input type='checkbox' name='nodeTwo[]' value='28' /> 人员信息管理
					</label>
						<ul><!--分割-->
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label><!--分割-->
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='18' /> 人员信息查询
								</label><!--分割-->
						</ul>
					</li>
				</ul>
			</li>
			<li>
			<label class='checkbox inline'> 
				<input type='checkbox' name='group[]' value='1' /> 行政管理
			</label>
				<ul>
					<li>
					<label class='checkbox inline'>
					 	<input type='checkbox' name='nodeTwo[]' value='28' /> 人员信息管理
					</label>
						<ul>
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='17' /> 人员信息录入
								</label>
							<li>
								<label class='checkbox inline'>
								<input type='checkbox' name='nodeThree[]' value='18' /> 人员信息查询
								</label>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
		*/
	}
	public function roleAddPage() {
		$request = Request::instance();
		$postroleName = $request->post('roleName');
		$poststatus = $request->post('status');
		$postgroup = $request->post('group/a');//末尾加a才能接收前台checkbox上传的数组数据
		$postnodeTwo = $request->post('nodeTwo/a');
		$postnodeThree = $request->post('nodeThree/a');
		
		if($postroleName==""||$postgroup==null ||$postnodeTwo==null||$postnodeThree==null)
		{
			//echo "选择不完整";
			$this->error("菜单选择不完整，请重新选择",'/index/Role/index');
		}
		//将上传的数组拼接为以","分割的字符串
		$rulesID=implode(",",$postgroup);
		$rulesID.=",".implode(",",$postnodeTwo);
		$rulesID.=",".implode(",",$postnodeThree);
        //var_dump($rules);
		$authRule=new AuthGroup();
		if(!$authRule->addNewRole($postroleName,$poststatus,$rulesID))
		{
			$this->error("新增失败",'/index/Role/index');
		}
		else{
			$this->success("新增成功",'/index/Role/index');
		}
	}
	public function roleEditPage() {
		$request = Request::instance();
		$postroleName = $request->post('roleName');
		$poststatus = $request->post('status');
		$postgroup = $request->post('group/a');
		$postnodeTwo = $request->post('nodeTwo/a');
		$postnodeThree = $request->post('nodeThree/a');
		
		if($postroleName==""||$postgroup==null ||$postnodeTwo==null||$postnodeThree==null)
		{
			//echo "选择不完整";
			$this->error("菜单选择不完整，请重新选择",'/index/Role/index');
		}
		//将上传的数组拼接为以","分割的字符串
		$rulesID=implode(",",$postgroup);
		$rulesID.=",".implode(",",$postnodeTwo);
		$rulesID.=",".implode(",",$postnodeThree);
        //var_dump($rules);
		$authRule=new AuthGroup();
		if(!$authRule->editRole($postroleName,$poststatus,$rulesID))
		{
			$this->error("编辑失败",'/index/Role/index');
		}
		else{
			$this->success("编辑成功",'/index/Role/index');
		}
	}
	
	
	public function getRoleList() {
		$authGroup = new AuthGroup ();
		return $authGroup->roleListSearch ();
	}
}                   