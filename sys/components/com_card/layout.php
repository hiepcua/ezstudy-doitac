<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
define('COMS','card');
$title_manager = 'Quản lý kho thẻ';
if(isset($_GET['task']) && $_GET['task']=='add')
	$title_manager = 'Tạo thẻ mới';
if(isset($_GET['task']) && $_GET['task']=='order')
	$title_manager = 'Quản lý đơn hàng';
$obj=new CLS_CARD();
if(isset($_POST['cmdsave'])){
    if(isset($_POST['txt_packet'])){
        $packet=(int)$_POST['txt_packet'];
    }
	$num=(int)$_POST['txt_num'];
	$author=$_SESSION[MD5($_SERVER['HTTP_HOST']).'_USERLOGIN']['username'];
	$obj->Add_new($packet,$num,$author);
	echo "<script language=\"javascript\">window.location='index.php?com=".COMS."'</script>";
}	
define("THIS_COM_PATH",COM_PATH."com_".COMS."/");
$task='';
if(isset($_GET["task"]))
	$task=$_GET["task"];
if(!is_file(THIS_COM_PATH.'task/'.$task.'.php')){
	$task='list';
}
include_once(THIS_COM_PATH.'task/'.$task.'.php'); 
	
unset($objlag);
unset($obj); 
?>