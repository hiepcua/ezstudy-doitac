<?php
class CLS_CARD{
	private $pro=array(
					  'ID'=>'-1',
					  'ParID'=>'0',
					  'Name'=>'',
					  'Intro'=>'',
					  'isAdmin'=>'1',
					  'isActive'=>1
					  );
	private $objmysql=NULL;
	public function __construct(){
		$this->objmysql=new CLS_MYSQL;
	}
	// property set value
	public function __set($proname,$value){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_CARD Class for set' );
			return;
		}
		$this->pro[$proname]=$value;
	}
	public function __get($proname){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_CARD Class for get' );
			return '';
		}
		return $this->pro[$proname];
	}
	public function getList($where='',$limit=''){
		$sql='SELECT `tbl_card`.*,`tbl_card_transection`.cdate as `mdate`,`tbl_card_transection`.`content` 
				FROM `tbl_card` LEFT JOIN tbl_card_transection ON tbl_card_transection.cardcode=tbl_card.cardcode 
				'.$where.$limit;
		return $this->objmysql->Query($sql);
	}
	public function getMemberUsed($cardcode){
		$sql='SELECT `member` FROM `tbl_card_transection` WHERE `cardcode`="'.$cardcode.'"';
		$objdata=new CLS_MYSQL;
		$objdata->Query($sql);
		$r=$objdata->Fetch_Assoc(); 
		return $r['member']; 
	}
	public function Num_rows(){
		return $this->objmysql->Num_rows();
	}
	public function Fetch_Assoc(){
		return $this->objmysql->Fetch_Assoc();
	}
	public function checkActivePacket($code,$packet){
		$sql="SELECT * FROM `tbl_card` WHERE `cardcode`='$code' AND `packet`=$packet AND `status`=0";
		$objdata=new CLS_MYSQL;
		$objdata->Query($sql);
		if($objdata->Num_rows()==1) return true;
		else return false;
	}
	public function UseCode($code){
		$sql="UPDATE tbl_card SET `status`=1 WHERE cardcode='$code'";
		$objdata=new CLS_MYSQL;
		$objdata->Exec($sql);
	}
	private function getCardNumber(){
		$thisdate=date('Ymd');
		$thisdate=substr($thisdate,2,6);
		$code=substr($thisdate,0,3).rand(100000,999999).substr($thisdate,3,3);
		return $code;
	}
	public function Add_new($packet,$num,$author){
		if($packet<0 || $num==0) return false;
		$sql="INSERT INTO `tbl_card`(`cardcode`,`packet`,`cdate`,`author`) VALUES ";
		$cdate=mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		for($i=0;$i<$num;$i++){
			$code=$this->getCardNumber();
			$sql.="('$code','$packet','$cdate','$author'),";
		}
		$sql=substr($sql,0,-1);
		return $this->objmysql->Query($sql);
	}
	public function Delete($id){
		$sql="DELETE FROM `tbl_card` WHERE `cardcode` in ('$id') AND status=0";
		return $this->objmysql->Query($sql);
	}
	public function setActive($ids,$status=''){
		$sql="UPDATE `tbl_card` SET `isactive`='$status' WHERE `cardcode` in ('$ids')";
		if($status=='')
			$sql="UPDATE `tbl_card` SET `isactive`=if(`isactive`=1,0,1) WHERE `cardcode` in ('$ids')";
		return $this->objmysql->Exec($sql);
	}
}
?>