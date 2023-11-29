<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
$arr=array();
$arr['username']=isset($_POST['username'])?antiData($_POST['username']):'';
$arr['ischeck']=isset($_POST['ischeck'])?antiData($_POST['ischeck']):'no';
$arr['time']=time();
$username=isset($_POST['username'])?antiData($_POST['username']):'';
$password=isset($_POST['password'])?antiData($_POST['password']):'';

unset($_POST);
if($arr['username']=='' || $password=='') die('Username and Password are empty');
$arr['password']=hash('sha256', $username).'|'.hash('sha256', $password);
$data=array();
$rep=LogIn($username,$password);

if($rep['data']==null)
    die('Login failed');
else{
    if($arr['ischeck']=='yes') setcookie('LOGIN_USER',encrypt(json_encode($arr)),time() + (86400 * 30), "/");
    $rep['islogin']=true;
    setSessionLogin($rep);
    die('success');
}
unset($data);
unset($rep);
unset($arr);