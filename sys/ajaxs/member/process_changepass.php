<?php
session_start();
require_once('../../global/libs/gfconfig.php');
require_once('../../global/libs/gfinit.php');
require_once('../../global/libs/gffunc.php');
require_once('../../../global/libs/gffunc_member.php');
require_once('../../libs/cls.mysql.php');
require_once('../../libs/cls.user.php');
require_once('../../global/PHPMailer/mail.php');
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()) die("E01");

if(isset($_POST['user'])) {
	$obj=new CLS_MYSQL;
	$user=addslashes(strip_tags($_POST['user']));
	$pass=addslashes(strip_tags($_POST['pass']));
	$pass_encode=hash('sha256',$user).'|'.hash('sha256',md5($pass));
	$sql="UPDATE tbl_member SET `password`='$pass_encode' WHERE username='$user'";
	$obj->Exec($sql); 
	
	// Gửi mail
	$title = "Change Password";
	$body =file_get_contents('../../mail_temp/changePass.html');
	$body=str_replace('{username}', $user, $body);
	$body=str_replace('{password}', $pass, $body);
	$body=str_replace('{link_css}', ROOTHOST."mail_tem/style.css", $body);
	sendMail($title,$body,$user);
	echo 'success';
}?>