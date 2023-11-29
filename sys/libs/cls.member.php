<?php
class CLS_MEMBER{
	private $pro=array(
					  'Username'=>'',
					  'Password'=>'',
					  'Par_User'=>'0',
					  'Fullname'=>'',
					  'Birthday'=>'',
					  'Address'=>'',
					  'Phone'=>'',
					  'Email'=>'',
					  'CMT'=>'',
					  'CMT_date'=>'',
					  'CMT_place'=>'',
					  'KyC1'=>'',
					  'KyC2'=>'',
					  'isKYC'=>'',
					  'Packet'=>'',
					  'isRoot'=>'',
					  'is2FA'=>'',
					  'gSecret'=>'',
					  'Cdate'=>'',
					  'isActive'=>1
					  );
	private $objmysql=NULL;
	public function __construct(){
		$this->objmysql=new CLS_MYSQL;
	}
	// property set value
	public function __set($proname,$value){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_MEMBER Class' );
			return;
		}
		$this->pro[$proname]=$value;
	}
	public function __get($proname){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_MEMBER Class' );
			return '';
		}
		return $this->pro[$proname];
	}
	public function getList($where='',$limit=''){
		$sql='SELECT * FROM `tbl_member` WHERE 1=1 '.$where.$limit;
		return $this->objmysql->Query($sql);
	}
	public function Num_rows(){
		return $this->objmysql->Num_rows();
	}
	public function Fetch_Assoc(){
		return $this->objmysql->Fetch_Assoc();
	}
	function setActive($usernames,$status=''){
		$sql="UPDATE `tbl_member` SET `isactive`='$status' WHERE `username` in ('$usernames')";
		if($status=='')
			$sql="UPDATE `tbl_member` SET `isactive`=if(`isactive`=1,0,1) WHERE `username` in ('$usernames')";
		return $this->objmysql->Exec($sql);
	}
	function Add_new(){
		$sql="INSERT INTO `tbl_member`(`par_user`,`username`,`password`,`fullname`,`birthday`,`address`,`phone`,`email`,cmt,cmt_date,cmt_place,kyc1,kyc2,iskyc,packet,isroot,is2fa,gsecret,cdate,`isactive`) VALUES ";
		$sql.=" ('".$this->pro["Par_User"]."','".$this->pro["Username"]."','".$this->pro["Password"]."','".$this->pro["Fullname"]."','".$this->pro["Birthday"]."','".$this->pro["Address"]."','".$this->pro["Phone"]."','".$this->pro["Email"]."','".$this->pro["CMT"]."','".$this->pro["CMT_date"]."','".$this->pro["CMT_place"]."','".$this->pro["KyC1"]."','".$this->pro["KyC2"]."','".$this->pro["isKYC"]."','".$this->pro["Packet"]."','".$this->pro["isRoot"]."','".$this->pro["is2FA"]."','".$this->pro["Gsecret"]."','".$this->pro["Cdate"]."','".$this->pro["isActive"]."') ";
		return $this->objmysql->Exec($sql);
	}
	function Update(){
		$sql="UPDATE `tbl_member` SET `par_user`='".$this->pro["Par_User"]."',
				`fullname`='".$this->pro["Fullname"]."',
				`birthday`='".$this->pro["Birthday"]."',
				`address`=".$this->pro["Address"].",
				`phone`=".$this->pro["Phone"].",
				`email`=".$this->pro["Email"].",
				`cmt`='".$this->pro["CMT"]."',
				`cmt_date`='".$this->pro["CMT_date"]."',
				`cmt_place`='".$this->pro["CMT_place"]."',
				`kyc1`='".$this->pro["KyC1"]."',
				`kyc2`='".$this->pro["KyC2"]."',
				`iskyc`='".$this->pro["isKYC"]."',
				`packet`='".$this->pro["Packet"]."',
				`isroot`='".$this->pro["isRoot"]."',
				`is2fa`='".$this->pro["is2FA"]."',
				`gsecret`='".$this->pro["Gsecret"]."',
				`isactive`='".$this->pro["isActive"]."' ";
		$sql.=" WHERE `username`='".$this->pro["Username"]."'";
		return $this->objmysql->Exec($sql);
	}
	function Delete($Username){
		$sql="DELETE FROM `tbl_member` WHERE `username` in ('$username')";
		return $this->objmysql->Query($sql);
	}
}
?>