<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
if(isLogin()){
	$this_user=getInfo('username');
	$item_obj=SysGetList('tbl_member', array('rates'), " AND username='$this_user'");
	$rates=isset($item_obj[0]['rates'])? $item_obj[0]['rates']:'';
	$cur_page=isset($_POST['txtCurnpage'])? $_POST['txtCurnpage']:1;

	?>
	<div class="card box-card">
		<div class="card-body">
			<div class="action-tool">
				<h2 class="label-title">
					Danh sách Khách hàng
				</h2>
				<?php require_once('modules/toolbar.php');?>
			</div>

			<?php 
			$url = API_DC.'list-packet';
			$arr = array();
			$arr['key'] = PIT_API_KEY;
			$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$_PACKET_LIST = Curl_Post($url,json_encode($post_data));
			$_PACKET_LIST = $_PACKET_LIST['data'];
			
			$url=API_DC.'list-account';
			$arr = array();
			$max_row=10;
			$arr['key']   = PIT_API_KEY;
			$arr['saler'] = $this_user;
			$arr['strwhere'] = '';
			$arr['page'] = $cur_page;
			$arr['max_row'] = $max_row;
			$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$rep = Curl_Post($url,json_encode($post_data));
			if(is_array($rep['data']) && count($rep['data']) > 0) {
				$total_items=$rep['total_rows'];
				?>
				<table class="table table-main">
					<thead>
						<tr>
							<th class="stt text-center mhide"><strong>STT</strong></th>
							<th><strong>Họ và tên</strong></th>
							<th class="text-center"><strong>Khối lớp</strong></th>
							<th class="text-center"><strong>Gói dịch vụ</strong></th>
							<th class="text-center"><strong>Trạng thái</strong></th>
							<th class="text-center mhide"><strong>Ngày đăng ký</strong></th>
							<th class="text-center mhide"><strong>Ngày hết hạn</strong></th>
							<th class="text-center"><strong>Thao tác</strong></th>
						</tr>
					</thead>
					<tbody> 
						<?php 
						$objdata = $rep['data'];
						$stt=0;
						foreach($objdata as $row){
							$item_account=array('username'=>$row['username'],
								'fullname'=>$row['fullname'],
								'cdate'=>$row['cdate'],
								'pdate'=>$row['pdate'],
								'edate'=>$row['edate'],
								'grade'=>$row['grade'],
								'par_user'=>$row['par_user'],
								'ref_user'=>$row['ref_user'],
								'phone'=>$row['phone'],
								'email'=>$row['email'],
								'saler'=>$row['saler'],
								'utype'=>$row['utype'],
								'packet'=>$row['packet'],
							);
							$str_item=json_encode($item_account);
							$grade=$row['grade'];
							$packet=$row['packet'];
							$packet_status=$row['packet_status'];
							$stt++;
							$label=$row['utype']=='chame'? 'Phụ huynh':'Học sinh';
							$class_notic='';

							$activate=(int)$row['activate'];
							$end_time=$row['edate'];
							$id=$row['username'];

							if($end_time=='' || $end_time==NULL){
								$label_end_time='N/A';
								$status='Chưa kích hoạt';
								$class_notic='noticblue';
							}else {
								$label_end_time=date('d-m-Y', $end_time);
								$today=time();
								$notic_endtime=$today+DATE_EXTEND*24*60*60; // ngày dự kiến hết hạn +10
								$class_notic='';
								if($end_time <= $notic_endtime){
									$n=date('d', $notic_endtime-$end_time)+1;
									$status='<p>Sắp hết hạn</p> (Còn '.$n.' ngày)';
									$class_notic='notic';
								}
								elseif($today > $end_time){
									$status='Đã hết hạn';
									$class_notic='notic';
								}
								else $status='Đang sử dụng';
							}

							if($row['utype']=="hocsinh"){
								$status = $status;
								$class_notic = $class_notic;
								$packetLabel=isset($_PACKET_LIST[$row['packet']]['name'])?$_PACKET_LIST[$row['packet']]['name']:"Khóa học miễn phí";
							}else{
								$packetLabel="";
								$status = "Đang hoạt động";
								$class_notic = "";
							}
							?>
							<tr class="<?php echo $class_notic;?>">
								<td class="stt mhide text-center"><?php echo $stt;?></td>
								<td>
									<p class="td-text"><b><?php echo $row['username'];?></b></p>
									<ul class="list-inline">
										<li><?php echo $row['fullname']; ?></li>
										<li><span class="mhide">Loại TK: <?php echo $label;?></span></li>
									</ul>
								</td>
								<td class="text-center status"><?php echo $grade;?></td>
								<td class="text-center status"><?php echo $packetLabel;?></td>
								<td class="text-center status"><?php echo $status;?></td>
								<td class="txt-cdate mhide text-center"><?php echo date('d-m-Y', $row['cdate']);?></td>
								<td class="txt-cdate mhide text-center"><?php echo $label_end_time;?></td>
								<td class="text-center">
									<div class="btn-group btn-more-act">
										<button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
										<div class="dropdown-menu dropdown-menu-right">
											<?php if($row['utype']=="hocsinh"){ ?>
												<?php if(!isset($_PACKET_LIST[$packet])){?>
													<a href="#" onclick="callActionAccount(this,1)" class="dropdown-item" data-id="<?php echo $id;?>" data-activate="<?php echo $activate;?>"><i class="fa fa-pie-chart" aria-hidden="true"></i> Đăng ký dịch vụ</a>
												<?php }?>
												<?php if(isset($_PACKET_LIST[$packet]) && $packet_status=='running'){?>
													<a href="#" onclick="callActionAccount(this,2)" class="dropdown-item" data-id="<?php echo $id;?>" data-activate="<?php echo $activate;?>"><i class="fa fa-pie-chart" aria-hidden="true"></i> Gia hạn</a>
												<?php }?>
												<a href="#" onclick="callActionAccount(this,5)" class="dropdown-item" data-id='<?php echo $str_item;?>'><span class="fa fa-edit"></span> Chi tiết tài khoản</a>
											<?php }else{ ?>
												<a href="#" onclick="callActionAccount(this,5)" class="dropdown-item" data-id='<?php echo $str_item;?>'><span class="fa fa-edit"></span> Chi tiết tài khoản</a>
											<?php } ?>
										</div>
									</div>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="box-pagination">
					<?php echo paging($total_items,$max_row,$cur_page); ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php 
}else{
	header('location:'.ROOTHOST);
}
?>




