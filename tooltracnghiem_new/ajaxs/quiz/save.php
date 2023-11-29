<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$url='https://ezstudy.ecos.asia/api/exam';
$data=array();
$post_data=$arr = array();
$arr['key']   = PIT_API_KEY;
$arr['exam_id']=$exam_id;
$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post($url,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0){
	$data[$exam_id]=$rep['data'];
	$info_exam=$rep['data_exam'][0];
	$data['info_exam']=$info_exam;
	//$data['data_answer']=array();
	//$data['isview']=0;
	$data['time_start']=time();
}
if(count($data)>0) echo json_encode($data);
else echo '1';

?>

