<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
$arr=array();
$arr['username']=isset($_POST['username'])?antiData($_POST['username']):'';
$arr['ischeck']=isset($_POST['ischeck'])?antiData($_POST['ischeck']):'no';
$arr['time']=time();
$password=isset($_POST['password'])?antiData($_POST['password']):'';
unset($_POST);
if($arr['username']=='' || $password=='') die('Username and Password are empty');
$arr['password']=hash('sha256', $arr['username']).'|'.hash('sha256', md5($password));
$data=array();
$data['login_data']=encrypt(json_encode($arr),PIT_API_KEY);
$url=ROOTHOST.'api/member/login';
$rep=Curl_Post($url,json_encode($data));

	$arr['password']=$password;
	if((int)$rep['data']['is2fa']==1){
		$_SESSION['MEMBER_1FA']=$rep['data'];
		die('2fa');
	}else{
		if($arr['ischeck']=='yes') setcookie('LOGIN_USER',encrypt(json_encode($arr)),time() + (86400 * 30), "/");
		$session=$cdate=time();
		$_SESSION['MEMBER_LOGIN']=$rep['data'];
		$_SESSION['MEMBER_LOGIN']['session']=$session;
		$_SESSION['MEMBER_LOGIN']['action_time']=$cdate;
		$_SESSION['MEMBER_LOGIN']['islogin']=true;
		die('success');
	}

unset($data);
unset($rep);
unset($arr);