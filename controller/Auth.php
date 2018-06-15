<?php
namespace app\index\controller;//适应TP5添加语句
use think\Config;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luofei614 <weibo.com/luofei614>　
// +----------------------------------------------------------------------
/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and') 
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 * 
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100  表示用户的分数在5-100之间时这条规则才会通过。
 * @category ORG
 * @package ORG
 * @subpackage Util
 * @author luofei614<weibo.com/luofei614>
 */
//数据库
/*
-- ----------------------------
-- think_auth_rule，规则表，
-- id:主键，name：规则唯一标识, title：规则中文名称 status 状态：为1正常，为0禁用，condition：规则表达式，为空表示存在就验证，不为空表示按照条件验证
-- ----------------------------
 DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (  
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,  
    `name` char(80) NOT NULL DEFAULT '',  
    `title` char(20) NOT NULL DEFAULT '',  
    `status` tinyint(1) NOT NULL DEFAULT '1',  
    `condition` char(100) NOT NULL DEFAULT '',  
    PRIMARY KEY (`id`),  
    UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- think_auth_group 用户组表， 
-- id：主键， title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
-- ----------------------------
 DROP TABLE IF EXISTS `think_auth_group`;
CREATE TABLE `think_auth_group` ( 
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, 
    `title` char(100) NOT NULL DEFAULT '', 
    `status` tinyint(1) NOT NULL DEFAULT '1', 
    `rules` char(80) NOT NULL DEFAULT '', 
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- think_auth_group_access 用户组明细表
-- uid:用户id，group_id：用户组id
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group_access`;
CREATE TABLE `think_auth_group_access` (  
    `uid` mediumint(8) unsigned NOT NULL,  
    `group_id` mediumint(8) unsigned NOT NULL, 
    UNIQUE KEY `uid_group_id` (`uid`,`group_id`),  
    KEY `uid` (`uid`), 
    KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 */
class Auth{
    //默认配置
    protected $_config = array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'bigsys_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'bigsys_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'bigsys_auth_rule', //权限规则表
        'AUTH_USER' => 'bigsys_user_login',//用户信息表
       	'NOT_AUTH_ACTION' => array(	       //不需检测权限列表,全部录入小写！！
       		'/index/index/index',
       		'/index/menu/getfirstnavs',
       		'/index/menu/firstnavsmanagement',
       		'/index/menu/getsencondnavs',
       		'/index/menu/navssearch',
       		'/index/menu/addfirstnav',
       		'/index/menu/changfirstnav',
       		'/index/menu/removefirstnav',
       		'/index/menu/secondnavsmanagement',
       		'/index/menu/thirdnavsmanagement',
       		'/index/menu/addsecondnav',
       		'/index/menu/getsecondnavs',
       		'/index/menu/changsecondnav',
       		'/index/menu/removesecondnav',
       		'/index/menu/addthirdnav',
       		'/index/menu/getthirdnavs',
       		'/index/menu/changthirdnav',
       		'/index/menu/removethirdnav',
       		'/index/role/getrolelist',
       		'/index/role/rolesearch',
       		'/index/role/roleadd',
       		'/index/role/roleaddpage',
       		'/index/role/roleedit',
       		'/index/role/roleeditpage',
       		'/index/role/roledelete',
       		'/index/rolegroup/rolesearchbyrole',
       		'/index/rolegroup/saverolegroup',
       		'/index/rolegroup/deleteroleingroup',
       		'/index/staff/getdepartmentinfolist',
       		'/index/staff/getsubsectioninfolist',
       		'/index/staff/getteaminfolist',
       		'/index/rolegroup/rolesearchbyuser',
       		'/index/staff/departmentadmin',
       		'/index/staff/departmentadd',
       		'/index/staff/departmentedit',
       		'/index/staff/subsectionadmin',
       		'/index/staff/subsectionedit',
       		'/index/staff/subsectionadd',
       		'/index/staff/teamadmin',
       		'/index/staff/teamadd',
       		'/index/staff/teamedit',
       		'/index/staff/importstaffinfo',
       		'/index/staff/staffinforecordsearch',
       		'/index/staff/staffinforecorddelete',
       		'/index/staff/getpostinfolist',
       		'/index/staff/getrankinfolist',
       		'/index/staff/getnotonthejobinfolist',
       		'/index/staff/getmaritalinfolist',
       		'/index/staff/getpoliticsinfolist',
       		'/index/staff/getnationinfolist',
       		'/index/staff/gethouseholdregisterinfolist',
       		'/index/staff/geteducationbackgroundinfolist',
       		'/index/staff/getsexinfolist',
       		'/index/staff/updatastaffinfo',
       		'/index/staff/exportstaffinfo',
       		'/index/staff/changepassword',
       		'/index/staff/importstaffbirthcontrolinfo',
       		'/index/staff/birthcontrolinforecordsearch',
       		'/index/staff/staffbirthcontrolrecorddelete',
       		'/index/staff/exportbirthcontrolinfo',
       		'/index/rewardandpunishmen/import',
       		'/index/rewardandpunishmen/getrecordidlist',
       		'/index/rewardandpunishmen/getpropertieslist',
       		'/index/rewardandpunishmen/getkindlist',
       		'/index/rewardandpunishmen/rewardandpunishmeninforecordsearch',
       		'/index/rewardandpunishmen/rewardandpunishmeninforecorddelete',
       		'/index/rewardandpunishmen/exportrewardandpunishmeninfo',
       		'/index/manhour/importworkhourinfo',
       		'/index/manhour/importdrivehourinfo',
       		'/index/evaluate/importselfinforecord',
       		'/index/evaluate/importcollectiveinforecord',
       		'/index/manhour/getworkhourdepartmentlist',
       		'/index/manhour/getworkhourteamlist',
       		'/index/manhour/workhourinforecordsearch',
       		'/index/manhour/workhourinforecorddelete',
       		'/index/manhour/exportworkhourinfo',
       		'/index/manhour/drivehourinforecordsearch',
       		'/index/manhour/getdrivehourlinelist',
       		'/index/manhour/exportdrivehourinfo',
       		'/index/manhour/drivehourinforecorddelete',
       		'/index/evaluate/getselfinfodepartmentlist',
       		'/index/evaluate/getselfinfoteamlist',
       		'/index/evaluate/selfinforecordsearch',
       		'/index/evaluate/selfinforecorddelete',
       		'/index/evaluate/getselectdutyteamlist',
       		'/index/evaluate/getselectdutytype',
       		'/index/evaluate/collectiveinforecordsearch',
       		'/index/evaluate/exportevaluateselfinfo',
       		'/index/evaluate/collectiveinforecorddelete',
       		'/index/evaluate/exportevaluatecollectiveinfo',
       		'/index/normalcheck/importnormalcheckinfo',
       		'/index/normalcheck/getnormalchecklevellist',
       		'/index/normalcheck/getnormalcheckmodulelist',
       		'/index/normalcheck/getnormalcheckkindlist',
       		'/index/normalcheck/normalcheckrecordsearch',
       		'/index/normalcheck/normalcheckinforecorddelete',
       		'/index/normalcheck/exportnormalcheckrecordinfo',
       		'/index/normalcheck/importoutsourcecheckinfo',
       		'/index/normalcheck/getoutsourcechecklevellist',
       		'/index/normalcheck/getoutsourcecheckteamlist',
       		'/index/normalcheck/getoutsourcecheckkindlist',
       		'/index/normalcheck/outsourcecheckrecordsearch',
       		'/index/normalcheck/outsourcecheckinforecorddelete',
       		'/index/normalcheck/exportoutsourcecheckrecordinfo',
       	)
    );
    public function __construct() {

        if (Config::has('AUTH_CONFIG')) {
            //可设置配置项 AUTH_CONFIG, 此配置项为数组。
            $this->_config = array_merge($this->_config, Config::get('AUTH_CONFIG'));
        }
    }
    //获得权限$name 可以是字符串或数组或逗号分割， uid为 认证的用户id， $or 是否为or关系，为true是， name为数组，
    //只要数组中有一个条件通过则通过，如果为false需要全部条件通过。
    public function check($name, $uid, $relation='or') {
        if (!$this->_config['AUTH_ON'])
            return true;
        $authList = $this->getAuthList($uid);
        //var_dump($authList);
       // $name = strtolower($name);//后添加语句，转为小写匹配
        if (is_string($name)) {
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        //var_dump($name);
        $list = array(); //有权限的name
        foreach ($authList as $val) {
//         	echo $val.'<br>';
            if (in_array($val, $name))
                $list[] = $val;
        }
//         var_dump($list);
        if ($relation=='or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation=='and' and empty($diff)) {
            return true;
        }
        return false;
    }
    //获得用户组，外部也可以调用
    public function getGroups($uid) {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        //$user_groups = \think\Db::name($this->_config['AUTH_GROUP_ACCESS'] . ' a')->where("a.uid='$uid' and g.status='1'")->join($this->_config['AUTH_GROUP']." g on a.group_id=g.id")->select();
        $user_groups = \think\Db::name($this->_config['AUTH_GROUP_ACCESS'])
        ->alias('a')
        ->join($this->_config['AUTH_GROUP']." g", "g.id=a.group_id")
        ->where("a.uid='$uid' and g.status='1'")
        ->field('uid,group_id,title,rules')->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
    }
    //获得权限列表,相当于将表bigsys_auth_rule中对应登陆人员ID的所有name字段数字列出来
    protected function getAuthList($uid) {
//     public function getAuthList($uid) {
        static $_authList = array();
        if (isset($_authList[$uid])) {
            return $_authList[$uid];
        }
        if(isset($_SESSION['_AUTH_LIST_'.$uid])){
            return $_SESSION['_AUTH_LIST_'.$uid];
        }
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids = array();//保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }
        //读取用户组所有权限规则
        $map=array(
            'id'=>array('in',$ids),
            'status'=>1,
 
        );
        //$rules = M()->table($this->_config['AUTH_RULE'])->where($map)->select();
        $rules = \think\Db::name($this->_config['AUTH_RULE'])->where($map)->field('name')->select();
        //循环规则，判断结果。
        $authList = array();
        foreach ($rules as $r) {
            if (!empty($r['condition'])) {
                //条件验证
                $user = $this->getUserInfo($uid);
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $r['condition']);
                //dump($command);//debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $authList[] = $r['name'];
                }
            } else {
                //存在就通过
                //$authList[] = $r['name'];
                $authList[] = strtolower( $r['name']);
            }
        }
        $_authList[$uid] = $authList;
        if($this->_config['AUTH_TYPE']==2){
            //session结果
            $_SESSION['_AUTH_LIST_'.$uid]=$authList;
        }
        //return $authList;
        return  array_unique($authList);
    }
    /*
     * 自定义函数
     * 菜单查询
	 * @param $uid 用户ID
	 * @param $title 权限表中title字段
     */
    //protected function getAuthList($uid,$title) {
    public function getNavigationList($uid,$title) {

    	//读取用户所属用户组
    	$groups = $this->getGroups($uid);
    	//var_dump($groups);
    	$ids = array();//保存用户所属用户组设置的所有权限规则id
    	foreach ($groups as $g) {
    		$ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
    	}
    	$ids = array_unique($ids);
    	if (empty($ids)) {
    		return array();
    	}
    	//读取用户组所有权限规则
    	$map=array(
    			'id'=>array('in',$ids),
    			'status'=>1,
    			'title' =>$title
    	);
    	//$rules = M()->table($this->_config['AUTH_RULE'])->where($map)->select();
    	$rules = \think\Db::name($this->_config['AUTH_RULE'])->where($map)->field('name')->select();
    	//var_dump($rules);
    	//循环规则，判断结果。
    	$authList = array();
    	foreach ($rules as $r) {
    		if (!empty($r['condition'])) {
    			//条件验证
    			$user = $this->getUserInfo($uid);
    			$command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $r['condition']);
    			//dump($command);//debug
    			@(eval('$condition=(' . $command . ');'));
    			if ($condition) {
    				$authList[] = $r['name'];
    			}
    		} else {
    			//存在就通过
    			//$authList[] = $r['name'];
    			$authList[] = strtolower($r['name']);
    		}
    	}
    	//return $authList;
    	return  array_unique($authList);
    }
    /*
     * 自定义函数
    * 获取用户组名称
    * @param $uid 用户ID
    */
    public function getAuthGroupName($uid)
    {
    	$result = \think\Db::name($this->_config['AUTH_GROUP_ACCESS'])->alias('a')->join($this->_config['AUTH_GROUP'].' w','a.group_id = w.id','
LEFT')->where('a.uid',$uid)->field('w.title')->select();
    	
		return $result[0]['title'];
    }
    /*
    * 自定义函数
    * 检测配置文件AUTH_ON是否为true
    * @return  bool
    */
    public function isAuthOn()
    {
    	return $this->_config['AUTH_ON'];
    }
    /*
     * 自定义函数
    * 获取不需要验证ACTION数组
    * @return  array
    */
    public function getNotAuthAction()
    {
    	return $this->_config['NOT_AUTH_ACTION'];
    }
    //获得用户资料,根据自己的情况读取数据库
    protected function getUserInfo($uid) {
        static $userinfo=array();
        if(!isset($userinfo[$uid])){
             $userinfo[$uid]=M()->table($this->_config['AUTH_USER'])->find($uid);
        }
        return $userinfo[$uid];
    }
}
