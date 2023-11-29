<?php
function CancelUpgradePacket(){
	$now=time();
	$obj=SysGetList('tbl_member_packet',array()," AND new_packet>0",false);
	while($r=$obj->Fetch_Assoc()){
		$id=$r['id'];
		if($now-$r['udate']>30*60){
			$arr=array();
			$arr['new_packet']=0;
			SysEdit('tbl_member_packet',$arr," id='$id'");
		}
	}
}
function Bonus_MATCHING($user,$money,$level=0){
	if($user=='root' || $user=='') return;
	if($level>3) return;
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	$thisUser=$obj[0];
	$userPacket=$thisUser['packet'];
	if($level==1){
		$bonus=$money*0.01;
	}
	if($level==2){
		if($userPacket<=500)return;
		$bonus=$money*0.005;
	}
	if($level==3){
		if($userPacket<=2500)return;
		$bonus=$money*0.005;
	}
	$note="Bonus for matching of tier $level agents";
	
	if($bonus<=0) return;
	$arr=array();
	$arr['username']=$user;
	$arr['money']=$bonus;
	$arr['type']=5;
	$arr['cdate']=time();
	$arr['note']=$note;
	SysAdd('tbl_wallet_b',$arr);
	//-------------------------------------
	$level++;
	$par_user=$thisUser['par_user'];
	Bonus_SALERS($par_user,$money,$level);
}
function Bonus_SALERS($user,$money,$level=0){
	if($user=='root' || $user=='') return;
	if($level>3) return;
	global $_PACKET;
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	$thisUser=$obj[0];
	$userPacket=$thisUser['packet'];
	if($level==0){
		$bonus=isset($_PACKET["P".$userPacket])?$money*$_PACKET["P".$userPacket]['commiss']:0;
		$note="Bonus for sales";
	}else{
		if(($userPacket<5000 && $userPacket>500) && $level>2)return;
		if($userPacket<=500 && $level>1)return;
		$bonus=$money*0.01;
		$note="Bonus for sales of tier $level agents";
	}
	if($bonus<=0) return;
	$arr=array();
	$arr['username']=$user;
	$arr['money']=$bonus;
	$arr['type']=4;
	$arr['cdate']=time();
	$arr['note']=$note;
	SysAdd('tbl_wallet_b',$arr);
	//-------------------------------------
	$level++;
	$par_user=$thisUser['par_user'];
	Bonus_SALERS($par_user,$money,$level);
}
function Bonus_Daily(){
	global $_PACKET;
	$obj=SysGetList('tbl_member_packet',array()," AND isactive=1",false);
	while($r=$obj->Fetch_Assoc()){
		$user=$r['username'];
		$userPacket=$r['packet'];
		if(!isset($_PACKET["P".$userPacket])) continue;
		$day=$r['day']+1;
		$loop=$r['loop'];
		$money=$_PACKET["P".$userPacket]['day_rate'];
		$max_day=$_PACKET["P".$userPacket]['max_day'];
		if($money==0) continue;
		$arr=array();
		$arr['username']=$user;
		$arr['money']=$money;
		$arr['type']=1;
		$arr['cdate']=time();
		$arr['note']="Reward daily of day $day (loop $loop)";
		SysAdd('tbl_wallet_s',$arr);
		//	update day
		$arr=array();
		$arr['day']=$day;
		SysEdit('tbl_member_packet',$arr," username='$user'");
		//	check max day
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
	$money=isset($_PACKET["P".$userPacket])?$_PACKET["P".$userPacket]['commiss']*$packet:0;  // người được nhận ở mức nào thì nhận mức đấy
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
	$par_user=$thisUser['par_user'];
	//Bonus_MATCHING($par_user,$money,1);
}
function hh_cancap($user,$by_user,$packet){ // sales là doanh số
	if($user=='root' || $user=='') return;
	$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
	$this_user=$obj[0];
	$userPacket=$this_user['packet'];
	$sales=getSales($user,$by_user,$packet);
	if($sales>0 && $userPacket>0){
		global $_PACKET;
		$money=isset($_PACKET["P".$userPacket])?$_PACKET["P".$userPacket]['sales']*$sales:0;  // người được nhận ở mức nào thì nhận mức đấy
		$arr=array();
		$arr['username']=$user;
		$arr['money']=$money;
		$arr['type']=4;
		$arr['cdate']=time();
		$arr['note']='Rewards for member growth';
		if($money>0){
			SysAdd('tbl_wallet_b',$arr);
		}
	}
	$par_user=$this_user['par_user'];
	//Bonus_MATCHING($par_user,$money,1);
	$rep_user=$this_user['rep_user'];
	hh_cancap($rep_user,$by_user,$packet);
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
		if(isChildenPath($r1['path'],$new_user)){// thêm vào nhánh 1
			if($ds1-$packet >=$ds2)	return -1;					// thêm vào nhánh mạnh
			if($ds1-$packet < $ds2){ 							 
				if($ds1<=$ds2) 	return $packet; 				// "thêm vào nhánh yếu vẫn yếu";
				if($ds1>$ds2)	return $ds2-($ds1-$packet); 	// "thêm vào nhánh yếu thành mạnh";
				if($ds1==$ds2) 	return $packet;					// "thêm vào nhánh yếu = nhánh mạnh";
			}
		}
		if(isChildenPath($r2['path'],$new_user)){		// 'thêm vào nhánh 2';
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
function isChildenPath($path,$childen_user){
	$n=SysCount('tbl_member_packet'," AND username='$childen_user' AND path LIKE '$path%'");
	if($n==1) return true;
	else	return false;
}
/*tính hoa hồng nhánh yếu*/
function getMinDs($code){
	$mysql=new CLS_MYSQL;
	$sql="SELECT * FROM `tbl_accounts` WHERE `rep_user`='".$code."'";
	$mysql->Query($sql);
	$n=$mysql->Num_rows();
	if($n<2)  return -1;
	$r1=$mysql->Fetch_Assoc();
	$r2=$mysql->Fetch_Assoc();
	$ds1=getDS($r1['path']);
	$ds2=getDS($r2['path']);
	if($ds1<=$ds2){
		return $ds1;
	}
	if($ds1>=$ds2){
		return $ds2;
	}
}
function check_childen_code($code,$childen_code){
	$this_acc=getAccountByCode($code);
	$path=$this_acc['path'];
	$sql="SELECT * FROM tbl_accounts WHERE code='$childen_code' AND path LIKE '$path%'";
	$mysql=new CLS_MYSQL;
	$mysql->Query($sql);
	if($mysql->Num_rows()==1)
		return true;
	else
		return false;
}
function getDSNhom($user,$side){
	$ds=0;
	$obj=new CLS_MYSQL;
	$sql="SELECT `path` FROM `tbl_member_packet` WHERE `rep_user`='".$user."' AND side='$side'";
	$obj->Query($sql);
	if($obj->Num_rows()>0){
		$r=$obj->Fetch_Assoc();
		$path=$r['path'];
		$sql="SELECT SUM(`packet`) as ds FROM tbl_member_packet WHERE path LIKE '$path%'";
		$obj->Query($sql);
		$r=$obj->Fetch_Assoc();
		$ds=$r['ds'];
	}
	return $ds;
}
?>