<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_packet.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$user=getInfo('username');
	$card=isset($_POST['card'])?antiData($_POST['card']):'';
	$obj=SysGetList('tbl_card',array()," AND cardcode='$card' AND status=0",false);
	if($obj->Num_rows()>0){
		$r=$obj->Fetch_Assoc();
		$packet=$r['packet'];
		if($packet>0) Bonus_SALERS($user,$packet);
		// update used card
		SysEdit('tbl_card',array('status'=>1)," cardcode='$card' ");
		$note="Active by $user";
		$arr=array();
		$arr['cardcode']=$card;
		$arr['member']=$user;
		$arr['cdate']=time();
		$arr['content']=$note;
		SysAdd('tbl_card_transection',$arr);
		die('success');
	}else{
		die('Card number not found or the not exist!');
	}
}else{
	die('<p class="text-center">Please login to continue!</p>');
}
?>