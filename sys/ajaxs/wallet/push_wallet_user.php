<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../../global/libs/gffunc_member.php");
require_once("../../global/libs/gffunc_wallet.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
global $arr_serec_code;
$username=isset($_POST['txtcustomer_push'])? addslashes(trim($_POST['txtcustomer_push'])):"";
$number_wallet=isset($_POST['number_wallet_push'])? addslashes($_POST['number_wallet_push']):"";
$number_wallet=str_replace(",", "", $number_wallet);
$secret_code=isset($_POST['secret_code_push'])? addslashes(trim($_POST['secret_code_push'])):"";
$note_push=isset($_POST['txt_note_push'])? addslashes(trim($_POST['txt_note_push'])):"";
$by_user=isset($_POST['by_user'])? addslashes(trim($_POST['by_user'])):"";
// check secret_code
if(in_array($secret_code,$arr_serec_code)){
    $arr2=$arr=array();
	if(MemberCountList(" AND `isactive`=1 AND `username`='$username'")==1){
        $sql="SELECT * FROM tbl_wallet_b WHERE isactive=1 AND TIMESTAMPDIFF(SECOND, cdate, now()) < 4";
        $objdata=new CLS_MYSQL();
        $objdata->Query($sql);
        $num=$objdata->Num_rows();
        if($num==''){// check quá x giây mới đc insert
			$note_push=$note_push!=''? "(".$note_push.")":'';
            $note="Nạp điểm vào tài khoản $note_push";
            $arr2['username']=$arr['username']=$username;
            $arr2['money']=$number_wallet;
            $arr2['status']=$arr['status']=1;
            $arr2['note']=$note;
            $arr2['cuser']=$by_user;
            $arr2['type']=1;
            $arr2['cdate']=$arr['mdate']=$arr['cdate']=time();
			$rs=updatePushWallet('tbl_wallet_b',$username,$number_wallet);
			$rs1=SysAdd('tbl_wallet_b_histories', $arr2);
			echo "success";
        }
    }
}else echo "error_secret";







 ?>

 