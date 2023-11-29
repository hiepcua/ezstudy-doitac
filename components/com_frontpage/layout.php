<?php 
$isroot=getInfo('isroot');
$daily=SysCount('tbl_member', " AND isroot=1");
$saler=SysCount('tbl_member', " AND isroot=''");
?>
<div class="row row-item box-db2">
	<div class="<?php echo $isroot==1? 'col-md-9':'';?> col-item">
		<div class="row row-item">
			 <div class="col-xs-6 col-item">
        <div class="card">
            <div class="card-body">
                <?php
				$table='tbl_wallet_b';
               $total_current=countTotalWallet($table,$this_user,1);
				$total_push=countTotalWallet($table,$this_user,2);
                ?>
                <div class="card-header">
					<h3 class="card-title">Điểm kích hoạt (Ví B)</h3>
					<div class="avatar-xs bg-1">
						<span class="avatar-title">
							<i class="fa fa-line-chart" aria-hidden="true"></i>
						</span>
					</div>
                </div>
               <div class="media-wallet">
					<p><span class=""><span class="number"><?php echo number_format($total_current);?></span><span class="mhide"> / </span><span class="clear-m"><span class="number"><?php echo number_format($total_push);?></span></span></span></p>	
				</div>	
               
                <div class="mt-3 text-center box-btn">
					<a href="<?php echo ROOTHOST;?>bonus-report" class="mhide btn btn-primary waves-effect waves-light btn-sm">Doanh số <i class="fa fa-arrow-circle-right "></i></a>
                    <a href="<?php echo ROOTHOST;?>b-wallet" class="waves-effect btn bg-3 btn-readmore">Xem chi tiết <i class="fa fa-arrow-circle-right "></i></a>
                </div>
            </div>
        </div>
    </div>
      <div class="col-xs-6 col-item">
        <div class="card">
            <div class="card-body">
                <?php
				$table='tbl_wallet_s';
               $total_current=countTotalWallet($table,$this_user,1);
				$total_push=countTotalWallet($table,$this_user,2);
                ?>
                <div class="card-header">
					<h3 class="card-title">Điểm tích lũy (Ví S)</h3>
					<div class="avatar-xs bg-4">
						<span class="avatar-title">
							 <i class="fa fa-suitcase icon-db" aria-hidden="true"></i>
						</span>
					</div>
                </div>
               <div class="media-wallet">
					<p><span class=""><span class="number"><?php echo number_format($total_current);?></span><span class="mhide"> / </span><span class="clear-m"><span class="number"><?php echo number_format($total_push);?></span></span></span></p>	
				</div>	
                <div class="mt-3 text-center box-btn">
                    <a href="<?php echo ROOTHOST;?>s-wallet" class="waves-effect btn bg-3 btn-readmore">Xem chi tiết <i class="fa fa-arrow-circle-right "></i></i></a>
                </div>
            </div>
        </div>
    
		</div>
	</div>
	</div>
	
   <div class="<?php echo $isroot==1? 'col-md-3':'';?> col-item">
    <div class="card card-report dhide">
    <div class="card-body">
	<?php 
	$money_today=getDS($this_user, 1);
	$money_week=getDS($this_user, 2);
	$money_month=getDS($this_user, 3);
	?>
		
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
	<?php if($isroot==1){?>
   <div class="row-m row-item">
		<div class="col-xs-6 col-item">
		<div class="card box-child  card-green ">
            <div class="card-body">
                <div class="card-header">
					<h3 class="card-title">Đại lý</h3>
					<h3 class="card-number"><?php echo $daily;?></h3>
						<span class="avatar-title">
							<i class="fa fa-diamond" aria-hidden="true"></i>
						</span>
		
                </div>
              
            </div>
        </div>
        </div>
		 <div class="col-xs-6 col-item">
		<div class="card box-child card-blue">
            <div class="card-body">
                <div class="card-header">
					<h3 class="card-title">Saler</h3>
					<h3 class="card-number"><?php echo $saler;?></h3>
						<span class="avatar-title">
							<i class="fa fa-heart-o" aria-hidden="true"></i>
						</span>
		
                </div>
              
            </div>
            </div>
        </div>
		

	</div>
	<?php } ?>
	</div>
</div>
<div class="row  row-item box-db3">
    <div class="<?php echo $isroot==1? 'col-md-6':'';?>  col-item">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-4">Giao dịch gần nhất</h4>
                <div data-simplebar="init">
                    <div class="simplebar-wrapper">
                        <table class="table table-borderless table-centered table-nowrap">
                            <tbody>
                            <?php
                            $obj_user_wallet=SysGetList('tbl_wallet_s_histories',array()," AND username='$this_user' ORDER BY id DESC LIMIT 6", false);
                            while ($row=$obj_user_wallet->Fetch_Assoc()) {
                            $id=$row['id'];
                            $username=$row['username'];
                            $money=$row['money'];
                            $label=$money>0? "+":"";
                            $status=$row['note'];
                            $type=$row['type'];
                            if($type==1) $label_type='Kích hoạt';
                            elseif($type==2) $label_type='Gia hạn';
                            else $label_type='Liên kết';
                            ?>
                                <tr>
                                    <td style="width: 20px;"><i class="fa fa-usd" aria-hidden="true"></i></td>
                                  <td>
                                    <h6 class="font-size-15 mb-1 fw-normal"><?php echo $status;?></h6>
                                </td>
                                    
                                    <td class="text-muted fw-semibold text-end"><b><?php echo $label.number_format($money); ?>đ</b></td>
                                </tr>
                            <tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card-body-->
        </div>
    </div>

<?php if($isroot==1){?>
	<div class="col-md-6 col-item">
        <div class="card">
            <div class="card-body">
               
                <h4 class="card-title mb-4">Top Đại lý</h4>

                <div data-simplebar="init">
                    <div class="simplebar-wrapper">
                        <table class="table table-borderless table-centered table-nowrap">
                            <tbody>
                            <?php
							$trwhere=" INNER JOIN tbl_wallet_b ON tbl_member.username=tbl_wallet_b.username WHERE par_user='$this_user' ORDER BY money_total DESC LIMIT 6";
                            $obj_user_wallet=SysGetList('tbl_member',array(),$trwhere, false, false);
                            $stt=0;
                            while ($row=$obj_user_wallet->Fetch_Assoc()) {
                            $stt++;
                            $username=$row['username'];
                            $cur_wallet=$row['money_total'];
                            ?>
                            <tr>
                                <td style="width: 20px;"><i class="fa icon-user fa-user bg-<?php echo $stt ?>" aria-hidden="true"></i></td>
                                <td>
                                    <h6 class="font-size-15 mb-1 fw-normal"><?php echo $username;?></h6>
                                </td>
                                <td><span class="badge bg-5 font-size-12"><?php echo number_format($cur_wallet);?>đ</span></td>
                                <td class="text-muted fw-semibold text-end"><?php echo $stt ?></td>
                            </tr>
                            <tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
        </div>
	</div>
<?php }?>
   
    <div class="clearfix"></div>
</div>
</div>