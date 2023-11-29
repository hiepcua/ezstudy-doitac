<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../libs/cls.mysql.php");
require_once("../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
} 


$id=isset($_POST['txt_id'])?(int)$_POST['txt_id']:0;
$day = strtotime($_POST['txt_day']);
$from_hour = strtotime($_POST['txt_from_hour']);
$to_hour = strtotime($_POST['txt_to_hour']);
$note = stripslashes($_POST['txt_note']);
$teacher_id=isset($_POST['cbo_teacher'])?(int)$_POST['cbo_teacher']:0;
$cbo_member=isset($_POST['list_member_id'])?test_input($_POST['list_member_id']):'';

// Get member_course_id
$sql="SELECT id from tbl_member_course WHERE user_id = $teacher_id AND mem_id IN($cbo_member) AND isactive=1";
$objdata->Query($sql);
$str_memcourse='';
while ($row_memcourse = $objdata->Fetch_Assoc()) {
	$str_memcourse.= $row_memcourse['id'].',';
}
//$str_memcourse= substr($str_memcourse,0,strlen($str_memcourse)-1);

if(isset($_POST['cmd_update_user'])) {
	$sql="UPDATE tbl_schedule_task SET 
	`mem_id`='$mem_id',
	`user_id`='$teacher_id',
	`course_id`='$course_id',
	`isactive`=1
	WHERE id=$id";
	if($objdata->Query($sql)){}
		else{die('E04');}
}else{
	$sql="INSERT INTO tbl_schedule_task(`member_course_id`,`isday`,`from_hour`,`to_hour`,`note`) 
	VALUES('".$str_memcourse."','$day','$from_hour','$to_hour','$note')";
	if($objdata->Query($sql)){}
		else{die('E04');}
}
?>