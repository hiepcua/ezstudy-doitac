<?php
global $_UNIT;
$page_name='REPORT';
if(isLogin()){
	$par_user=$this_user=getInfo('username');
	$strwhere='';
	$get_user=isset($_GET['id'])? addslashes($_GET['id']):'';
	$filter_username=isset($_POST['sl_username']) ? addslashes($_POST['sl_username']):'';
	if($filter_username=='' && $get_user!='') $filter_username=$get_user;
	//echo $filter_username;
	if($filter_username!="" && $filter_username!=$this_user){
        $count=SysCount('tbl_member'," AND username='$filter_username' AND par_user='$par_user'");
		if($count<1) die('Không tìm thấy User này trong danh sách Sale của bạn');
		$this_user=$filter_username;
		 $strwhere.=" AND username='$filter_username'";
		 $label_title="Doanh số > ".$filter_username;
    }
	else $label_title="Doanh số của bạn";
    $obj=SysGetList('tbl_member_wallet',array()," AND username='$this_user'");
    $wallet=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
    $cur_page=isset($_POST['txtCurnpage'])? (int)$_POST['txtCurnpage']:1;
	$table_his='tbl_wallet_b_histories';
	$total_active=countTotalWalletHistories($table_his,$this_user,3);
	
	$type_report=isset($_POST['txt_typedate'])? (int)$_POST['txt_typedate']:'0';
    if($type_report!='0'){
		$arr_date=getDateReport($type_report);
		$first_date=isset($arr_date['first'])? $arr_date['first']:'';
		$last_date=isset($arr_date['last'])? $arr_date['last']:'';
		 if($type_report==1){
			 $today=time();
			 $label_title='Doanh số Hôm nay';
		 }
		elseif($type_report==2){
			$label_title='Doanh số Tuần này';
		}
		else{
			$label_title='Doanh số Tháng này';
		}
		$strwhere.=" AND cdate > $first_date AND cdate<=$last_date";
	}
	 $fromdate=isset($_POST['txt_fromdate']) ? addslashes($_POST['txt_fromdate']):'';
	 $todate=isset($_POST['txt_todate']) ? addslashes($_POST['txt_todate']):'';
    if($fromdate!=''){
        $fromdate_fomat=strtotime($fromdate);
        $strwhere.=" AND `cdate` >= '$fromdate_fomat'";
    }
    if($todate!=''){
        $todate_fomat=strtotime($todate);
        $strwhere.=" AND `cdate` <= '$todate_fomat'";
    }
?>
<div class="card box-report">
	<div class="card-body">
		<form id="frm_list" name="frm_list" method="post" action="">
		<div class="media">
			<h3 class="title title-main pull-left"><?php echo $label_title;?></h3>
			<div class="input-report pull-right">
			<select name="txt_typedate" class="form-control form-control2 txt_typedate" id="txt_typedate">
				<option value="0">Chọn báo cáo</option>
				<option value="1">Ngày hôm nay</option>
				<option value="2">Tuần này</option>
				<option value="3">Tháng này</option>
			</select>
			 <script language="javascript">
				cbo_Selected('txt_typedate',<?php echo $type_report;?>);
			</script>
			
		</div>
		</div>
		<div class="frm-search-box form-inline">
		<div class=" input-group input-time">
				<label class="mr-sm-2" for="fromdate">Tài khoản</label>
				<select name="sl_username" id="sl_username" class="form-control select sl_username"  style="width: 100%">
					<option value="<?php echo $this_user;?>"><?php echo $this_user;?></option>
					<?php
					$arr=SysGetList('tbl_member', array('fullname','username','email'), "AND par_user='$par_user' ORDER BY cdate DESC");
					foreach($arr as $row){
						$fullname=$row['fullname'];
						$user=$row['username'];
						$select='';
						if($filter_username==$user) $select='selected';
						?>
						<option value="<?php echo $user;?>" <?php echo $select;?> data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $user;?></option>
					<?php
					}
					?>
				</select>
				<script type="text/javascript">
				$(document).ready(function(){
					$("#sl_username").select2();
					cbo_Selected("sl_username","<?php echo $filter_username; ?>");

				});
				</script>
		</div>
		
		<div class="input-group input-time">
			<label class="mr-sm-2" for="fromdate">Từ</label>
			<input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="fromdate" name="txt_fromdate" placeholder="Từ ngày" value="<?php echo $fromdate;?>"/>
		</div>

		<div class="input-group input-time">
			<label class="mr-sm-2" for="todate">Đến</label>
			<input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="todate" name="txt_todate" placeholder="Đến ngày" value="<?php echo $todate;?>"/>
		</div>
	
		<button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i> <span class="txt-search"> Tìm kiếm</span></button>
		<span type="button" class="btn btn-default btn-search reset_form"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</span>

		<div class="clear-m"></div>
		</div>
		<?php if($strwhere==''){?>
		<div class="number-report">
			<div class="bg-info" style="padding: 5px 15px; margin-bottom: 15px">
				<h4 class="name"> Tổng Doanh số: <b style="color: red"><?php echo number_format(-1*$total_active);?>đ</b></h4>
				<div class="clearfix"></div>
			</div>
	</div>
   <?php
		}
	$strwhere.=" AND username ='$this_user' AND type=3";
	$total_items=SysCount($table_his,$strwhere);
	$start=($cur_page-1)*MAX_ROWS;
	$strwhere.=" ORDER BY cdate DESC LIMIT ".$start.",".MAX_ROWS;
	$array=SysGetList($table_his, array(),$strwhere, false);
	getListWallet($array,'b',1);
	?>
	</div>
	<div class="box-pagination">
		<?php echo paging($total_items,MAX_ROWS,$cur_page); ?>
	</div>
</div>
<?php }else{
	header('location:'.ROOTHOST.'login');
}?>
<script>
$('#txt_typedate').change(function(){
	$('#frm_list').submit();
})
$('.reset_form').click(function(){
	$('#fromdate').val('');
	$('#todate').val('');
})
</script>
