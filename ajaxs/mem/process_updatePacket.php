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
	$packet=isset($_POST['packet'])?antiData($_POST['packet'],'int'):0;
	$code_2fa=isset($_POST['code_2fa'])?antiData($_POST['code_2fa']):'';
	global $_PACKET;
	if(!isset($_PACKET['P'.$packet])) die('Packet does not exist!');
	$ga = new PHPGangsta_GoogleAuthenticator();
	$checkResult = $ga->verifyCode($secret,$code_2fa,2);
	if ($checkResult){
		$arr=array();
		$arr['new_packet']=$packet;
		$arr['udate']=time();
		SysEdit('tbl_member_packet',$arr," username='$user'");
	}else{
		die('2FA code is Incorrect!');
	}
}else{
	die('<p class="text-center">Please login to continue!</p>');
}
?>