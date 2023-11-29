<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
require_once('../../global/PHPMailer/mail.php');
require_once(libs_path.'GoogleAuthenticator.php');
if(isset($_POST['email'])) {
	$email=isset($_POST['email'])?antiData($_POST['email']):'';
	$sql = "SELECT * FROM tbl_member WHERE email='$email'";
	$obj = new CLS_MYSQL;
	$obj->Query($sql);
	if($obj->Num_rows()==0) die('Email incorrect');
	$r = $obj->Fetch_Assoc();
	if($r['isactive']!=1) die('Account locked. Please contact the administrator.');
	$user = $r['username'];
	$is2fa = $r['is2fa'];
	$gsecret=$r['gsecret'];
	if($is2fa===0) die('No information 2FA Qr-Code');
	$ga = new PHPGangsta_GoogleAuthenticator();
	$qrcode = '<img src="'.$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$user).'" />';
	$qrcode.= '<div><strong>2FA Qr-Code</strong></div>';
	// Gửi mail
	$title = "2FA Qr-code Information";
	$body =file_get_contents('../../mail_tem/forgot2fa.html');
	$body=str_replace('{username}', $user, $body);
	$body=str_replace('{qrcode}', $qrcode, $body);
	$body=str_replace('{link_css}', ROOTHOST."mail_tem/style.css", $body);
	sendMail($title,$body,$user);
	die('success');
} ?>