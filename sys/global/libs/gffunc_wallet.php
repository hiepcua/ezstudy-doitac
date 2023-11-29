<?php
function getDateReport($type){
	$today=date('d-m-Y 23:59:59');
	if($type==1){
		$first_date=strtotime("-1 days");
		$last_date=strtotime($today);
	}
	elseif($type==2){
		$first_date = strtotime('monday this week');
		$last_date = strtotime('sunday this week');
		
	}else{
		$first_date=date('01-m-Y', strtotime($today));
		$last_date=date('t-m-Y', strtotime($today));
		$first_date=strtotime($first_date);
		$last_date=strtotime($last_date);
	}
	return array('first'=>$first_date,'last'=>$last_date);
}
function updateMoneyWallet($tbl_wallet,$username,$number){
	$sql2="UPDATE $tbl_wallet SET `money`=`money`-$number WHERE username='$username'";
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function updatePushWallet($tbl_wallet,$username,$number){
	$time=time();
	$sql="INSERT INTO $tbl_wallet (username, money, money_total,cdate, mdate, status)
		VALUES ('$username', '$number', '$number','$time','$time','1')
		ON DUPLICATE KEY UPDATE
		mdate     = '$time',
		  money     = money+$number,
		  money_total = money_total+$number";
		//$sql="UPDATE $tbl_wallet SET `money_total`=`money_total`+$number, `money`=`money`+$number WHERE username='$username'";
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function countTotalWallet($tbl_wallet,$username, $type=1, $strwhere=''){//1 là số dư, 2 là tổng nạp vào
    $name_field=$type==1? 'money':'money_total';
	if($username!="") $strwhere.=" AND `username`='$username'";
     $sql="SELECT `".$name_field."` as total FROM $tbl_wallet WHERE 1=1 $strwhere";
    $obj=new CLS_MYSQL;
    $obj->Query($sql);
    $r=$obj->Fetch_Assoc();
    return $r['total']+0;
}
function getCurrentWallet($table, $username, $type=''){ // type= 1 nap, 2 la chuyen,  3 la kichhoat
    $str_where='';
    if($type!=='') $str_where=" AND type='$type'";
    $sql="SELECT SUM(money) AS `sum` FROM $table WHERE `status`=1 AND username='$username' $str_where";
	
    $objdata=new CLS_MYSQL();
    $objdata->Query($sql);
    $row=$objdata->Fetch_Assoc();
    return $row['sum']+0;
}
function getReportWallet($table,$type='',$str_where){
    if($type!=='') $str_where.=" AND type='$type'";
    $sql="SELECT SUM(money) AS `total` FROM $table WHERE `status`=1 $str_where";
	
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function countTotalWalletHistories($tbl_wallet,$strwhere, $type=''){
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE type='$type' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function getListWallet($array, $type=''){
    if($array->Num_rows()>0){
		$total_money_on_page=0;
        ?>
        <table class="table tbl-main">
            <thead><tr>
                <th class="text-center" width="50">STT</th>
                <th>Username</th>
                <?php if($type!='') echo '<th>Nội dung</th>';?>
                <th class="text-right">Số tiền</th>
                <th>Thời gian</th>
                <th width="120">Trạng thái</th>
            </tr></thead>
            <tbody>
            <?php
            $stt=0;
            while($r=$array->Fetch_Assoc()) {
                $stt++;
                $cdate=date('Y-m-d H:i',$r['cdate']);
                $flag=$r['status']==1? 'Hoàn thành':'Đang xử lý';
				$money=$r['money'];
				if($type!='' && $money<0) $money=-1*$r['money'];
				$total_money_on_page+=$money;
                ?>
                <tr>
                    <td class="text-center"><?php echo $stt;?></td>
                    <td><?php echo $r['username'];?></td>
                     <?php if($type!='') echo '<td>'.$r['note'].'</td>';?>
                    <td class="text-right"><?php echo number_format($money);?>đ</td>
                    <td><?php echo $cdate;?></td>
                    <!--<td><?php /*echo $r['note'];*/?></td>-->
                    <td><?php echo $flag;?></td>
                </tr>
            <?php }
            ?>
			 <tr class="total_tr">
					<td colspan='3' class="text-right" style="color: #333">Tổng:</td>
					<td class="td-price text-right"><?php echo number_format($total_money_on_page); ?>đ</td>
					<td></td>
					<td></td>
				</tr>
            </tbody>

        </table>
    <?php
    }
}
function getDS($type_date){
	$tbl_wallet='tbl_wallet_s_histories';
	$arr_date=getDateReport($type_date);
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE 1=1  $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
?>