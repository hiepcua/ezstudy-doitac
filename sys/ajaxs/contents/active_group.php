<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
require_once("../../libs/cls.category.php");

$obj=new CLS_CATEGORY;
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
}
$id=isset($_GET['id'])?(int)$_GET['id']:0;
$status=isset($_GET['status'])?(int)$_GET['status']:0;
$sql="UPDATE tbl_categories SET isactive=$status WHERE id='$id'";
$objdata->Exec($sql);
$obj->getListCategory();
?>