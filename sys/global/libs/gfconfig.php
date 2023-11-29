<?php
function isSSL(){
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return true;
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') return true;
	else return false;
}
$REQUEST_PROTOCOL = isSSL()? 'https://' : 'http://';
define('ROOTHOST',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('ROOTHOST_ADMIN',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/sys/');
define('WEBSITE',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('DOMAIN','pitnex.com');
define('ROOT_IMAGE','/home/admin/web/pitnex.com/public_html/');
define('ROOT_MEDIA','/home/admin/web/pitnex.com/public_html/uploads/media/');
define('BASEVIRTUAL0','/home/admin/web/pitnex.com/public_html/uploads/');
define('MEDIA_HOST',ROOTHOST.'uploads/media/');
define('IMAGE_HOST',ROOTHOST.'uploads/media/');
define('AVATAR_DEFAULT',ROOTHOST.'images/avatar/default.jpg');

define('PIT_API_KEY','6b73412dd2037b6d2ae3b2881b5073bc');

// ---------------DEFINE CONSTANT API----------------
define("API_GET_INFO_ETH", 'https://api.coinmarketcap.com/v1/ticker/ethereum/');
define("API_GET_INFO_TRANSACTION_ETHERCHAIN", 'https://www.etherchain.org/api/tx/');
define("API_GET_INFO_TRANSACTION_FROM_ETHERSCAN", 'https://api.etherscan.io/api?module=proxy&action=eth_getTransactionByHash&txhash=');
define("URL_NODEJS_SERVER", '45.77.252.213:3001');
	
define('APP_ID','1663061363962371');
define('APP_SECRET','dd0b6d3fb803ca2a51601145a74fd9a8');

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

define('MAX_ROWS',50);
define('MAX_ROWS_ADMIN',50);
define('MAX_ROWS_INDEX',50);

define('ADMIN_LOGIN_TIMEOUT',-1);
define('URL_REWRITE','1');
define('USER_TIMEOUT',6000);
define('MEMBER_TIMEOUT',10000);
define('ACTION_TIMEOUT',600);
define('MEMBER_STATUS',1);
define('MEMBER_ROOT','dxdpro');
define('NAME_2FA','Pitnex.com');
define('KEY_AUTHEN_COOKIE','PIT_260584');

define('SMTP_SERVER','smtp.gmail.com');
define('SMTP_PORT','465');
define('SMTP_USER','hoangtucoc321@gmail.com');
define('SMTP_PASS','nsn2651984');
define('SMTP_MAIL','hoangtucoc321@gmail.com');

define('SITE_NAME','PITNEX');
define('SITE_TITLE','PITNEX');
define('SITE_DESC','');
define('SITE_KEY','');
define('SITE_IMAGE','');
define('SITE_LOGO','');
define('COM_NAME','Copyright &copy; IGF.COM.VN');
define('COM_CONTACT','');
$_FILE_TYPE=array('docx','excel','pdf');
$_MEDIA_TYPE=array('mp4','mp3');
$_IMAGE_TYPE=array('jpeg','jpg','gif','png');
$_PACKET=array(
	"P100"=>array('name'=>'NDT cấp 1','price'=>'100','day_rate'=>0,'commiss'=>0.045,'sales'=>0.05,'max_reward'=>4,'max_day'=>365),
	"P500"=>array('name'=>'NDT cấp 1','price'=>'500','day_rate'=>1.58,'commiss'=>0.055,'sales'=>0.05,'max_reward'=>4,'max_day'=>365),
	"P1000"=>array('name'=>'NDT cấp 2','price'=>'1000','day_rate'=>3.25,'commiss'=>0.065,'sales'=>0.05,'max_reward'=>4,'max_day'=>365),
	"P2500"=>array('name'=>'NDT cấp 3','price'=>'2500','day_rate'=>8.25,'commiss'=>0.07,'sales'=>0.05,'max_reward'=>4,'max_day'=>365),
	"P5000"=>array('name'=>'NDT cấp 4','price'=>'5000','day_rate'=>17.12,'commiss'=>0.08,'sales'=>0.05,'max_reward'=>4,'max_day'=>365),
	"P10000"=>array('name'=>'NDT cấp 5','price'=>'10000','day_rate'=>17.12,'commiss'=>0.08,'sales'=>0.05,'max_reward'=>4,'max_day'=>365)
);
$_UNIT='PIT';
$_WITHDRAW=7;
$arr_serec_code=array('999999','899989','397999','6868999','886833');
$arr_token=array('1234554321', '123456123456', '123456789');
?>