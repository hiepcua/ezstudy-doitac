<?php
session_start();
define('incl_path','../global/libs/');
define('libs_path','../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(libs_path.'cls.mysql.php');
$json 	= json_decode(file_get_contents('php://input'),true);
$data	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$type=$data['wallet_type'];
$type='b';
$page=isset($data['page'])?$data['page']:1;
$table='';
if($type=='s') $table='tbl_wallet_s';
if($type=='b') $table='tbl_wallet_b';
$start=($page-1)*50;
if( $table!=''){
	$obj=SysGetList($table,array()," ORDER BY cdate DESC LIMIT $start,50");
	echo json_encode(array('status'=>'yes','page'=>$page,'data'=>$obj));
}else{
	echo json_encode(array('status'=>'no','mess'=>'Wallet account is incorrect!'));
}
die();