<?php
define('COMS','account');
define('OBJ','Tài khoản');
$task=isset($_GET['task']) ? addslashes($_GET['task']):'list';
$title_manager="Danh sách ".OBJ;
if($task=='add') $title_manager = "Thêm mới ".OBJ;
if($task=='edit') $title_manager = "Sửa ".OBJ;


$viewtype=isset($_GET['viewtype'])?addslashes($_GET['viewtype']):'list';
if(is_file(COM_PATH.'com_'.COMS.'/tem/'.$viewtype.'.php'))
    include_once('tem/'.$viewtype.'.php');


unset($viewtype); unset($obj);
?>