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
$user=$data['username'];
$obj=SysGetList('tbl_member',array('par_user')," AND username='$user'");

$par_user=$obj[0]['par_user'];
$obj=SysGetList('tbl_member',array('link1','link2')," AND username='$par_user'");
$arr=array();
$arr['username']=$par_user;
$arr['link1']=$obj[0]['link1'];
$arr['link2']=$obj[0]['link2'];
die(json_encode(array('status'=>'yes','data'=>$arr)));