<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objdata=new CLS_MYSQL;
if(!$objuser->isLogin()) die("E01");
$user = isset($_POST['user'])?strip_tags(addslashes($_POST['user'])):'';
?>
<div class="row form-group">
	<div class="col-md-4 col-xs-4 text">Username</div>
	<div class="col-md-6 col-xs-8"><?php echo $user;?></div>
</div>
<div class="row form-group">
	<div class="col-md-4 col-xs-4 text">Mật khẩu mới</div>
	<div class="col-md-6 col-xs-8">
		<input type="password" name="txtpass" id="txtpass" class="form-control" value="" required>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-4 col-xs-4 text">&nbsp;&nbsp;</div>
	<div class="col-md-6 col-xs-8">
		<button type="button" name="btnsave" id="btnsave" class="btn btn-primary">Lưu</button>
		<button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal">Thoát</button>
	</div>
</div>
<div class="clearfix"></div>
<script>
$(document).ready(function(){
	$("#btnsave").click(function(){
		var pass = $("#txtpass").val();
		var url = "<?php echo ROOTHOST_ADMIN;?>ajaxs/member/process_changepass.php";
		if(pass=="") {$("#txtpass").focus(); return false;}
		else if(pass.length<6) {$("#txtpass").focus(); alert('Mật khẩu từ 6 ký tự trở lên'); return false;}
		$('#myModalPopup .modal-body').html('Loading...');
		$.post(url,{'user':'<?php echo $user;?>','pass':pass},function(req){
			console.log(req);
			if(req=="E01") showMess("Vui lòng đăng nhập hệ thống","error");
			else if(req=="success"){
				showMess("Đổi mật khẩu thành công. MK mới đã được hệ thống tự động gửi vào email thành viên.");	
				setTimeout(function(){window.location.reload();},3000);
			}else showMess(req);
			//setTimeout(function(){window.location.reload();},3000);
		});
	})
})
</script>