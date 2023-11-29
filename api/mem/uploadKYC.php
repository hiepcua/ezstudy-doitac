<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_media.php');
require_once(libs_path.'cls.mysql.php');

$user=isset($_POST['username'])?antiData($_POST['username']):'';
$kyc_name=isset($_POST['kycname'])?antiData($_POST['kycname']):'';
$apikey=isset($_POST['apikey'])?antiData($_POST['apikey']):'';
$mess='';
if($apikey===PIT_API_KEY){
	if(!is_array($_FILES) || $user=='') {
		$mess="Failed to move uploaded file.";
	}
	$fileName = isset($_FILES["txt_file"]["name"]) ? $_FILES["txt_file"]["name"]:'';
	if($fileName===''){
		$mess="Can't knowledge name file.";
	}
	$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	if(!in_array($extension, array('jpeg','jpg','gif','png'))) 
		$mess="This format is not allowed!";

	$filePath='images/uploads/'.str_replace('@gmail.com','',$user).'/';
	$Root_Path = ROOT_IMAGE.$filePath;

	if (!file_exists($Root_Path)) { 
	  if (!mkdir($Root_Path, 0777, true)) {
		$mess="Failed to create $filePath";
	  }
	}
	if($mess!=''){
		$Root_Path.=$fileName;
		$fileSource=$_FILES["txt_file"]["tmp_name"];
		$chunk = 0; $chunks = 256;
		uploadMedia($Root_Path,$fileSource,$chunk,$chunks);
		$url=$filePath.$fileName;
		$arr=array($kyc_name=>$url);
		SysEdit('tbl_member',$arr," username='$user'");
		echo json_encode(array('status'=>'yes',$kyc_name=>$url));
	}else{
		echo json_encode(array('status'=>'no','mess'=>$mess));
	}
}else{
	echo json_encode(array('status'=>'no','mess'=>'Apikey is incorect!'));
}
die();
?>