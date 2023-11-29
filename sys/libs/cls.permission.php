<?php
class CLS_PERMISSION{
	private $pro=array(
		'Id'=>0,
		'Name'=>'',
		'GUser_id'=>'',
		'Intro'=>'',
		'isactive'=>1
		);
	private $objmysql=NULL;
	public function __construct(){
		$this->objmysql=new CLS_MYSQL;
	}
	// property set value
	public function __set($proname,$value){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_PERMISSION Class' );
			return;
		}
		$this->pro[$proname]=$value;
	}
	public function __get($proname){
		if(!isset($this->pro[$proname])){
			echo ($proname.' is not member of CLS_PERMISSION Class' );
			return '';
		}
		return $this->pro[$proname];
	}

	public function getList($where='',$limit=''){
		$sql="SELECT * FROM `tbl_permission` WHERE 1=1 ".$where." ORDER BY `name` ".$limit;
		return $this->objmysql->Query($sql);
	}
	public function Num_rows() { 
		return $this->objmysql->Num_rows();
	}
	public function Fetch_Assoc(){
		return $this->objmysql->Fetch_Assoc();
	}
	//-------------------------------------------------------
	public function getGroupPermission(){
		$sql="SELECT * FROM `tbl_permission` WHERE `isactive`=1";
		$objdata=new CLS_MYSQL();
		$objdata->Query($sql);
		if($objdata->Num_rows()){
			echo "<ul class='menu'>";
			while($r=$objdata->Fetch_Assoc()){
				$id=$r["id"];
				$name=$r["name"];
				echo "<li><a href='javascript:void(0);' onclick='user_group_select(this);' dataid='$id'>$name</a></li>";
			}
			echo "</ul>";
		}
	}
	public function getIDByGUserID($guser_id){
		$sql="SELECT id FROM tbl_permission WHERE `guser_id` LIKE '%$guser_id,%' ";
		$objdata = new CLS_MYSQL();
		$objdata->Query($sql);
		$permis_id=array();
		if($objdata->Num_rows()>0){
			while ($row = $objdata->Fetch_Assoc()) {
				$permis_id[] = $row['id'];
			}
		}
		return $permis_id;
	}
	public function getListCheckBox($ids){
		$sql="SELECT * FROM tbl_permission WHERE isactive=1";
		$objdata = new CLS_MYSQL();
		$objdata->Query($sql);
		if($objdata->Num_rows()>0){
			while ($row = $objdata->Fetch_Assoc()) {
				$id = (int)$row['id'];
				$name = stripslashes($row['name']);
				if(in_array($id,$ids)){
					echo '<label class="checkbox-inline"><input type="checkbox" value="'.$id.'" name="chk" id="chk" onclick="docheckonce(\'chk\');" checked>'.$name.'</label>';
				}else{
					echo '<label class="checkbox-inline"><input type="checkbox" value="'.$id.'" name="chk" id="chk" onclick="docheckonce(\'chk\');">'.$name.'</label>';
				}
			}
		}
	}
	public function checkGuserInGuser_id($guser_id){
		$sql="SELECT id FROM tbl_permission WHERE guser_id LIKE '%$guser_id%'";
		$objdata = new CLS_MYSQL();
		$objdata->Query($sql);
		$flag=false;
		if($objdata->Num_rows()>0) $flag=true;
		return $flag;
	}
}