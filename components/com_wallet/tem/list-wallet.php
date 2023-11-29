<?php
global $_UNIT;
if(isLogin()){
	$get_wallet=isset($_GET['wallet'])? addslashes($_GET['wallet']):'b';
    $this_user=getInfo('username');
    $obj=SysGetList('tbl_member_wallet',array()," AND username='$this_user'");
    $wallet=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
    $cur_page=isset($_POST['txtCurnpage'])? (int)$_POST['txtCurnpage']:1;
	if($get_wallet=='b'){
		$title='Ví B';
		$table='tbl_wallet_b';
		$table_his='tbl_wallet_b_histories';

	}else{
		$title='Ví S';
		$table='tbl_wallet_s';
		$table_his='tbl_wallet_s_histories';

	}
	$total_current=countTotalWallet($table,$this_user,1);
	$total_push=countTotalWallet($table,$this_user,2);

?>
    <div class="card">
        <div class="card-body card-box">
            <div class="wallet-title">
				<div class="media">
					<ul class="list-inline">
					<li><h3 class="title"><?php echo $title;?></h3></li>
					<li><p><span class="">Số dư: <span class="number"><?php echo number_format($total_current);?>đ </span>/ Tổng nạp: <span class="number"><?php echo number_format($total_push);?>đ</span></span></p></li>
					</ul>
				</div>	
			<?php if($get_wallet=='b' && getInfo('isroot')==1) echo '<a class="btn btn-success" href="'.ROOTHOST.'send"><i class="fa fa-retweet" aria-hidden="true"></i> Chuyển điểm</a>'; ?>
        </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class='title'>Lịch sử giao dịch</h3>
            <?php
            $strwhere=" AND username ='$this_user'";
            $strwhere.=" ORDER BY cdate DESC LIMIT 100";
            $array=SysGetList($table_his, array(),$strwhere, false);
            getListWallet($array,$get_wallet);
            ?>
           
        </div>
	</div>
<?php }else{
	header('location:'.ROOTHOST.'login');
}?>