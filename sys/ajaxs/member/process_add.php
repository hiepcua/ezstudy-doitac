<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../../global/libs/gffunc_member.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()) die("E01");
$cuser = getInfo("username");
$cuser_path = getInfo("path");

$username = isset($_POST['txt_username']) ? $_POST['txt_username']:'';
if($username!="") { 
	// Kiểm tra tài khoản tồn tại
	$num = SysCount("tbl_member", "AND username='".$username."'");
	if($num>0){
		echo "Username exist"; die();
	}

	$arr = array();
	$password = antiData($_POST['txt_password']);
	$hash_pass = hash('sha256', $username).'|'.hash('sha256', $password);
	$arr['username'] = $username;
	$arr['password'] = $hash_pass;
	$arr['par_user'] = $cuser;
	$arr['fullname'] = antiData($_POST['txt_fullname']);
	$arr['isactive'] = $_POST['txt_active']=="on" ? 1:0;
	$arr['phone'] = antiData($_POST['txt_phone']);
	$arr['email'] = isset($_POST['txt_email'])? antiData($_POST['txt_email']):'';
	$arr['cmt'] = isset($_POST['txt_cmt']) ? antiData($_POST['txt_cmt']):'';
	$arr['cmt_place'] = isset($_POST['txt_cmt_place']) ? antiData($_POST['txt_cmt_place']):'';	
    $arr['address'] = isset($_POST['txt_address'])? antiData($_POST['txt_address']):'';
	$arr['isroot'] = isset($_POST['txt_isroot']) && $_POST['txt_isroot']=="on" ? 1:0;
	$arr['birthday'] = isset($_POST['txt_birthday']) && $_POST['txt_birthday']!=""?strtotime($_POST['txt_birthday']):0;
	$arr['cmt_date'] = isset($_POST['txt_cmt_date']) && $_POST['txt_cmt_date']!=""?strtotime($_POST['txt_cmt_date']):0;
	$arr['cdate'] = time();
	$arr['path'] = $cuser_path.'_'.$username.'_';

	SysAdd("tbl_member", $arr);
	echo "success";
	die();
}
die("error");