<div class="dabroash" style="padding-top: 15px;">
<div class="row row-item box-db2">
    <div class="col-md-6 col-item">
        <div class="card">
            <div class="card-body">
			 <div class="card-header">
					<h3 class="card-title">Doanh số</h3>
				</div>
			<?php 
			$money_today=getDS(1);
			$money_week=getDS(2);
			$money_month=getDS(3);
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
			
	</div>
        </div>
    </div>
    <div class="col-md-6 col-item">
        <div class="card">
            <div class="card-body">
               
                <div class="card-header">
                    <div class=" pull-left" >
                        <h3 class="card-title">Thông tin ví</h3>
                    </div>
                   
                    <div class="clearfix"></div>
                </div>
                 <?php
				$table='tbl_wallet_b';
                $total_current=countTotalWallet($table,'',1);
				$total_push=countTotalWallet($table,'',2);
                ?>
                <div class="card-header">
					<h3 class="card-title">Ví B</h3>
                </div>
               <div class="media-wallet" style="border-bottom: 1px solid #f1f1f1; margin-bottom: 6px; margin-top: 6px">
					<p><span class=""><span class="number"><?php echo number_format($total_current);?>đ </span><span class="mhide">/</span><span class="clear-m"><span class="number"><?php echo number_format($total_push);?>đ</span></span></span></p>	
				</div>	
				  <?php
				$table='tbl_wallet_s';
               $total_current=countTotalWallet($table,'',1);
				$total_push=countTotalWallet($table,'',2);
                ?>
               <div class="card-header">
					<h3 class="card-title">Ví S</h3>
                </div>
               <div class="media-wallet">
					<p><span class=""><span class="number"><?php echo number_format($total_current);?> đ </span><span class="mhide">/</span><span class="clear-m"><span class="number"><?php echo number_format($total_push);?>đ</span></span></span></p>	
				</div>	
                
            </div>
        </div>
    </div>
</div>
<div class="row  row-item">
    <div class="col-md-4 col-item">
        <div class="card">
            <div class="card-body">
               
                <h4 class="card-title mb-4">Giao dịch gần nhất</h4>
                <div data-simplebar="init">
                    <div class="simplebar-wrapper">
                        <table class="table table-borderless table-centered table-nowrap">
                            <tbody>
                            <?php
                            $obj_user_wallet=SysGetList('tbl_wallet_s_histories',array()," ORDER BY id DESC LIMIT 6", false);
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
                                        <h6 class="font-size-15 mb-1 fw-normal"><?php echo $username;?></h6>
                                    </td>
                                    <td><span class="badge bg-4 font-size-12"><?php echo $label_type;?></span></td>
                                    <td class="text-muted fw-semibold text-end"><?php echo $label.number_format($money); ?>đ</td>
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
    <div class="col-md-4 col-item">
        <div class="card">
            <div class="card-body">
               
                <h4 class="card-title mb-4">Top Đại lý</h4>

                <div data-simplebar="init">
                    <div class="simplebar-wrapper">
                        <table class="table table-borderless table-centered table-nowrap">
                            <tbody>
                            <?php
							$trwhere=" INNER JOIN tbl_wallet_b ON tbl_member.username=tbl_wallet_b.username WHERE isroot='1' ORDER BY money_total DESC LIMIT 6";
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
                                <td><span class="badge bg-4 font-size-12"><?php echo number_format($cur_wallet);?>đ</span></td>
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
    <div class="col-md-4 col-item">
        <div class="card">
            <div class="card-body">
               
                <h4 class="card-title mb-4">Top Sales</h4>

                <div data-simplebar="init">
                    <div class="simplebar-wrapper">
                        <table class="table table-borderless table-centered table-nowrap">
                            <tbody>
                            <?php
                           $trwhere=" INNER JOIN tbl_wallet_b ON tbl_member.username=tbl_wallet_b.username WHERE isroot='' ORDER BY money_total DESC LIMIT 6";
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
                                <td><span class="badge bg-4 font-size-12"><?php echo number_format($cur_wallet);?>đ</span></td>
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
    <div class="clearfix"></div>
</div>
</div>