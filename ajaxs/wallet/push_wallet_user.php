<?php 
include_once('../../../includes/gfconfig.php');
include_once('../../../includes/gfinnit.php');
include_once('../../../includes/gffunction.php');
include_once('../../libs/cls.mysql.php');
include_once('../../libs/cls.configsite.php');
include_once('../../libs/cls.billwallet.php');
include_once('../../libs/cls.wallet.php');
$infor_config=array('123', '345');
$secret_code_config=explode(',',trim($infor_config['secret_code']));
$username=isset($_POST['txtcustomer_push'])? addslashes(trim($_POST['txtcustomer_push'])):"";
$number_wallet=isset($_POST['number_wallet_push'])? addslashes($_POST['number_wallet_push']):"";
$number_wallet=str_replace(",", "", $number_wallet);
$secret_code=isset($_POST['secret_code_push'])? addslashes(trim($_POST['secret_code_push'])):"";
$note_push=isset($_POST['txt_note_push'])? addslashes(trim($_POST['txt_note_push'])):"";
$by_user=isset($_POST['by_user'])? addslashes(trim($_POST['by_user'])):"";
// check secret_code
if(in_array($secret_code,$secret_code_config)){
    $arr2=$arr=array();
	if(MemberCountList(" AND `isactive`=1 AND `username`='$username'")!=1){
        $sql="SELECT * FROM tbl_wallet_b WHERE isactive=1 AND TIMESTAMPDIFF(SECOND, cdate, now()) < 4";
        $objdata=new CLS_MYSQL();
        $objdata->Query($sql);
        $num=$objdata->Num_rows();
        if($num==''){// check quá x giây mới đc insert
            $note="Nạp tiền vào tài khoản thông qua yêu cầu ($note_push)";
            $arr2['username']=$arr['username']=$username;
            $arr2['money']=$arr['money']=$number_wallet;
            $arr2['status']=$arr['status']=1;
            $arr2['note']=1;
            $arr2['type']=1;
            $arr2['cdate']=$arr['mdate']=$arr['cdate']=time();
            $rs=SysAdd('tbl_wallet_b', $arr);
            $rs=SysAdd('tbl_wallet_b_histories', $arr2);
            if($objdata->Query($sql)){
                echo "success";
            };
        }
    }
}else echo "error_secret";







 ?>

 