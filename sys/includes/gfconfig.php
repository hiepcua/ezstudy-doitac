<?php
	$GLOBALS['ARR_ACTION'] = array(
		'view'		=>1,
		'add'		=>2,
		'edit'		=>4,
		'delete'	=>8
		);
	$GLOBALS['ARR_ACTION_NAME'] = array(
		'view'		=>'Xem',
		'add'		=>'Thêm',
		'edit'		=>'Sửa',
		'delete'	=>'Xóa'
		);
	$GLOBALS['ARR_COM'] = array(
		'config'	=>1,
		'gusers'	=>2,
		'user'		=>4,
		'member'		=>8,
		'member_packet'	=>16,
		'member_wallet'	=>32,
		'wallet_b'	=>64,
		'wallet_s'	=>128
		);
	$GLOBALS['ARR_COM_ACT'] = array(
		'config'	=>4, // edit
		'gusers'	=>15, 
		'user'		=>15, 
		'member'		=>15, 
		'member_packet'	=>15,
		'member_wallet'	=>15,
		'wallet_b'	=>15,
		'wallet_s'	=>15
		);
	$GLOBALS['ARR_COM_NAME'] = array(
		'config'	=>'Cấu hình',
		'gusers'	=>'Nhóm quyền',
		'user'		=>'Quản trị viên',
		'member'		=>'Thành viên',
		'member_packet'	=>'Gói thành viên',
		'member_wallet'	=>'Ví thành viên',
		'wallet_b'	=>'Business Wallet',
		'wallet_s'	=>'Stake Wallet'
		);
	$GLOBALS['MSG_PERMIS']='<div id="action" style="background-color:#fff; margin:10px 15px;"><h3 align="center">Bạn không có quyền truy cập.</h3></div>';
?>