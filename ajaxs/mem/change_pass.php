<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
$objdata=new CLS_MYSQL;
if(isLogin()){
$user = isset($_POST['user'])?strip_tags(addslashes($_POST['user'])):'';
?>

    <div class="box-modal">
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
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" value="Hủy bỏ">Cancel</button>
        <button type="button" name="btnsave" id="btnsave" class="btn btn-primary">Lưu</button>
    </div>
<div class="clearfix"></div>
<script>
$(document).ready(function(){
	$("#btnsave").click(function(){
		var pass = $("#txtpass").val();
		var url = "<?php echo ROOTHOST;?>ajaxs/mem/process_changepass.php";
		if(pass=="") {$("#txtpass").focus(); return false;}
		else if(pass.length<6) {$("#txtpass").focus(); alert('Mật khẩu từ 6 ký tự trở lên'); return false;}
		$('#myModal .modal-body').html('Loading...');
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
<?php }?>