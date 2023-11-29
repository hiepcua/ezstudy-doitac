<?php
if(isLogin()){
	$isroot=getInfo('isroot');
	if($isroot!=1){
		echo '<div class="card"><div class="card-body">Chức năng này chỉ sử dụng cho tài khoản là Đại lý. Xin cảm ơn!</div></div>';
		die();
	}
	$strwhere="";
	$type=isset($_GET['type'])? (int)$_GET['type']:'';
	if($type!=''){
		$title_label=' Danh sách Saler';
		$strwhere.=" AND isroot=''";
	}else{
		$title_label=' Danh sách Đại lý';
		$strwhere.=" AND isroot=1";
	}
	
	$this_user=getInfo('username');
	$page_name="LIST_MEM";
	
	if(isset($_POST['txtkeyword'])) $_SESSION['KEYWORD'.$page_name]=addslashes($_POST['txtkeyword']);
	$keyword=isset($_SESSION['KEYWORD'.$page_name]) ? $_SESSION['KEYWORD'.$page_name]:'';
	if($keyword!='') $strwhere.=" AND (`username` like '%$keyword%' OR `fullname` like '%$keyword%' OR `phone` like '%$keyword%' OR `email` like '%$keyword%')";
	if(!isset($_SESSION['CUR_PAGE_'.$page_name])) $_SESSION['CUR_PAGE_'.$page_name]=1;
	if(isset($_POST['txtCurnpage'])) $_SESSION['CUR_PAGE_'.$page_name]=$_POST['txtCurnpage'];
	$cur_page=$_SESSION['CUR_PAGE_'.$page_name];
	$start=($cur_page-1)*MAX_ROWS;
	$total_items = SysCount('tbl_user',$strwhere);
	$strwhere.=" ORDER BY cdate DESC LIMIT ".$start.",".MAX_ROWS;
	$obj=SysGetList('tbl_member',array(),$strwhere,false);
	?>
	<form id="frm_list" name="frm_list" method="post" action="">
		<div class="group-box">
			<div class="box-card">
				<div class="action-tool">
					<h2 class="label-title">
						<?php echo $title_label;?>
					</h2>
					<?php require_once('modules/toolbar.php');?>
				</div>
				<div class="clearfix"></div>
				
				<?php
				$array=SysGetList('tbl_member',array(), " AND par_user='$this_user' $strwhere", false);
				if($array->Num_rows()>0){
					?>
					<table class="table table-main">
						<thead>
							<tr>
								<th class="stt text-center mhide"><strong>STT</strong></th>
								<th ><strong>Tài khoản</strong></th>
								<th class="text-center mhide"><strong>Quyền</strong></th>
								<th class="mhide"><strong>Ngày tạo</strong></th>
								<th class="td-action"><strong>Thao tác</strong></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$stt=0;
							while($r_mem=$array->Fetch_Assoc()){
								$stt++;
								$jdate=date('d-m-Y',$r_mem['cdate']);
								$id=$username=$r_mem['username'];
								$arr_user[]=$username;
								$nhom=$r_mem['isroot']==1? 'Đại lý': 'Sale';
								if($r_mem['rates']!=''){
									$rates=json_decode($r_mem['rates'], true);
									$kichhoat=isset($rates['0,0']['kichhoat'])? $rates['0,0']['kichhoat']:'';
									$giahan=isset($rates['0,0']['giahan'])? $rates['0,0']['giahan']:'';
									$lienket=isset($rates['0,0']['lienket'])? $rates['0,0']['lienket']:'';
									
								}
								?>
								<tr>
									<td class="stt text-center mhide"><?php echo $stt;?></td>
									<td >
										<p class="td-text"><b><?php echo $username?></b></p>
										<ul class="list-inline">
											<li><?php echo $r_mem['fullname'];?></li>
											<li>Phone: <a href="tel:<?php echo $r_mem['phone'];?>"><?php echo $r_mem['phone'];?></a></li>
										</ul>
										<ul class="list-inline box-tag">
											<li><span class="tag">Kích hoạt:<?php echo $kichhoat;?>%</span></li>
											<li><span class="tag">Gia hạn:<?php echo $giahan;?>%</span></li>
											<li><span class="tag">Liên kết:<?php echo$lienket;?>%</span></li>
										</ul>
									</td>
									<td class="text-center mhide"><?php echo $nhom;?></td>
									<td class="txt-cdate mhide"><?php echo $jdate;?></td>
									<td class="text-center">
										<div class="btn-group btn-more-act">
											<button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="<?php echo ROOTHOST."members/edit/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><span class="fa fa-edit"></span> Chỉnh sửa thông tin</a>
												<a href="<?php echo ROOTHOST."members/config/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><i class="fa fa-pie-chart" aria-hidden="true"></i> Tỉ lệ thưởng</a>
												<a href="<?php echo ROOTHOST."bonus-report/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><i class="fa fa-line-chart" aria-hidden="true"></i> Doanh số</a>
												<span class="change_pass dropdown-item" dataid="<?php echo $id;?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Đổi mật khẩu</span>
												<a href="#" onclick="callActionMember(this, 3)" class="dropdown-item delete"  data-id="<?php echo $id;?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>
											</div>
										</div>
									</td>
								</tr>
							<?php }?>
						</tbody>
					</table>
					<?php
				}
				?>

				
				<div class="box-pagination">
					<?php echo paging($total_items,MAX_ROWS,$cur_page); ?>
				</div>
			</div>
		</div>
		<?php
	}else{
		header('location:'.ROOTHOST.'login');
	}
	?>
	<script>
		$(".change_pass").click(function(){
			var user = $(this).attr('dataid');
			var url = "<?php echo ROOTHOST?>ajaxs/mem/change_pass.php";
			$('#myModal .modal-body').html('Loading...');
			$.post(url,{'user':user},function(req){
				$('#myModal .modal-title').html('Đổi mật khẩu');
				$('#myModal .modal-body').html(req);
				$('#myModal').modal('show');
			})
		})
	</script>