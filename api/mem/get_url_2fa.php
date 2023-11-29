<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'GoogleAuthenticator.php');

$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$user=$data['username'];
$gsecret=$data['gsecret'];
if($user!=''){
	$ga = new PHPGangsta_GoogleAuthenticator();
	$url=$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$user);
	echo json_encode(array('status'=>'yes','data'=>$url));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Username or Gsecret is empty'));
} 
die();