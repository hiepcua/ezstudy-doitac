<?php
function ActivePacket($user,$by_user){
	$this->hh_thuong_truc_tiep($parcode,$code,$packet);
}
function Bonus_Daily(){
	global $_PACKET;
	$obj=SysGetList('tbl_member_packet',array()," AND isactive=1",false);
	while($r=$obj->Fetch_Assoc()){
		$user=$r['username'];
		$userPacket=$r['packet'];
		$day=$r['day']+1;
		$loop=$r['loop'];
		$money=$_PACKET["P".$userPacket]['day_rate'];
		$max_day=$_PACKET["P".$userPacket]['max_day'];
		$arr=array();
		$arr['username']=$user;
		$arr['money']=$money;
		$arr['type']=1;
		$arr['cdate']=time();
		$arr['note']="Reward daily of day $day (loop $loop)";
		SysAdd('tbl_wallet_a',$arr);
		// update day
		$arr=array();
		$arr['day']=$day;
		SysEdit('tbl_member_packet',$arr," username='$user'");
		// check max day
		if($day>=$max_day){
			SysEdit('tbl_member_packet',array('isactive'=>0)," username='$user'");
		}
	}
}
function Bonus_tructiep($user,$by_user,$packet,$type){
	if($user=='root' || $user=='') return;
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	if(count($obj)<=0) return;
	$thisUser=$obj[0];
	$userPacket=$thisUser['packet'];
	global $_PACKET;
	$money=$_PACKET["P".$userPacket]['commiss']*$packet;  // người được nhận ở mức nào thì nhận mức đấy
	if($type==1) $note="Active packet $packet PIT for member $by_user";
	elseif($type==2) $note="Upgrade packet for member $by_user";
	elseif($type==3) $note="Re Active packet for member $by_user";
	else $note='';
	if($money>0){
		$arr=array();
		$arr['username']=$user;
		$arr['money']=$money;
		$arr['type']=$type;
		$arr['cdate']=time();
		$arr['note']=$note;
		SysAdd('tbl_wallet_b',$arr);
	}
}
function hh_cancap($user,$by_user,$packet){ // sales là doanh số
	if($user=='root' || $user=='') return;
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	$this_user=$obj[0];
	$sales=getSales($user,$by_user,$packet);
	if($sales>0){
		global $_PACKET;
		$userPacket=$this_user['packet'];
		$money=$_PACKET["P".$userPacket]['sales']*$sales;  // người được nhận ở mức nào thì nhận mức đấy
		$arr=array();
		$arr['username']=$user;
		$arr['money']=$money;
		$arr['type']=4;
		$arr['cdate']=time();
		$arr['note']='Rewards for member growth';
		$date=time();
		$note='Thưởng phát triển nhánh yếu';
		if($money>0){
			SysAdd('tbl_wallet_b',$arr);
		}
	}
	$rep_user=$this_user['rep_user'];
	$this->hh_cancap($rep_user,$by_user,$packet);
}
function getSales($user,$new_user,$packet){
	// check node 
	$obj=SysGetList('tbl_member_packet',array()," AND rep_user='$user'",false);
	if($obj->Num_rows()<2) return -1;
	$r1=$obj->Fetch_Assoc();
	$ds1=getSelesByPath($r1['path']);
    $r2=$obj->Fetch_Assoc();
	$ds2=getSelesByPath($r2['path']);
	if($ds1!=0 || $ds2!=0){
		if($this->isChildenPath($r1['path'],$new_user)){// thêm vào nhánh 1
			if($ds1-$packet >=$ds2)	return -1;					// thêm vào nhánh mạnh
			if($ds1-$packet < $ds2){ 							 
				if($ds1<=$ds2) 	return $packet; 				// "thêm vào nhánh yếu vẫn yếu";
				if($ds1>$ds2)	return $ds2-($ds1-$packet); 	// "thêm vào nhánh yếu thành mạnh";
				if($ds1==$ds2) 	return $packet;					// "thêm vào nhánh yếu = nhánh mạnh";
			}
		}
		if($this->isChildenPath($r2['path'],$new_user)){		// 'thêm vào nhánh 2';
			if($ds2-$this_ds >=$ds1) return -1;
			if($ds2-$this_ds <$ds1){
				if($ds2<=$ds1) 	return $packet;
				if($ds2>$ds1)  	return $ds1-($ds2-$this_ds);
				if($ds1==$ds2) 	return $packet;
			}
		}
	}
	return -1;
}
function getSelesByPath($path){
	$mysql=new CLS_MYSQL;
	$sql="SELECT SUM(packet) as ds FROM tbl_member_packet WHERE path LIKE '$path%'";
	$mysql->Query($sql);
	$r=$mysql->Fetch_Assoc();
	return $r['ds'];
}
public function isChildenPath($path,$childen_user){
	$n=SysCountList('tbl_member_packet'," AND username='$childen_user' AND path LIKE '$path%'")
	if($n==1) return true;
	else	return false;
}
	/*tính hoa hồng nhánh yếu*/
   
    public function getMinDs($code){
        $mysql=new CLS_MYSQL;
        $sql="SELECT * FROM `tbl_accounts` WHERE `rep_user`='".$code."'";
        $mysql->Query($sql);
        $n=$mysql->Num_rows();
        if($n<2)  return -1;
        $r1=$mysql->Fetch_Assoc();
        $r2=$mysql->Fetch_Assoc();
        $ds1=$this->getDS($r1['path']);
        $ds2=$this->getDS($r2['path']);
        if($ds1<=$ds2){
            return $ds1;
        }
        if($ds1>=$ds2){
            return $ds2;
        }
    }
    public function check_childen_code($code,$childen_code){
        $this_acc=$this->getAccountByCode($code);
        $path=$this_acc['path'];
        $sql="SELECT * FROM tbl_accounts WHERE code='$childen_code' AND path LIKE '$path%'";
        $mysql=new CLS_MYSQL;
        $mysql->Query($sql);
        if($mysql->Num_rows()==1)
            return true;
        else
            return false;
    }
?>