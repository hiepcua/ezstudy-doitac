<?php
global $_UNIT;
$page_name='REPORT';
if(isLogin()){
    $this_user=getInfo('username');
    $obj=SysGetList('tbl_member_wallet',array()," AND username='$this_user'");
    $wallet=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
    $cur_page=isset($_POST['txtCurnpage'])? (int)$_POST['txtCurnpage']:1;
	$strwhere='';
    if(isset($_POST['txt_fromdate'])) $_SESSION['FROMDATE'.$page_name]=addslashes($_POST['txt_fromdate']);
    if(isset($_POST['txt_todate'])) $_SESSION['TODATE'.$page_name]=addslashes($_POST['txt_todate']);
	if(isset($_POST['txtkeyword'])) $_SESSION['KEYWORD'.$page_name]=addslashes($_POST['txtkeyword']);
    $keyword=isset($_SESSION['KEYWORD'.$page_name]) ? $_SESSION['KEYWORD'.$page_name]:'';
    if($keyword!='') $strwhere.=" AND (`name` like '%$keyword%' OR `cuser` like '%$keyword%')";
    $fromdate=isset($_SESSION['FROMDATE'.$page_name]) ? $_SESSION['FROMDATE'.$page_name]:'';
    $todate=isset($_SESSION['TODATE'.$page_name]) ? $_SESSION['TODATE'.$page_name]:'';
    if($fromdate!=''){
        $fromdate_fomat=strtotime($fromdate);
        $strwhere.=" AND `cdate` >= '$fromdate_fomat'";
    }
    if($todate!=''){
        $todate_fomat=strtotime($todate);
        $strwhere.=" AND `cdate` <= '$todate_fomat'";
    }
    $fromuser=isset($_SESSION['USER'.$page_name]) ? $_SESSION['USER'.$page_name]:0;
    if($fromuser!='0') $strwhere.=" AND `cuser` = '$fromuser'";
?>
<div class="card">
	<div class="card-body">

		<div class="media">
			<h3 class="title title-main">Doanh Số</h3>
		</div>

	<div class="toolbar">
			<div class="frm-search-box <?php  if($is_admin==true) echo 'style1';?> form-inline">
				<div class="input-group input-user">
					<label class="mr-sm-2" for="fromdate">Loại báo cáo</label>
					<select name="txt_typedate" class="form-control txt_typedate" data-placeholder="">
						<option value="0">Ngày hôm nay</option>
						<option value="1">Tuần này</option>
						<option value="2">Tháng này</option>
					</select>
				</div>
				<div class="input-group input-user">
					<label class="mr-sm-2" for="fromdate">Loại giao dịch</label>
					<select name="txt_type" class="form-control txt_type" data-placeholder="">
						<option value="0">Tất cả</option>
						<option value="1">Kích hoạt</option>
						<option value="2">Chuyển khoản</option>
						<option value="3">Liên kết</option>
					</select>
				</div>
				<div class="input-group input-time">
					<label class="mr-sm-2" for="fromdate">Từ ngày</label>
					<input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="fromdate" name="txt_fromdate" placeholder="Từ ngày" value="<?php echo $fromdate;?>"/>
				</div>
				<div class="input-group input-time">
					<label class="mr-sm-2" for="todate">Đến ngày</label>
					<input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="todate" name="txt_todate" placeholder="Đến ngày" value="<?php echo $todate;?>"/>
				</div>
				<button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i> <span class="txt-search"> Tìm kiếm</span></button>
				<div class="clear-m"></div>
			</div>
		</div>

	<?php 
	$table_his='tbl_wallet_b_histories';
	$total_active=countTotalWalletHistories($table_his,$this_user,3);
	?>
	<div class="number-report">
	<div class="bg-info" style="padding: 5px 15px; margin-bottom: 15px"><h4 class="name"> Tổng Doanh số: <b style="color: red"><?php echo number_format(-1*$total_active);?>đ</b></h4></div>
		
	</div>
   <?php
	$strwhere.=" AND username ='$this_user' AND type=3";
	$total_items=SysCount($table_his,$strwhere);
	$start=($cur_page-1)*MAX_ROWS;
	$strwhere.=" ORDER BY cdate DESC LIMIT ".$start.",".MAX_ROWS;
	$array=SysGetList($table_his, array(),$strwhere, false);
	getListWallet($array);
	?>
	<div class="box-pagination">
		<?php echo paging($total_items,MAX_ROWS,$cur_page); ?>
	</div>
</div>
<?php }else{
	header('location:'.ROOTHOST.'login');
}?>