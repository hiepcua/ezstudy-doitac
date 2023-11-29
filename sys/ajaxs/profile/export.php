<?php
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");

$keyword=isset($_POST['key'])?addslashes(strip_tags($_POST['key'])):'';
$obj=new CLS_MYSQL;
$sql="SELECT * FROM tbl_profile WHERE 1=1 ";
if($keyword!='') $sql.=" AND ( `fullname` like '%$keyword%' OR `household` like '%$keyword%' OR `address` like '%$keyword%')";
$obj->Query($sql); 
$arr_excel=array();
$arr_excel[]=array("#","Ngày đăng ký","Họ và tên","Ngày sinh","Giới tính","CMTND",
"Hộ khẩu thường trú","Địa chỉ","Email","Điện thoại","Khoa/Ngành","Năm tốt nghiệp",
"Chăm sóc","Đỗ/Trượt");
$stt=0;
while($row=$obj->Fetch_Assoc()){
	$stt++;
	$cdate=date("d/m/Y H:i:s",strtotime($row['cdate']));
	$fullname = stripslashes($row['fullname']);
	$household = stripslashes($row['household']);
	$address = stripslashes($row['address']);
	$email = stripslashes($row['email']);
	$birthday  = $row['birthday'];
	$gender  = $row['gender'];
	$cmtnd  = $row['cmtnd'];
	$tel = $row['tel'];
	$branch = stripslashes($row['branch']);
	$year = (int)$row['year'];
	$take_care = (int)$row['take_care']==0?'':'Có';
	$pass = (int)$row['pass']==0?'':'Đỗ';
	$arr_excel[]=array("$stt","$cdate","$fullname","$birthday","$gender","$cmtnd",
	"$household","$address","$email","$tel","$branch","$year","$take_care","$pass");
}
$my_file = "../../temp/profile.csv";
$f = fopen("$my_file", "w") or die('Cannot open file:  '.$my_file);
//This line is important:
fputs( $f, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!

foreach ($arr_excel as $line) {
	fputcsv($f, $line);
}
fclose($f);
header('Content-Encoding: UTF-8');
mb_internal_encoding('UTF-8');
header('Content-Description: File Transfer');
header("content-type:application/csv;charset=UTF-8");
header('Content-Disposition: attachment; filename="'.basename($my_file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($my_file));
//readfile($my_file);
echo ROOTHOST_ADMIN."temp/profile.csv";
?>