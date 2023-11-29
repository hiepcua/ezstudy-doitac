<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
echo $data;
$arr=array();
$username=$data['username'];
if($username!=''){
	SysActive('tbl_member',"username='$username'",1);
	echo json_encode(array('status'=>'yes','mess'=>"success"));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Username is required!'));
}