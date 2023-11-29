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
$arr=array();
$user=$data['username'];
$oldpass=hash('sha256',$user).'|'.hash('sha256',md5($data['oldpass']));
$arr['password']=hash('sha256',$user).'|'.hash('sha256',md5($data['newpass']));
$obj=SysGetList('tbl_member',array('password')," AND username='$user'");
if(isset($obj[0]['password']) && $obj[0]['password']== $oldpass){
	SysEdit('tbl_member',$arr," username='$user'");
	echo json_encode(array('status'=>'yes','mess'=>'Change password success!'));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Old password is incorrect!'));
}
die();