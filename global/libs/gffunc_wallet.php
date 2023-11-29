<?php
function updatePayWallet($tbl_wallet,$username,$number){
	$sql="UPDATE $tbl_wallet SET `money`=`money`-$number WHERE username='$username'";
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function updatePushWallet($tbl_wallet,$username,$number){
	$time=time();
	$sql="INSERT INTO $tbl_wallet (username, money, money_total,cdate, mdate, status)
		VALUES ('$username', '$number', '$number','$time','$time','1') ON DUPLICATE KEY UPDATE mdate = '$time',money = money+$number,money_total = money_total+$number";
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function countTotalWallet($tbl_wallet,$username, $type=1, $strwhere=''){//1 là số dư, 2 là tổng nạp vào
    $name_field=$type==1 ? 'money':'money_total';
    $sql="SELECT `".$name_field."` as total FROM $tbl_wallet WHERE `username`='$username' $strwhere";
    $obj=new CLS_MYSQL;
    $obj->Query($sql);
    $r=$obj->Fetch_Assoc();
    return $r['total']+0;
}

function countTotalWalletHistories($tbl_wallet,$username, $type=''){
    $strwhere='';
    if($type!='') $strwhere=" AND type='$type'";
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE `username`='$username' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function getDS($username,$type_date){
	$tbl_wallet='tbl_wallet_s_histories';
	$arr_date=getDateReport($type_date);
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE `username`='$username' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function getListWallet($array,$type='b', $flag_money=''){ //$flag_money=1 chuyển đổi money thành dương
    if($array->Num_rows()>0){
    ?>
    <table class="table tbl-main">
        <thead><tr>
            <th class="text-left mhide">Ngày</th>
			<th class="text-right">Số điểm</th>
            <th>Nội dung</th>
            <th class="text-center mhide">Loại giao dịch</th>
            <th width="90">Trạng thái</th>
        </tr></thead>
        <tbody>
        <?php
        $stt=0;
		$total_money_on_page=0;
            while($r=$array->Fetch_Assoc()) {
                $stt++;
                $cdate=date('Y-m-d H:i',$r['cdate']);
                $flag=$r['status']==1? 'fa-check-circle done':'fa-spinner';
				
				if($r['type']==1) $label=$type=='b'? 'Nạp điểm':'Kích hoạt';
				elseif($r['type']==2) $label=$type=='b'? 'Chuyển điểm':'Gia hạn';
				else  $label=$type=='b'?'Gia hạn':'Liên kết';
				
				$money=$r['money'];
				if($flag_money==1 && $money<0) $money=-1*$r['money'];
				$total_money_on_page+=$money;
                ?>
                <tr>
                    <td class="text-left mhide"><?php echo $cdate;?></td>
					<td class="text-right"><strong><?php echo number_format($money);?></strong> đ</td>
                    <td><?php echo $r['note'];?></td>
                    <td class="text-center mhide"><?php echo $label;?></td>
                    <td class="text-center"><i class="fa <?php echo $flag;?>"></i></td>
                </tr>
            <?php }
			if($flag_money==1){
        ?>
		 <tr class="total_tr">
			<td class="text-left" style="color: #333">Tổng:</td>
			<td class="td-price text-right"><?php echo number_format($total_money_on_page); ?> đ</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
			   <?php }
        ?>
        </tbody>
    </table>
    <?php
    }
}
function getTiLeThuong($username,$rates, $action){//1 là kích hoạt, 2 là gia hạn, 3 là liên kết
	$my_rates=json_decode($rates, true);
	$rs=$rs2='';
	if($my_rates=='') return '';
	if(count($my_rates)>0){
		$doanhso=countTotalWallet('tbl_wallet_b_histories', $username, 1);// doanh số nạp điểm
		foreach($my_rates as $key=> $vl){
			if($key=='0,0'){
				if($action==1) $rs=$my_rates['0,0']['kichhoat'];
				elseif($action==2) $rs=$my_rates['0,0']['giahan'];
				else $rs=$my_rates['0,0']['lienket'];
				if($key=='0,0' && count($my_rates)==1) return $rs;
			}
			else{
				$item=explode(',',$key);
				$rank1=$item[0];
				$rank2=$item[1];
				if($doanhso >= $rank1 && $doanhso <= $rank2){
					if($action==1) $rs2=$vl['kichhoat'];
					elseif($action==2) $rs2=$vl['giahan'];
					else $rs2=$vl['lienket'];	
				}
			}
			
		}
	}
	return $rs2;
}				
?>