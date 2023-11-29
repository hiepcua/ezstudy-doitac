<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_packet.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(libs_path.'cls.mysql.php');
$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$type=$data['wallet_type'];
$username=$data['username'];
$table='';
if($type=='s') $table='tbl_wallet_s';
if($type=='b') $table='tbl_wallet_b';
if($table!='' && $username!=''){
	$W_Balance=getWalletBalance($table,$username);
	echo json_encode(array('status'=>'yes','balance'=>$W_Balance));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Username is empty or wallet type is incorrect!'));
}
die();