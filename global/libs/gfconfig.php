<?php
function isSSL(){
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return true;
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') return true;
	else return false;
}
$REQUEST_PROTOCOL = isSSL()? 'https://' : 'http://';
define('ROOTHOST',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('ROOTHOST_PATH',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/tooltracnghiem/');
define('ROOTHOST_ADMIN',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/sys/');
define('WEBSITE',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('DOMAIN','partner.ezstudy.vn');
define('PARTNER_CODE','DT_01');
define('MEDIA_HOST',ROOTHOST.'uploads/media/');
define('IMAGE_HOST',ROOTHOST.'uploads/media/');
define('AVATAR_DEFAULT',ROOTHOST.'images/avatar/default.jpg');

define('PIT_API_KEY','6b73412dd2037b6d2ae3b2881b5073bc');
define('DOMAIN_VSTUDY','https://hoctap.ezstudy.vn/');
define('DOMAIN_EZSTUDY','https://dc.ezstudy.vn/');
define('API_DC','https://dc.ezstudy.vn/api/doitac/');
define('DATE_EXTEND','10'); 
define('MAX_ROWS','10'); 
define('ROOT_PATH',''); 
define('TEM_PATH',ROOT_PATH.'templates/');
define('COM_PATH',ROOT_PATH.'components/');
define('MOD_PATH',ROOT_PATH.'modules/');
define('INC_PATH',ROOT_PATH.'includes/');
define('LAG_PATH',ROOT_PATH.'languages/');
define('EXT_PATH',ROOT_PATH.'extensions/');
define('EDI_PATH',EXT_PATH.'editor/');
define('DOC_PATH',ROOT_PATH.'documents/');
define('DAT_PATH',ROOT_PATH.'databases/');
define('IMG_PATH',ROOT_PATH.'images/');
define('MED_PATH',ROOT_PATH.'media/');
define('LIB_PATH',ROOT_PATH.'libs/');
define('JSC_PATH',ROOT_PATH.'js/');
define('LOG_PATH',ROOT_PATH.'logs/');

define('ADMIN_LOGIN_TIMEOUT',-1);
define('URL_REWRITE','1');
define('USER_TIMEOUT',6000);
define('MEMBER_TIMEOUT',10000);
define('ACTION_TIMEOUT',600);
define('MEMBER_STATUS',1);
define('MEMBER_ROOT','');
define('NAME_2FA','');
define('KEY_AUTHEN_COOKIE','EZ_260584');

define('SMTP_SERVER','smtp.gmail.com');
define('SMTP_PORT','465');
define('SMTP_USER','hoangtucoc321@gmail.com');
define('SMTP_PASS','nsn2651984');
define('SMTP_MAIL','hoangtucoc321@gmail.com');

define('SITE_NAME','PMS | PARTNER MANAGER SYSTEM');
define('SITE_TITLE','PMS | PARTNER MANAGER SYSTEM');
define('SITE_DESC','');
define('SITE_KEY','');
define('SITE_IMAGE','');
define('SITE_LOGO','');
define('COM_NAME','Copyright &copy; IGF.COM.VN');
define('COM_CONTACT','');
$_FILE_TYPE=array('docx','excel','pdf');
$_MEDIA_TYPE=array('mp4','mp3');
$_IMAGE_TYPE=array('jpeg','jpg','gif','png');
$_conf_packet=array('EZ1','EZ2','EZ3');

$_PACKET_STATUS=array(
	'running'=>"Đang hoạt động",
	'pending'=>"Tạm dừng",
	'expire'=>"Hết hạn",
	'lock'=>"Tạm khóa",
);

// Config API url
// Service
define('API_SERVICE_ADD_ORDER',DOMAIN_EZSTUDY.'api/add-order-service'); 

// Member
define('API_GET_MEMBER',DOMAIN_EZSTUDY.'api/list-member');
define('API_MEMBER_EDIT',DOMAIN_EZSTUDY.'api/member-edit');
define('API_MEMBER_UPDATE_INFO',DOMAIN_EZSTUDY.'api/member-update-info'); 
define('API_MEMBER_INFO',DOMAIN_EZSTUDY.'api/member-info'); 
define('API_MEMBER_CHILD',DOMAIN_EZSTUDY.'api/list-member-child'); 
define('API_MEMBER_LEARNING_HISOTY',DOMAIN_EZSTUDY.'api/member-learning-history'); 

// List Packet
define('API_PACKET',DOMAIN_EZSTUDY.'api/doitac/list-packet'); 
define('API_PACKET_REG',DOMAIN_EZSTUDY.'api/doitac/reg_packet'); // Đăng ký | Gia hạn dịch vụ
define('API_CREATE_WORK',DOMAIN_EZSTUDY.'api/doitac/create_work'); // Tạo nhiệm vụ cho tài khoản

// Đăng ký mới gói dịch vụ hoặc gia hạn gói dịch vụ
function api_add_order_service($member="", $packet="", $month="", $price="", $type=1){ 
	// $type = 0 : Đăng ký mới | 1 : Gia hạn thêm
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['member'] = $member;
	$json['packet'] = $packet;
	$json['month'] = $month;
	$json['price'] = $price;
	$json['cdate'] = time();
	$json['status'] = "L0";
	if($type==1)
		$json['note'] = "Đăng ký mới";
	else
		$json['note'] = "Gia hạn thêm";
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$req = Curl_Post(API_SERVICE_ADD_ORDER, json_encode($post_data));
	return $req['data'];
}

// Lấy thông tin member
function api_get_member_info($member){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_INFO,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

// Lấy tất cả tài khoản con
function api_get_child_member($member){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_CHILD,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

// Cập nhật thông tin member
function api_update_member($username, $arr = array()){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$json['arr'] = $arr;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_UPDATE_INFO,json_encode($post_data)); 
	if($rep['status']=="yes" && $rep['data']=="success") {
		return true;
	}
	return false;
}

// Lấy danh sách gói dịch vụ
function api_get_packet_service($packet_id=""){
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['id'] = $packet_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_PACKET,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

// Chi tiết một gói dịch vụ
function api_get_packet_service_grade($packet_id="",$grade=""){ // $packet_id: Bắt buộc
	if($packet_id!=""){
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['id'] = $packet_id;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_PACKET,json_encode($post_data));

		$packet_grade = null;
		if(is_array($rep['data']) && count($rep['data']) > 0) {
			$item = $rep['data'];
			if($grade!=""){
				$packet_conf = isset($item[$packet_id]['packet']) && $item[$packet_id]['packet']!="" ? json_decode($item[$packet_id]['packet'],true):array();
				$packet_grade = isset($packet_conf[$grade]) ? $packet_conf[$grade]:array();
			}
		}
		$item[$packet_id]['packet_grade'] = $packet_grade;
		return $item;
	}
	return null;
}

// API đăng ký gói dịch vụ hoặc gia hạn dịch vụ
// $type = 1: Đăng ký mới | 2: Gia hạn
function api_packet_reg($username="",$packet="",$month="",$price="",$type=1){
	$json = array();
	$json['key']   	= PIT_API_KEY;
	$json['member'] = $username;
	$json['packet'] = $packet;
	$json['month']  = $month;
	$json['price']  = $price;
	$json['type']  	= $type;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_PACKET_REG, json_encode($post_data));
	return $rep['data'];
}

// API tạo nhiệm vụ cho học sinh
function api_create_work($username=""){
	if($username!=""){
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] = $username;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_CREATE_WORK, json_encode($post_data));
		return $rep['data'];
	}else{
		return 'error';
	}
}