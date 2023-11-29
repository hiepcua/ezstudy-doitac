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
$user=$data['username'];
$secret=$data['gsecret'];
$packet=$data['packet'];
$code_2fa=$data['code_2fa'];
global $_PACKET;
if(!isset($_PACKET['P'.$packet])){
	echo json_encode(array('status'=>'no','mess'=>'Packet does not exist!'));
	die();
}
$ga = new PHPGangsta_GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret,$code_2fa,2);
if ($checkResult){
	$arr=array();
	$arr['new_packet']=$packet;
	$arr['udate']=time();
	SysEdit('tbl_member_packet',$arr," username='$user'");
	echo json_encode(array('status'=>'yes','mess'=>'success'));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Packet does not exist!'));
}
die();