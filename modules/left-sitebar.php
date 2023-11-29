<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
if(isLogin()){
$this_user=getInfo('username');
$r_mem=SysGetList('tbl_member',array('rates')," AND username='$this_user'");
$kichhoat=$giahan=$lienket='';
if(isset($r_mem[0]['rates'])){
	$rates=json_decode($r_mem[0]['rates'], true);
	$kichhoat=isset($rates['0,0']['kichhoat'])? $rates['0,0']['kichhoat']:'';
	$giahan=isset($rates['0,0']['giahan'])? $rates['0,0']['giahan']:'';
	$lienket=isset($rates['0,0']['lienket'])? $rates['0,0']['lienket']:'';

}
$table='tbl_wallet_b';
$total_b=countTotalWallet($table,$this_user,1);
$table='tbl_wallet_s';
$total_s=countTotalWallet($table,$this_user,2);

?>
<div class="col-md-3">
    <div class="card mod">
        <div class="bg-soft-primary">
            <div class="text-primary txt-helo">
                <h5 class="text-title">Helo: <?php echo $username;?></h5>
            </div>
            <div class="box-avatar">
                <img src="<?php echo ROOTHOST."global/img/avatars/sunny-big.png";?>" alt="" class="img-fluid">
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-4">
                    <h5 class="text-label"><?php echo getInfo('isroot')==1? 'Đại lý':'Saler';?></h5>
                </div>
                <div class="col-sm-8">
                    <ul class="list-inline">
                        <li>Ví B:</li>
                        <li><b><?php echo number_format($total_b);?>đ</b></li>
                    </ul>
                    <ul class="list-inline">
                        <li>Ví S:</li>
                        <li><b><?php echo number_format($total_s);?>đ</b></li>
                    </ul>
					
                </div>
				<div class="clearfix"></div>
				<div class="box-btn text-center" style="margin-top: 10px; ">
					<?php if(getInfo('isroot')==1){?>
					<a href="<?php echo ROOTHOST."send";?>" class="btn btn-success waves-effect waves-light btn-sm">Chuyển điểm <i class="fa fa-arrow-circle-right "></i></a>
					<?php }?>
					<a href="<?php echo ROOTHOST."members/profile";?>" class="btn btn-primary waves-effect waves-light btn-sm">Trang cá nhân <i class="fa fa-arrow-circle-right "></i></a>
				</div>
            </div>
        </div>
    <div class="number-report">
		<table class="table tbl-report">
		<tr>
			<td class="border-left">
				<div class="item">
					<p class="text-muted text-truncate mb-2">Kích hoạt</p>
					<h5 class="mb-0"><?php echo $kichhoat;?>%</h5>
				</div>
			</td>
			<td class="border-left">
				<div class="item">
					<p class="text-muted text-truncate mb-2">Gia hạn</p>
					<h5 class="mb-0"><?php echo $giahan;?>%</h5>
				</div>
			</td>
			<td>
				<div class="item">
					<p class="text-muted text-truncate mb-2">Liên kết</p>
					<h5 class="mb-0"><?php echo $lienket;?>%</h5>
				</div>
			</td>
		</tr>
		</table>
	</div>
	</div>
    <div class="card mod">
    <div class="card-body">
	<?php 
	$money_today=getDS($this_user, 1);
	$money_week=getDS($this_user, 2);
	$money_month=getDS($this_user, 3);
	?>
		<h5 class="text-title">Doanh số</h5>
		<ul class="list-group">
            <li class="list-group-item justify-content-between">
                <label>Doanh số hôm nay</label>
                <span class="badge badge-default badge-pill"><?php echo number_format($money_today);?>đ</span>
            </li>
            <li class="list-group-item justify-content-between">
                 <label>Tuần này</label>
                <span class="badge badge-default badge-pill"><?php echo number_format($money_week);?>đ</span>
            </li>
			<li class="list-group-item justify-content-between">
                 <label>Tháng này</label>
                <span class="badge badge-default badge-pill"><?php echo number_format($money_month);?>đ</span>
            </li>
		</ul>
		  <div class="box-btn text-center" style="margin-bottom: 10px; ">
				
				<a href="<?php echo ROOTHOST."bonus-report";?>" class="btn btn-danger waves-effect waves-light btn-sm">Chi tiết doanh số <i class="fa fa-arrow-circle-right "></i></a>
			</div>
	</div>
	</div>
   
</div>
<?php }?>