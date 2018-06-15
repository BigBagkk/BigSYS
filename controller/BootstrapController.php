<?php

// 本类由系统自动生成，仅供测试用途

namespace Home\Controller;

use Think\Controller;

use Think\Model;
use app\index\controller\CommAction;
// include("../Edit/DataTables.php" );

vendor('Edit.DataTables');

 use

    DataTables\Editor,

   DataTables\Editor\Field,

   DataTables\Editor\Format,

   DataTables\Editor\Mjoin,

   DataTables\Editor\Upload,

   DataTables\Editor\Validate,

   DataTables\Database;

 

class BootstrapController extends CommAction

{
   public $editordb;
   public function __construct()
   {
        parent::__construct();

        $sql_details = array(

            "type" => "Mysql",// Database type: "Mysql","Postgres", "Sqlite" or "Sqlserver"

            "user" => "root", // Database user name

            "pass" => "", // Database password

            "host" => "127.0.0.1",// Database host

            "port" => "", // Database connection port (can be left empty fordefault)

            "db" => "bigsystemdatabase", // Database name

            "dsn" => "charset=utf8"

        )// PHP DSN extra information. Set as`charset=utf8` if you are using MySQL

           // 'pdo' => new Model()
        ;
        $this->editordb = new Database($sql_details);
   }
   public function index()
   {
        $this->display();
   }
  public function datatable()
   {
        $this->display();
   }
   public function GetTableData()
   {
        $test = D('userinfo');
        $rst = $test->select();
        $arr = array(
            "data" => $rst
        );
        $this->ajaxReturn($arr, "JSON");
   }
   public function editTableData()
   {
        $this->display();
   }
     Public function HandleEditTableData()
   {
        $qing = Editor::inst($this->editordb, 'bigsys_auth_group_access');
		$qing->fields(
				Field::inst('uid'), 
				Field::inst('group_id')
        );
        $qing->process($_POST);
        $qing->json();
   }
}
?>