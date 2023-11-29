<?php
if($UserLogin->isLogin()) {
	$PERMISSION = $UserLogin->getInfo('isroot');
} ?>
<div id="left_sidebar">
	<div class="sidebar_top"></div>
	<ul class="sidebar_body">
		<li><a href="<?php echo ROOTHOST_ADMIN;?>" class='toggle' data-toggle="tooltip" data-placement="right" data-original-title="Trang Admin">
			<i class="fa fa-desktop" aria-hidden="true"></i> <span>Trang chủ Admin</span>
		</a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>member" class="dark"><i class="fa fa-users" aria-hidden="true"></i> <span>Thành viên</span></a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>member?task=withdraw" class="dark"><i class="fa fa-users" aria-hidden="true"></i> <span>Rút Pitnex (bussiness)</span></a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>?com=card" class="dark"><i class="fa fa-users" aria-hidden="true"></i> <span>Kho thẻ</span></a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>?com=prices" class="dark"><i class="fa fa-users" aria-hidden="true"></i> <span>Giá pitnex</span></a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>guser" class="dark"><i class="fa fa-user" aria-hidden="true"></i> <span>Nhóm quyền</span></a></li>
		<li><a href="<?php echo ROOTHOST_ADMIN;?>user" class="dark"><i class="fa fa-users" aria-hidden="true"></i> <span>Quản trị viên</span></a></li>
	</ul>
</div>
<script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	})
</script>