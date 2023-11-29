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
$type=$data['packet'];
if($type=='packet'){
	$obj=array();
	foreach($_PACKET as $key=>$packet){
		$obj[$key]=array('name'=>$packet['name'],'price'=>$packet['price']);
	}
	echo json_encode(array('status'=>'yes','data'=>$obj));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Wallet account is incorrect!'));
}
die();