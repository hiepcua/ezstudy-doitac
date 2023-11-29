<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(incl_path.'gffunc_wallet.php');

if(isLogin()){
	$this_user = getInfo('username');
	$item_obj = SysGetList('tbl_member', array('rates'), " AND username='$this_user'");
	$rates = isset($item_obj[0]['rates'])? $item_obj[0]['rates']:'';
	$table = "tbl_wallet_b";
	$total_wallet = countTotalWallet($table,$this_user);
	$money_packet = 0;

	// Thông tin tài khoản ez
	$username = isset($_POST['txt_username'])? addslashes($_POST['txt_username']):'';
	$member_info = api_get_member_info($username);

	// Thông tin packet
	$packet_group = isset($_POST['txt_packet'])? addslashes($_POST['txt_packet']):'';
	$packet_month = isset($_POST['txt_month'])? addslashes($_POST['txt_month']):'';
	$packet_conf = api_get_packet_service_grade($packet_group, $member_info['grade']);
	$money_packet = 0;

	if(is_array($packet_conf) && count($packet_conf)>0){
		$packet_grade = isset($packet_conf[$packet_group]['packet_grade']) ? $packet_conf[$packet_group]['packet_grade']:array();

		foreach ($packet_grade as $key => $value) {
			if($value['id'] == $packet_month)
				$money_packet = $value['money'];
		}
	}else {
    	die('Không có cấu hình gói dịch vụ');
    }

	$month = (int)$packet_month;
	$num_day = $month * 30;
	$price_per_day = ceil($money_packet/$num_day);
	if($money_packet<1) die('Số tiền không tồn tại!');
	if($total_wallet<=$money_packet) die('Số dư không đủ để thực hiện giao dịch!');
	else{
		// API đăng ký gói dịch vụ
		$res_reg = api_packet_reg($username, $packet_group, $month, $money_packet, 1);

		// API tạo nhiệm vụ cho tài khoản học sinh
		$res_create_work = api_create_work($username);

		if($res_reg=="success" && $res_create_work=="success"){
			$endtime = strtotime("+$num_day day");
			// Cập nhật lại cho tài khoản học sinh
			$json = array();
			$json['packet'] = $packet_group;
			$json['edate'] = $endtime;
			$json['pdate'] = time();
			$json['packet_status'] = 'running';
			$json['price_per_day'] = $price_per_day;
			$json['packet_month'] = $month;
			api_update_member($username, $json);
			unset($json);

			// Trừ tiền ví
			$type_tile = 1;
			$arr2 = array();
			$arr2['cuser'] 	= $arr2['username'] = $this_user;
			$arr2['money'] 	= -1*$money_packet;
			$arr2['type']  	= 3; // 3 là gia hạn của ví B
			$arr2['status'] = $arr['status']=1;
			$arr2['cdate'] 	= $arr['cdate']=time();
			$arr2['note'] 	= "Đăng ký gói dịch vụ ".$packet_group." thời gian ".$packet_month;

			$rs1=updatePayWallet('tbl_wallet_b',$this_user,$money_packet);
			$rs2=SysAdd('tbl_wallet_b_histories',$arr2,1);
			unset($arr2);

			// --------------------------------------
			$arr = array();
			$ti_le_thuong_kichhoat=getTiLeThuong($this_user,$rates, 1); // 1 là kích hoạt, 2 là gia hạn, 3 là liên kết
			$ti_le_thuong_ketnoi=getTiLeThuong($this_user,$rates, 3);
			$money_kichhoat=$ti_le_thuong_kichhoat*$money_packet*0.01;
			$money_ketnoi=$ti_le_thuong_ketnoi*$money_packet*0.01;
			
			$arr['username'] 	= $this_user;
			$arr['cdate'] 		= time();
			$arr['status'] 		= 1;
			$arr['money'] 		= $money_kichhoat;
			$arr['type'] 		= $type_tile;
			$arr['note'] 		= "Thưởng kích hoạt gói dịch vụ ".$packet_group." của tài khoản ".$username;
			$arr['cuser'] 		= $this_user;
			updatePushWallet('tbl_wallet_s',$this_user,$money_kichhoat);
			SysAdd('tbl_wallet_s_histories',$arr);

			$arr['type'] = 3;
			$arr['note'] = 'Thưởng liên kết bởi tài khoản '.$username;
			updatePushWallet('tbl_wallet_s',$ref_user,$money_ketnoi);
			$arr['money'] = $money_ketnoi;
			SysAdd('tbl_wallet_s_histories',$arr);
			unset($arr); 
			unset($rep);
			echo 'success';
		}else if($res_reg=="exist"){
			echo 'Đã có yêu cầu nâng cấp dịch vụ. Vui lòng chờ xác nhận';
		}
		else echo 'Lỗi kết nối với hệ thống khác';
	}
}
else{
    die('Vui lòng đăng nhập hệ thống');
}
?>
