<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');

if(isset($_SESSION['MEMBER_LOGIN'])){
	unset($_SESSION['MEMBER_LOGIN']);
}