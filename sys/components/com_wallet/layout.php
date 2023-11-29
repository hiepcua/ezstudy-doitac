<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
define('COMS','wallet');
$title_manager="Quản lý Ví";
if(isset($_GET['task']) && $_GET['task']=='add')
	$title_manager = "Thêm mới thành viên";
if(isset($_GET['task']) && $_GET['task']=='edit')
	$title_manager = "Cập nhật thành viên";

define('THIS_COM_PATH',COM_PATH.'com_'.COMS.'/');$task='';
if(isset($_GET['task']))
	$task=$_GET['task'];
if(!is_file(THIS_COM_PATH.'task/'.$task.'.php')){
	$task='list';
}
include_once(THIS_COM_PATH.'task/'.$task.'.php');
unset($task); unset($ids); unset($obj); unset($objlang);
?>