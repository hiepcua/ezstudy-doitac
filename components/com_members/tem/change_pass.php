<?php
if(isLogin()){
?>
<div class="card">
<div class="card-body">
<div class='col-sm-4'></div>
<div class='col-sm-4'>
		<div class='box'>
			<div class='header'>
				<div class='text-center'>
					<h2 class='title-name'>ĐỔI MẬT KHẨU</h2>
				</div>
			</div>
			<div class='main'>
				<div class='col-sm-12'>
				<div id="messageError" class="mess text-center" style="color:red;"></div>
				<div id="messageSuccess" class="mess text-center" style="color:green;"></div>
				<form name="frm_update_member" id="frm_update_member" action=""  method="POST" class="form_add">
					<div class="form-group">
						<div class="txt"><i class="fa fa-key"></i> Mật khẩu cũ</div>
						<input type="password"  class="form-control" id="txtpass" name="txtpass" value="" required>
					</div>
					<div class="form-group">
						<div class="txt"><i class="fa fa-key"></i> Mật khẩu mới</div>
						<input type="password" class="form-control" name="txt_newpass" id="txt_newpass" value="" required minlength="6">
					</div>
					<div class="form-group">
						<div class="txt"><i class="fa fa-key"></i> Nhập lại mật khẩu mới</div>
						<input type="password" class="form-control" name="txt_newpass2" id="txt_newpass2" value="" required minlength="6">
					</div>
					<div class="form-group text-center"><button type="button" class="btn btn-primary" id="btn_process_changepass">Change password >></button></div>
					<br>
				</form>
				</div>
				<div class='clearfix'></div>
				<script>
				$('#txtpass').focus();
				$('#btn_process_changepass').click(function(){
					changepass();
				})
				$('#txt_newpass,#txt_newpass2').keyup(function(e){
					 var code = (e.keyCode ? e.keyCode : e.which);
					if (code==13) {
						e.preventDefault();
						changepass();
					}
				})
				function changepass(){
					if(!checkfrm()) return;
					var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_changepass.php';
					var _data={
						'oldpass':$('#txtpass').val(),
						'password':$('#txt_newpass').val()
					}
					$('#messageError').html('');
					$.post(_url,_data,function(req){
						console.log(req);
						if(req=='success'){
							$('#messageSuccess').html('Đổi mật khẩu thành công!');
						}else $('#messageError').html(req);
					});
				}
				function checkfrm() {
					if($('#txtpass').val()=='') {
						$('#messageError').html('Vui lòng nhập mật khẩu!');
						$('#txtpass').focus();
						return false;
					}
					if($('#txt_newpass').val().length<6) {
						$('#messageError').html('Mật khẩu phải từ 6 ký tự trở lên');
						$('#txt_newpass').focus();
						return false;
					}
					if($('#txt_newpass').val()!=$('#txt_newpass2').val()) {
						$('#messageError').html('Xác nhận mật khẩu không khớp');
						$('#txt_newpass2').focus();
						return false;
					}
					return true;
				}
				</script>
			</div>
		</div>
	</div>
</div>
<div class='col-sm-4'></div>
<div class="clearfix"></div>
</div>
</div>
<?php }else{
	header('location:'.ROOTHOST.'login');
}?>