<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
require_once("../../libs/cls.category.php");

$objuser=new CLS_USER;
$obj=new CLS_CATEGORY;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()){
	die("E01");
}
$id=isset($_GET['id'])?(int)$_GET['id']:0;
$sql="DELETE FROM tbl_categories WHERE id='$id'";
$objdata->Exec($sql);
unset($_SESSION['CONTENT_ID_SELECTED']);
echo "success";
?>