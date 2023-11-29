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

$id=isset($_POST['txtid'])?(int)$_POST['txtid']:0;
$mc_id=isset($_POST['mc_id'])?(int)$_POST['mc_id']:0;
$money = isset($_POST['txt_name'])? (int)$_POST['txt_name']:0;
$profile = isset($_POST['txt_name'])? addslashes($_POST['txt_name']):'';
$note = addslashes($_POST['txt_note']);

if(isset($_POST['frm_fee'])){
	if(isset($_POST['cmd_update_user'])) {
		$sql="UPDATE tbl_log_fee SET 
		`member_course_id`='$mc_id',
		`money`='$money',
		`note`='$note'
		WHERE id=$id";
		if($objdata->Query($sql)){}
			else{echo 'E04';}
	}else{
		$cdate = time();
		$sql="INSERT INTO tbl_log_fee(`member_course_id`,`money`,`cdate`,`note`) VALUES('$mc_id','$money','$cdate','$note')";
		if($objdata->Query($sql)){}
			else{echo 'E04';}
	}
}else{
	if(isset($_POST['cmd_update_user'])) {
		$sql="UPDATE tbl_log_profile SET 
		`member_course_id`='$mc_id',
		`profile`='$profile',
		`note`='$note'
		WHERE id=$id";
		if($objdata->Query($sql)){}
			else{echo 'E04';}
	}else{
		$cdate = time();
		$sql="INSERT INTO tbl_log_profile(`member_course_id`,`profile`,`cdate`,`note`) VALUES('$mc_id','$profile','$cdate','$note')";
		if($objdata->Query($sql)){}
			else{echo 'E04';}
	}
}
?>