<?php
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
define('mail_path','../../global/PHPMailer/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
require_once(mail_path.'mail.php');
$user=isset($_GET['username'])?antiData($_GET['username']):'';
$n=SysCount('tbl_member'," AND username='$user' AND isactive=0");
if($n==1){
	$title = 'Verify account';
	$linkVerify = ROOTHOST.'verifyAccount?user='.urlencode(encrypt($user));
	$body = file_get_contents('../../mail_tem/verifyNewUser.html');
	$body=str_replace('{link_verify}', $linkVerify, $body);
	$body=str_replace('{website}', DOMAIN, $body);
	$body=str_replace('{link_css}', ROOTHOST."mail_tem/style.css", $body);
	sendMail($title,$body,$user);
	echo json_encode(array('status'=>'yes','mess'=>"Sendmail success"));
}else{
	echo json_encode(array('status'=>'no','mess'=>"Member has been activated"));
}