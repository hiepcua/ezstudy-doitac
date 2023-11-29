<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_pit.php');
require_once(incl_path.'gffunc_packet.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'GoogleAuthenticator.php');
$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$user=antiData($data['username']);
$secret=antiData($data['gsecret']);
$code_2fa=antiData($data['code_2fa']);
$link1=antiData($data['link1']);
$link2=antiData($data['link2']);

$ga = new PHPGangsta_GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret,$code_2fa,2);
if (!$checkResult){
	die(json_encode(array('status'=>'no','mess'=>'2FA code is Incorrect!')));
};

$arr=array();
$arr['link1']=$link1;
$arr['link2']=$link2;
SysEdit('tbl_member',$arr," username='$user'");

die(json_encode(array('status'=>'yes','mess'=>'success')));