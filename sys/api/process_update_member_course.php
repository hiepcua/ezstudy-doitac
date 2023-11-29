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
$id=isset($_POST['txt_id'])?(int)$_POST['txt_id']:0;
$mem_id=isset($_POST['cbo_mem'])?(int)$_POST['cbo_mem']:0;
$course_id=isset($_POST['cbo_course'])?(int)$_POST['cbo_course']:0;
$teacher_id=isset($_POST['cbo_teacher'])?(int)$_POST['cbo_teacher']:0;

if(isset($_POST['cmd_update_user'])) {
	$sql="UPDATE tbl_member_course SET 
	`mem_id`='$mem_id',
	`user_id`='$teacher_id',
	`course_id`='$course_id',
	`isactive`=1
	WHERE id=$id";
	// echo $sql;
	if($objdata->Query($sql)){}
		else{die('E04');}
}else{
	$sql="INSERT INTO tbl_member_course(`mem_id`,`user_id`,`course_id`,`isactive`) VALUES('$mem_id','$teacher_id','$course_id',1)";
	// echo $sql;
	if($objdata->Query($sql)){}
		else{die('E04');}
}
?>