<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'GoogleAuthenticator.php');

$oneCode=isset($_POST['2fa_code'])?antiData($_POST['2fa_code']):'';
if(isset($_SESSION['MEMBER_1FA'])){
	$secret=$_SESSION['MEMBER_1FA']['gsecret'];
	$ga = new PHPGangsta_GoogleAuthenticator();
	$checkResult = $ga->verifyCode($secret,$oneCode,2);
	if ($checkResult) {
		$session=$cdate=time();
		$_SESSION['MEMBER_LOGIN']=$_SESSION['MEMBER_1FA'];
		$_SESSION['MEMBER_LOGIN']['session']=$session;
		$_SESSION['MEMBER_LOGIN']['action_time']=$cdate;
		$_SESSION['MEMBER_LOGIN']['islogin']=true;
		unset($_SESSION['MEMBER_1FA']);
		die('success');
	}else{
		die('2FA code is Incorrect!');
	}
}