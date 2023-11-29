<?php
session_start();
require_once("../global/libs/gfinit.php");
require_once("../global/libs/gfconfig.php");
require_once("../global/libs/gffunc.php");
require_once("includes/gfconfig.php");
require_once("libs/cls.mysql.php");
require_once("libs/cls.user.php");
$UserLogin = new CLS_USER();
$UserLogin->LOGOUT();
echo '<script language="javascript">window.location="index.php"</script>';
?>