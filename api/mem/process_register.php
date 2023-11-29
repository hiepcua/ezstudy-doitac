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
$data	= json_decode(decrypt($json['regis_data'],PIT_API_KEY),true);
$arr=array();
$arr['fullname']=$data['fullname'];
$arr['email']=$data['username'];
$arr['password']=$data['password'];
$username=$data['username'];

$par=explode('|',$data['par_user']);
$par_user=isset($par[0])?$par[0]:'';

if($username!='' && $arr['password']!='' && $arr['fullname']!=''){
	//---------------------check exit member-------------------------
	$n=MemberCountList(" AND username='$username'");
	if($n<=0){
		//---------------------check exit member-------------------------
		$m=MemberCountList(" AND username='$par_user'");
		if($m>=1){
			$arr['par_user']=$par_user;	
		}else{
			$arr['par_user']=MEMBER_ROOT;
		}
		$ga = new PHPGangsta_GoogleAuthenticator();
		$secret = $ga->createSecret();
		$arr['username']=$username;
		$arr['gsecret']=$secret;
		$arr['cdate']=time();
		SysAdd('tbl_member',$arr);
		//------------------------------- add packet -------------------------------
		$arr=array();
		$arr['username']=$username;
		if($m>=1){
			$arr['par_user']=$par_user;	
		}else{
			$arr['par_user']=MEMBER_ROOT;
		}
		
		$side=isset($par[1])?$par[1]:0;
		$rep_user=findNode($arr['par_user'],$side);
		$n=MemberCountList(" AND username='$rep_user'");
		if($m>=1){
			$arr['rep_user']=$rep_user;	
		}else{
			$arr['rep_user']=MEMBER_ROOT;
		}
		$arr['side']=$side;
		$arr['packet']=0;
		$arr['cdate']=time();
		$arr['mdate']=time();
		$arr['day']=0;
		$arr['loop']=0;
		$arr['isactive']=0;
		$idPacket=SysAdd('tbl_member_packet',$arr);
		//------------------------------- update path -------------------------------
		$obj=SysGetList('tbl_member_packet',array('path')," AND username='".$arr['rep_user']."'");
		$path=$obj[0]['path'].$idPacket.'-';
		$arr=array('path'=>$path);
		SysEdit('tbl_member_packet',$arr,"id=$idPacket");
		//------------------------------- update packet histories -------------------------------
		$arr=array();
		$arr['username']=$username;
		$arr['packet']=0;
		$arr['cdate']=time();
		$arr['note']='Waiting upgrade to Investors';
		SysAdd('tbl_member_packet_histories',$arr);
		file_get_contents(ROOTHOST."api/mem/sendmail_verifyAccount.php?username=$username");
		echo json_encode(array('status'=>'yes','mess'=>"success"));
	}else{
		echo json_encode(array('status'=>'no','mess'=>"Username $username is exist"));
	}
}else{
	echo json_encode(array('status'=>'no','mess'=>'Username, Password and fullname are required!'));
}