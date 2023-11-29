<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()) die("E01");
if(isset($_POST['id'])) { 
	$id=$_POST['id'];
	$pdate=time();
	$sql="UPDATE tbl_withdraw_pit SET ispay=1,pdate='$pdate' WHERE id='$id'";
	$objdata->Exec($sql);
	$sql="UPDATE tbl_wallet_s SET status=1 WHERE status=0";
	$objdata->Exec($sql);
	die('success');
}
die();