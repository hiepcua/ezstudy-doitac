<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
define('mail_path','../../global/PHPMailer/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');

$arr = array();
$par_user = isset($_COOKIE['RES_USER']) ? decrypt($_COOKIE['RES_USER']):'';
$arr['key'] = PIT_API_KEY;
$arr['username'] = isset($_POST['username']) ? antiData($_POST['username']):'';
$arr['password'] = isset($_POST['password']) ? antiData($_POST['password']):'';
$arr['fullname'] = isset($_POST['fullname']) ? antiData($_POST['fullname']):'';
$arr['grade'] = isset($_POST['grade']) ? antiData($_POST['grade']):'';
$arr['ref_user'] = isset($_POST['ref_user']) ? antiData($_POST['ref_user']):'';
$arr['saler'] = isset($_POST['saler']) ? antiData($_POST['saler']):'';
$arr['type'] = isset($_POST['type']) ? antiData($_POST['type']):'';//hocsinh và chame
$arr['partner_code'] = 'DT_01';
unset($_POST);

if($arr['username']=='' || $arr['password']=='') die('Username and Password are empty');

$data = array();
$data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$url = DOMAIN_EZSTUDY.'api/doitac/register';
$rep = Curl_Post($url,json_encode($data));

if($rep['status']=='no') die($rep['mess']);
else{
	die('success');
}
unset($data);
unset($rep);
unset($arr);