<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../../global/libs/gffunc_member.php");
require_once("../../includes/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
} 
if(isset($_POST)) { 
	$username = isset($_POST['username'])?$_POST['username']:'';
	$fullname = addslashes($_POST['fullname']);
	$birthday = isset($_POST['birthday']) && $_POST['birthday']!=""?strtotime($_POST['birthday']):0;
	$phone = addslashes($_POST['phone']);
    $address = isset($_POST['address'])? addslashes($_POST['address']):'';
	$email = isset($_POST['email'])? addslashes($_POST['email']):'';
	$cmt = isset($_POST['cmt']) ? addslashes($_POST['cmt']):'';
	$cmt_date = isset($_POST['cmt_date']) && $_POST['cmt_date']!="" ? strtotime($_POST['cmt_date']):0;
	$cmt_place = isset($_POST['cmt_place']) ? addslashes($_POST['cmt_place']):'';
	$active = isset($_POST['txt_active']) && $_POST['txt_active']=="on" ? 1:0;
	$isroot = isset($_POST['txt_isroot']) && $_POST['txt_isroot']=="on" ? 1:0;

	if(!empty($username)) {
		$arr = array();
		$arr['fullname'] = $fullname;
		$arr['isactive'] = $active;
		$arr['phone'] = $phone;
		$arr['email'] = $email;
		$arr['cmt'] = $cmt;
		$arr['cmt_place'] = $cmt_place;	
	    $arr['address'] = $address;
		$arr['isroot'] = $isroot;
		$arr['birthday'] = $birthday;
		$arr['cmt_date'] = $cmt_date;
		
		SysEdit("tbl_member", $arr, "username='".$username."'");
		echo "success";
		die();
	}
}