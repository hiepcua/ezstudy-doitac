<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
 if(!$objuser->isLogin()){
	die("E01");
} 
$check_permission = $objuser->Permission('user');
if($check_permission==false) die('E02');

$userid=isset($_POST['userid'])?(int)$_POST['userid']:0;
$sql="UPDATE `sys_users` SET `isactive`=if(`isactive`=1,0,1) WHERE `id`=$userid ";
if($objdata->Query($sql)){}
else{die('E04');}
?>