<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../../global/libs/gffunc.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
require_once("../../libs/cls.category.php");
$objuser=new CLS_USER;
$obj=new CLS_CATEGORY;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
} 

$id=isset($_POST['txt_id'])?addslashes($_POST['txt_id']):0;
$par_id=isset($_POST['cbo_cate'])?addslashes($_POST['cbo_cate']):0;
$name=isset($_POST['txt_name'])?addslashes($_POST['txt_name']):'';
$code=un_unicode($name);
$intro=isset($_POST['txt_intro'])?addslashes($_POST['txt_intro']):'';

$filename=''; $path="../../../images/news/";
if(isset($_FILES["txt_thumb"]["type"]) && $_FILES["txt_thumb"]["type"]!=''){
	$validextensions = array("jpeg", "jpg", "png", "gif");
	$temporary = explode(".", $_FILES["txt_thumb"]["name"]);
	$file_extension = end($temporary);
	if (in_array($file_extension, $validextensions)) {
		if ($_FILES["txt_thumb"]["size"] < 1000000) {
			if ($_FILES["txt_thumb"]["error"] > 0){
				echo "ERR";
			}
			else{
				$filename=$_FILES['txt_thumb']['name'];
				if (file_exists($path.$filename)) {
					$filename=date('YmdHis').$_FILES["txt_thumb"]["name"];
				}
				$sourcePath = $_FILES['txt_thumb']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = $path.$filename; // Target path where file is to be stored
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
				
				$filename ="images/news/".$filename;
			}
		}else echo "ERR1";
	}
	else{
		echo "ERR2";
	}
}

if($id>0){
	$sql="UPDATE `tbl_categories` SET `par_id`='$par_id',`name`='$name',`code`='$code',`thumb`='$filename',`intro`='$intro' WHERE id='$id'";
	$objdata->Exec($sql);
}else{
	$sql="INSERT INTO tbl_categories (`par_id`,`name`,`code`,`thumb`,`intro`,`isactive`) 
	VALUES ('$par_id','$name','$code','$filename','$intro',1)";
	$objdata->Exec($sql);
}
$obj->getListCategory();
?>