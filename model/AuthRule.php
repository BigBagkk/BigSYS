<?php

namespace app\index\model;

class AuthRule extends \think\Model {
	// 设置当前模型对应的完整数据表名称
	protected $table = 'bigsys_auth_rule';
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
			'debug' => false 
	];
	/*
	 * 菜单查询 name字段 @return array
	 */
	public function navsSearch($title) {
		return $this->where ( 'title', $title )->column ( 'name' );
	}
	/*
	 * 菜单查询 id name 字段 @return array
	 */
	public function roleSearch($title) {
		return $this->where ( 'title', $title )->field('id,name')->select();
	}
	/*
	 * 新增一级菜单 @return bool
	 */
	public function addFirstNav($firstNav) {
		// $this->name = "你好";//全局变量冲突
		// $this->title = "一级菜单";
		$this->data ( [ 
				'name' => $firstNav,
				'title' => '一级菜单' 
		] );
		// 防止用户新增一级菜单重复。
		try {
			$this->save ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 新增二级菜单 @return bool
	 */
	public function addSecondNav($firstNav, $addSecondNav) {
		$this->data ( [ 
				'name' => $addSecondNav,
				'title' => $firstNav 
		] );
		// 防止用户新增一级菜单重复。
		try {
			$this->save ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 新增三级菜单 @return bool
	 */
	public function addThirdNav($postSecondNav, $postThirdNav, $postThirdURL) {
		$list = [ 
				[ 
						'name' => $postThirdNav,
						'title' => $postSecondNav
				],
				[ 
						'name' => $postThirdURL,
						'title' => $postThirdNav
				] 
		];
		// 防止用户新增一级菜单重复。
		try {
			$this->saveAll($list);
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 修改一级菜单 @return int/bool
	 */
	public function changFirstNav($oldpostFirstNav, $newpostFirstNav) {
		try {
			$this->save ( [ 
					'name' => $newpostFirstNav 
			], [ 
					'name' => $oldpostFirstNav 
			] );
			$this->save ( [ 
					'title' => $newpostFirstNav 
			], [ 
					'title' => $oldpostFirstNav 
			] );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 修改二级菜单 @return int/bool
	 */
	public function changSecondNav($oldpostSecondNav, $newpostSecondNav) {
		try {
			$this->save ( [ 
					'name' => $newpostSecondNav 
			], [ 
					'name' => $oldpostSecondNav 
			] );
			$this->save ( [ 
					'title' => $newpostSecondNav 
			], [ 
					'title' => $oldpostSecondNav 
			] );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 修改三级菜单 @return int/bool
	 */
	public function changThirdNav($postOldThirdNav,$postNewThirdNav,$postNewThirdURL) {
		try {
			$this->save ( [ 
					'name' => $postNewThirdURL 
			], [ 
					'title' => $postOldThirdNav 
			] );
			$this->save ( [ 
					'name' => $postNewThirdNav 
			], [ 
					'name' => $postOldThirdNav 
			] );
			$this->save ( [ 
					'title' => $postNewThirdNav 
			], [ 
					'title' => $postOldThirdNav 
			] );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 删除菜单 删除对应二级菜单 未做 删除对应三级菜单 未做 删除对应url 未做 删除bigsys_auth_group对应的权限组中的包含项 未做 @return int/bool
	 */
	public function deleteNav($deleteNav) {
		try {
			$this->where ( 'name', $deleteNav )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
	/*
	 * 删除三级菜单对应URL
	 */
	public function deleteURL($deleteNav) {
		try {
			$this->where ( 'title', $deleteNav )->delete ();
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}
}
