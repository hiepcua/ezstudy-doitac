<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_pit.php');
require_once(incl_path.'gffunc_packet.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'GoogleAuthenticator.php');
if(isLogin()){
	$user=getInfo('username');
	$secret=getInfo('gsecret');
	$link1=isset($_POST['link1'])?antiData($_POST['link1']):'';
	$link2=isset($_POST['link2'])?antiData($_POST['link2']):'';
	$code_2fa=isset($_POST['code_2fa'])?antiData($_POST['code_2fa']):'';
	
	$ga = new PHPGangsta_GoogleAuthenticator();
	$checkResult = $ga->verifyCode($secret,$code_2fa,2);
	if ($checkResult){
		$arr=array();
		$arr['link1']=$link1;
		$arr['link2']=$link2;
		SysEdit('tbl_member',$arr," username='$user'");
		setInfo('link1',$link1);
		setInfo('link2',$link2);
		die('success');
	}else{
		die('2FA code is Incorrect!');
	}
}else{
	die('<p class="text-center">Please login to continue!</p>');
}
?>