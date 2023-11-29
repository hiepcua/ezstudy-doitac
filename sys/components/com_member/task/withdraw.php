<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$strWhere='';
$ispay=isset($_GET['ispay'])?$_GET['ispay']:'';
if($ispay!=''){
	$ispay=(int)$ispay;
	$strWhere.=" AND ispay='$ispay'";
}
?>
 <div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Sell pit histories
    </div>
</div><br>
<div class='col-sm-12' id="list">
    <form method='get' action='' name='frm_list'>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="Header_list">
	  <tr>
		<td><div class='row'><div class='col-sm-4'><div class='input-group'>
			<input type="hidden" name="task" value='withdraw'/>
			<select class='form-control' name='ispay' id='cbo_ispay'>
				<option value=''>Tất cả</option>
				<option value='0' <?php echo $ispay==0?'selected=true':'';?> >Chưa Thanh toán</option>
				<option value='1' <?php echo $ispay==1?'selected=true':'';?>>Đã thanh toán</option>
			</select>
			<span class='input-group-addon' onclick="document.frm_list.submit();">Tìm kiếm</span>
		</div></div></div>
		</td>
        <td align="right">
        </td>
      </tr>
    </table>
	</form>
	<br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="table table-bordered  list">
		<tr class="header">
			<th>Create Date</th>
			<th>Username</th>
			<th class="text-center">Amount (PIT)</th>
			<th class="text-center">Amount (USDT)</th>
			<th class="text-center">USDT ADDRESS</th>
			<th class="text-center">isPay</th>
			<th class="text-center">Pay Date</th>
			<th class="text-center"></th>
		</tr>
		<?php
		$objData=SysGetList('tbl_withdraw_pit',array()," $strWhere ORDER BY ispay ASC, cdate ASC",false);
		while($item = $objData->Fetch_Assoc()){
			$cdate=date('Y-m-d H:i',$item['cdate']);
			$id=$item['id'];
			$username=$item['username'];
			$wallet=$item['wallet'];
			$amount=number_format($item['amount'],2);
			$usdt=number_format($item['usdt'],2);
			$isPay=$item['ispay']=='1'?"Yes":"";
			$pdate=$isPay=='Yes'?date('Y-m-d H:i',$item['pdate']):'';
			echo "<tr name='trow'>";
			echo "<td>$cdate</td>";
			echo "<td>$username</td>";
			echo "<td align='center'>$amount</td>";
			echo "<td align='center'>$usdt</td>";
			echo "<td align='center'>$wallet</td>";
			echo "<td align='center'>$isPay</td>";
			echo "<td align='center'>$pdate</td>";
			echo "<td align='center'>";
			if($isPay!="Yes"){
			echo "<a href='javascript:void(0);' class='btn btn-warning cmd_confirm_sell' dataid='$id'>Xác nhận</a>
				<a href='javascript:void(0);' class='btn btn-danger cmd_cancel_sell' dataid='$id'>Hủy</a>";
			}
			echo "</td>";
			echo "</tr>";
		}	?>
    </table>
	<script>
	$('.cmd_confirm_sell').click(function(){
		if(confirm("Bạn chắc chắn xác nhận giao dịch?")){
			var _url="<?php echo ROOTHOST_ADMIN;?>ajaxs/member/confirm_withdraw.php";
			var _data={
				'id':$(this).attr('dataid')
			}
			$.post(_url,_data,function(req){
				if(req=='success'){ alert('Confirm success!'); window.location.reload();}
				else alert(req);
			})
		}
	})
	$('.cmd_cancel_sell').click(function(){
		if(confirm("Bạn chắc chắn hủy giao dịch này!")){
			var _url="<?php echo ROOTHOST_ADMIN;?>ajaxs/member/cancel_withdraw.php";
			var _data={
				'id':$(this).attr('dataid')
			}
			$.post(_url,_data,function(req){
				if(req=='success'){ alert('Hủy thành công!'); window.location.reload();}
				else alert(req);
			})
		}
	})
	</script>
</div>
<?php //----------------------------------------------?>