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
$user	= $data['username'];
$leftcode=encrypt($user.'|0');
$rightcode=encrypt($user.'|1');
$dsA=getDSNhom($user,0);
$dsB=getDSNhom($user,1);
$arr=array();
$arr['left_link']=ROOTHOST."register?req=".urlencode($leftcode);
$arr['left_ds']=$dsA;
$arr['right_link']=ROOTHOST."register?req=".urlencode($rightcode);
$arr['right_ds']=$dsB;

/* $obj=SysGetList('tbl_member',array()," AND par_user='$username'",false);
while($r_mem=$obj->Fetch_Assoc()){
	$arr1[]=$r_mem['username'];
	$jdate=date('Y-m-d H:i',$r_mem['cdate']);
	$objpacket=SysGetList('tbl_member_packet',array()," AND username='$username'");
	$packet=$objpacket[0]['packet'];
	$level=isset($_PACKET['P'.$packet]['name'])?$_PACKET['P'.$packet]['name']:'';
	
} */
die(json_encode(array('status'=>'yes','mess'=>'success','username'=>$user,'data'=>$arr)));
