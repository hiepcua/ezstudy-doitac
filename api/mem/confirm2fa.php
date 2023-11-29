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

$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$code2fa=$data['code2fa'];
$secret=$data['gsecret'];

$ga = new PHPGangsta_GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret,$code2fa,2);
if ($checkResult) {
	echo json_encode(array('status'=>'yes','mess'=>''));
}else{
	echo json_encode(array('status'=>'no','mess'=>''));
}
die();