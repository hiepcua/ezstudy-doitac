<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_media.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(!isLogin()) die('Please login to continue!');
$user=isset($_POST['txt_user'])?antiData($_POST['txt_user']):'';
$kyc_name=isset($_POST['txt_kycname'])?antiData($_POST['txt_kycname']):'';
if(!is_array($_FILES) || $user=='') {
	die("Failed to move uploaded file.");
}
$fileName = isset($_FILES["txt_file"]["name"]) ? $_FILES["txt_file"]["name"]:'';
if($fileName==='') die("Can't knowledge name file.");
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
if(!in_array($extension, array('jpeg','jpg','gif','png'))) die("This format is not allowed!");

$filePath='images/uploads/'.str_replace('@gmail.com','',$user).'/';
$Root_Path = ROOT_IMAGE.$filePath;

if (!file_exists($Root_Path)) { 
  if (!mkdir($Root_Path, 0777, true)) {
    die("Failed to create $filePath");
  }
}
$Root_Path.=$fileName;
$fileSource=$_FILES["txt_file"]["tmp_name"];
$chunk = 0; $chunks = 256;
uploadMedia($Root_Path,$fileSource,$chunk,$chunks);
$url=$filePath.$fileName;
$arr=array($kyc_name=>$url);
SysEdit('tbl_member',$arr," username='$user'");
setInfo($kyc_name,$url);
//--------------------------------------------
if(getInfo('kyc1')!='' && getInfo('kyc2')!=''){
	SysEdit('tbl_member',array('iskyc'=>1)," username='$user'");
	setInfo('iskyc',1);
}
die($url);