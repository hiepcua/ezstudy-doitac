<?php
session_start();
header('Content-Type: text/html; charset="UTF-8"');
define('incl_path','../../global/libs/');
define('mail_path','../../global/PHPMailer/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
$arr=array();
$user=isset($_GET['user'])?$_GET['user']:'';
$user=str_replace(' ','%2B',$user);
if($user=='') die('Username is empty');
$arr['username']=decrypt(urldecode($user));
$data=array();
$data['data']=encrypt(json_encode($arr),PIT_API_KEY);
$url=ROOTHOST.'api/member/verifi';
$rep=Curl_Post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
if($rep['status']=='no')
	die($rep['mess']);
else{
	die('<div class="text-center">Verify account success! <a href="'.ROOTHOST.'login">Login now</a>?</div>');
}
unset($data);
unset($rep);
unset($arr);