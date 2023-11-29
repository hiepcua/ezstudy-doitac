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
if(isLogin()){
$arr=array();
$user=getInfo('username');
$arr['is2fa']=isset($_POST['is2fa'])?antiData($_POST['is2fa']):'';
if($user!=''){
	$arr['is2fa']=$arr['is2fa']=='yes'?1:0;
	SysEdit('tbl_member',$arr," username='$user'");
	if($arr['is2fa']===1){
		setInfo('is2fa',$arr['is2fa']);
		$gsecret=getInfo('gsecret');
		$ga = new PHPGangsta_GoogleAuthenticator();
		die('<img src="'.$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$user).'" />');
	}else{
		die('');
	}
}else{
	die('Username is empty!');
}
}
die();