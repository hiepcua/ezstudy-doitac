<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
if(isLogin()){
	$this_user = getInfo('username');
	$packet_id = isset($_POST['packet_id'])? antiData($_POST['packet_id']):1;
	$username = isset($_POST['username'])? antiData($_POST['username']):"";

	// Get info username
	$url = API_DC.'member-info';
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['username'] = $username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep_mem = Curl_Post($url,json_encode($post_data));
	$arr_mem = $rep_mem['data'];
	$grade_id = $arr_mem['grade']!="" ? $arr_mem['grade']:"";
	
	$url = API_DC.'list-packet';
	$arr = array();
	$arr['key'] = PIT_API_KEY;
	$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post($url,json_encode($post_data));
	
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arr_packet_config = json_decode($rep['data'][$packet_id]['packet'],true);
		if(isset($arr_packet_config[$grade_id])){
			foreach($arr_packet_config[$grade_id] as $key => $value){
				$content = 'Gói '.$key.' tháng - <span class=label>'.number_format($value['money']).'đ</span>';
				echo '<option value="'.$key.'" data-id="'.number_format($value['money']).'">'.$content.'</option>';
			}
		}
	}
}
?>
