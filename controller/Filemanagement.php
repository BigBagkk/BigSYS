<?php

namespace app\index\controller;

use app\index\controller\CommAction;
use think\Request;
use app\index\model\DepartmentInfo;
use app\index\model\TeamInfo;
use app\index\model\SubsectionInfo;


use PHPExcel_IOFactory;
use PHPExcel;
use think\session;
use app\index\model\RewardsPunishmentInfo;
use app\index\model\WorkHourInfo;
use app\index\model\DriveHourInfo;
use app\index\model\UpdataManagementInfo;

class Filemanagement extends CommAction {
	/*
	 * 加载文件管理界面
	 */
	public function fileUpdataManagement() {
		$updataManagementInfo = new UpdataManagementInfo();
		$selectUpdataManagementInfo = $updataManagementInfo->getAll();
		$this->assign('selectUpdataManagementInfo',$selectUpdataManagementInfo);
		return $this->fetch();
		//return $this->fetch ();
	}
	
	
}                   