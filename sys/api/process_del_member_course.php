<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../libs/cls.mysql.php");
require_once("../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
 if(!$objuser->isLogin()){
	die("E01");
} 
$id=isset($_POST['id'])?(int)$_POST['id']:0;
$sql="DELETE FROM `tbl_member_course` WHERE `id`=$id";
if($objdata->Query($sql)){}
else{die('E04');}
?>