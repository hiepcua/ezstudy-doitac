<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$user=getInfo('username');
	$arr=array();
	$arr['fullname']=isset($_POST['fullname'])?antiData($_POST['fullname']):'';
	$arr['phone']=isset($_POST['phone'])?antiData($_POST['phone']):'';
	$arr['cmt']=isset($_POST['cmt'])?antiData($_POST['cmt']):'';
	$arr['cmt_date']=isset($_POST['cmt_date'])?antiData($_POST['cmt_date']):'';
	$arr['cmt_place']=isset($_POST['cmt_place'])?antiData($_POST['cmt_place']):'';
	SysEdit('tbl_member',$arr," username='$user'");
	setInfo('fullname',$arr['fullname']);
	setInfo('phone',$arr['phone']);
	setInfo('cmt',$arr['cmt']);
	setInfo('cmt_date',$arr['cmt_date']);
	setInfo('cmt_place',$arr['cmt_place']);
	die('success');
}else{
	die('Please login to continue!');
}