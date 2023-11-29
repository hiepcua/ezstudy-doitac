<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
} 
if(isset($_POST['username'])) {
	$username = isset($_POST['username'])?$_POST['username']:'';
	$sql="UPDATE tbl_member SET `isbus`=if(`isbus`=1,0,1) WHERE username='$username'";
	$objdata->Query($sql);
	echo 'success';
} ?>