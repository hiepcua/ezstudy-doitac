<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
    $user=getInfo('username');
    $arr=array();
    if(isset($_POST['pass'])){// Daily change pass member
        $user_sale=isset($_POST['user'])?antiData($_POST['user']):'';
        $pass=isset($_POST['pass'])?antiData($_POST['pass']):'';
        $count=SysCount('tbl_member'," AND username='$user_sale' AND par_user='$user'");
        if($count>0){
            $arr['password']=hash('sha256',$user_sale).'|'.hash('sha256',md5($pass));
            SysEdit('tbl_member',$arr," AND username='$user_sale'");
            die('success');
        }
        else die('Bạn không có quyền change pass Sale này');

    }
    else{


        $password=getInfo('password');
        $oldpass=isset($_POST['oldpass'])?antiData($_POST['oldpass']):'';
        $oldpass=hash('sha256',$user).'|'.hash('sha256',md5($oldpass));
        $pass=isset($_POST['password'])?antiData($_POST['password']):'';
        if($pass!='' && $oldpass!=''){
            if($password==$oldpass){
                $arr['password']=hash('sha256',$user).'|'.hash('sha256',md5($pass));
                SysEdit('tbl_member',$arr," AND username='$user'");
                die('success');
            }else{
                die('Old password is incorrect');
            }
        }else{
            die('Password is empty!');
        }
    }

}else{
	die('Please login to continue!');
}
?>