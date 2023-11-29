<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(libs_path.'cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$user=$data['username'];
$is2fa=$data['is2fa'];
$arr=array();
if($user!=''){
	$arr['is2fa']=$arr['is2fa']=='yes'?1:0;
	SysEdit('tbl_member',$arr," username='$user'");
	echo json_encode(array('status'=>'yes','mess'=>"success"));
}else{
	echo json_encode(array('status'=>'no','mess'=>"username is empty"));
}
die();