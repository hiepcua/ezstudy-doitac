<?php
class CLS_CONFIG{
	private $pro=array(
		'Id'=>1,
		'Title'=>'',
		'Meta_key'=>'',
		'Meta_desc'=>'',
		'Logo'=>'',
		'Img'=>'',
		/*company*/
		'Phone'=>'',
		'Tel'=>'',
		'Fax'=>'',
        'Email'=>'',
        'Address'=>'',

		'Contact'=>'',
		'Footer'=>'',
		'Nich_yahoo'=>'',
		'Name_yahoo'=>''
	);
	private $objmysql=null;
	public function __construct(){
		$this->objmysql=new CLS_MYSQL;
		$this->objmysql->Query("SELECT * FROM tbl_configsite");
		$row=$this->objmysql->Fetch_Assoc();
		$this->Title=($row['title']!="")?$row['title']:SITE_TITLE;
		$this->Meta_desc=($row['meta_descript']!="")?$row['meta_descript']:SITE_DESC;
		$this->Meta_key=($row['meta_keyword']!="")?$row['meta_keyword']:SITE_KEY;
		$this->Contact=($row['contact']!="")?$row['contact']:COM_CONTACT;
		$this->Footer=$row['footer'];
		$this->Address=$row['address'];
		$this->Email=$row['email'];
		$this->Phone=$row['phone'];
		$this->Tel=$row['tel'];
		$this->Fax=$row['fax'];
		$this->Nich_yahoo=$row['nick_yahoo'];
		$this->Name_yahoo=$row['name_yahoo'];
		$this->Logo=$row['logo'];
	}
	// property set value
	function __set($proname,$value){
		if(!isset($this->pro[$proname])){
			echo "Error set: $proname không phải là thành viên của class configsite"; return;
		}
		$this->pro[$proname]=$value;
	}
	function __get($proname){
		if(!isset($this->pro[$proname])){
			echo ("Error get:$proname không phải là thành viên của class configsite" ); return;
		}
		return $this->pro[$proname];
	}
	public function getList($where=''){
		$sql="SELECT * FROM `tbl_configsite` where 1=1 ".$where;
		// echo $sql;
		return $this->objmysql->Query($sql);
	}
	public function Num_rows(){
		return $this->objmysql->Num_rows();
	}
	public function Fetch_Assoc(){
		return $this->objmysql->Fetch_Assoc();
	}
	function load_config(){
		$com=	isset($_GET['com'])?addslashes($_GET['com']):'';
		$viewtype=	isset($_GET['viewtype'])?addslashes($_GET['viewtype']):'';

		$code=		isset($_GET['code'])?addslashes($_GET['code']):'';
		$objcon=new CLS_CONTENTS;
		$objcate=new CLS_CATE();
		if($com=='contents'):
			if($viewtype=='detail'){
				$objcon->getList("WHERE `code`='$code'");
				$r_con=$objcon->Fetch_Assoc();
				if($r_con['meta_title']!='')
				$this->Title=stripslashes($r_con['meta_title']);
				else
				$this->Title=stripslashes($r_con['title']);

				$this->Meta_key=stripslashes($r_con['meta_key']);
				$this->Meta_desc=stripslashes($r_con['meta_desc']);

			}
           /* elseif($viewtype=='list'){
                $objcate->getList("WHERE `code`='$code'");
                $r_cate=$objcate->Fetch_Assoc();
                if($r_cate['meta_title']!='')
                    $this->Title=stripslashes($r_cate['meta_title']);
                else
                    $this->Title=stripslashes($r_cate['title']);
            }*/
            elseif($viewtype=='search'){
				$key='';
				if(isset($_GET['keyword']))
				$key=$_GET['keyword'];
				$this->Title="Tìm kiếm sản phẩm với từ khóa \"$key\"";
				$this->Meta_desc="";
			}else{}	
		endif;
	}
	function Update(){
		$sql='UPDATE `tbl_configsite` SET `title`="'.$this->pro["Title"].'",`meta_descript`="'.$this->pro["Meta_descript"].'",`meta_keyword`="'.$this->pro["Meta_keyword"].'",`email`="'.$this->pro["Email"].'",';
		$sql.='`logo`="'.$this->pro["Logo"].'",`nick_yahoo`="'.$this->pro["Nick_yahoo"].'",`name_yahoo`="'.$this->pro["Name_yahoo"].'",`contact`="'.$this->pro["Contact"].'",`footer`="'.$this->pro["Footer"].'",';
		$sql.='`phone`="'.$this->pro["Phone"].'",' ;
		$sql.='`fax`="'.$this->pro["Fax"].'",' ;
		$sql.='`tel`="'.$this->pro["Tel"].'"' ;
		$sql.=' WHERE `config_id`="'.$this->pro["Id"].'"';
		//echo $sql;
		$objdata=new CLS_MYSQL();
		if($objdata->Query($sql)) 
			return true;
		else return false;
	}
}
?>