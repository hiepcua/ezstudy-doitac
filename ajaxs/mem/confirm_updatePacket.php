<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_pit.php');
require_once(incl_path.'gffunc_packet.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
$user=isset($_POST['user'])?antiData($_POST['user']):'';
if($user!=''){
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	$r=$obj[0];
	$thisPacket=$r['packet'];
	$par_user=$r['par_user'];
	$rep_user=$r['rep_user'];
	$loop=$r['loop'];
	$packet=$r['new_packet'];
	//-----------------------------------------------
	$amount=$packet-$thisPacket;
	// check balance pitnex
	$obj=SysGetList('tbl_member_wallet',array('wallet','privateKeysActive')," AND username='$user'");
	$myAddress=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
	$P_Balance=$myAddress!=''?getBalance(URL_NODEJS_SERVER,$myAddress):0;
	if($P_Balance>$amount && $amount>0){
		// update packet
		// tiến hành giao dịch
		$privateKey=$obj[0]['privateKeysActive'];
		$money=str_replace(',','',number_format($amount,3));
		$jsonBody = array(
			"private_key" => $privateKey,
			"from" => $myAddress,
			"to" => ROOT_WALLET,
			"quantity" => $money." PIT",
			"memo" => "Upgrade packet $packet by $user"
		);
		$resp = transferCoin(URL_NODEJS_SERVER, json_encode($jsonBody, JSON_UNESCAPED_UNICODE));
		if ($resp['status']){
			// update giao dịch
			$arr=array();
			$arr['username']=$user;
			$arr['type']='TRAN_SEND';
			$arr['from_wallet']=$myAddress;
			$arr['to_wallet']=ROOT_WALLET;
			$arr['txhash']=$resp['transaction_id'];
			$arr['amount']=$amount;
			$arr['memo']="Upgrade packet $packet by $user";
			$arr['cdate']=time();
			SysAdd('tbl_transaction',$arr);
			
			$arr['username']='system';
			$arr['type']='TRAN_RECEIVED';
			SysAdd('tbl_transaction',$arr);
			
			// update packet
			$arr=array();
			$arr['packet']=$packet;
			$arr['new_packet']=0;
			$arr['mdate']=time();
			$arr['loop']=$loop+1;
			$arr['isactive']=1;
			SysEdit('tbl_member_packet',$arr," username='$user'");
			$arr=array();
			$arr['username']=$user;
			$arr['packet']=$packet;
			$arr['cdate']=time();
			$arr['note']="Upgrade packet $packet";
			SysAdd('tbl_member_packet_histories',$arr);
			// tiến hành cập nhập hệ thống
			Bonus_tructiep($par_user,$user,$amount,2);
			hh_cancap($rep_user,$user,$amount);
			die('success');
		}else{
			file_put_contents('Upgrade_packet.log',date('Y-m-d H:i').json_encode($resp),FILE_APPEND);
			die('Upgrade failed! '.$resp['message']);
		}
	}else{
		die('The balance is not sufficient to make a transaction!');
	}
}
?>