<?php
if(isLogin()){
?>
<div class="card">
<div class="card-body">

<div class='col-sm-2'></div>
<div class='col-sm-8'>
	<div class='main-panel'>
		<div class='header'>
			<div class='text-center'>
				<h2 class='title'>Đăng ký tài khoản</h2>
			</div>
		</div>
		<div class='main'>
			<div class='frm'>
				<div class="form-group">
					<div class='err_mess cred text-center'></div>
				</div>
				<div class='col-sm-6'>
					<div class="form-group">
						<label class="control-label">Fullname</label>
						<input type='text' class='form-control' name='txt_name' id='txt_name' placeholder='Fullname' value='' required/>
					</div>
				
					<div class="form-group">
						<label class="control-label">Username (E-mail)</label>
						<input type='text' class='form-control' name='txt_user' id='txt_user' placeholder='E-mail' value='' required/>
					</div>
					<div class="form-group">
						<label class="control-label">Password</label>
						<input type='password' class='form-control' name='txt_pas' id='txt_pas' placeholder='password' value='' required/> 
					</div>
					<div class="form-group">
						<label class="control-label">Confirm password</label>
						<input type='password' class='form-control' name='txt_rpas' id='txt_rpas' placeholder='confirm password' value='' required/> 
					</div>
				</div>
				
					
				
				<div class='col-sm-6'>
				<div class="form-group">
					<label class="control-label">Type</label>
					<select name='cbo_type' id='cbo_type' class='form-control'>
						<option value='student'>Học sinh</option>
						<option value='1'>Phụ huynh</option>
					</select>
				</div>
					<div class="form-group">
					<label class="control-label">Mã lớp</label>
					<input type='text' class='form-control' name='txt_class' id='txt_class' placeholder='Mã lớp' value='' required/> 
				</div>
				
				<div class="form-group">
					<label class="control-label">Giới thiệu</label>
					 <select name="txt_ref_user" class="form-control select txt_user" id="txt_ref_user" data-placeholder="Chọn User">
						<option value="0">Chọn user</option>
						<?php
						$arr=SysGetList('tbl_member', array('fullname','username','email'), " AND username!='$this_user' ORDER BY cdate DESC");
						foreach($arr as $row){
							$fullname=$row['fullname'];
							$user=$row['username'];
							$select='';
							?>
							<option value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Thực thi</label>
					 <select name="txt_sale" class="form-control select txt_sale" id="txt_sale" data-placeholder="Chọn User">
						<option value="0">Chọn user</option>
						<?php
						
						foreach($arr as $row){
							$fullname=$row['fullname'];
							$user=$row['username'];
							$select='';
							?>
							<option value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
						<?php
						}
						?>
					</select>
				</div>
				</div>
				<div class="form-group">
					<label class="custom-control-label"><input type="checkbox" class="custom-control-input" id="isConfirm"> Tôi đồng ý với điều khoản, và cam kết thông tin là chính xác.</label>
				</div>
				<div class='form-group text-center'>
					<button type='button' id='btn-process-register' class='btn btn-primary'>Đăng ký</button>
				</div>
				
				<script>
				$('#txt_name').focus();
				$('#btn-process-register').click(function(){
					register();
				});
				function register(){
					if($('#txt_name').val()==''){
						$('.err_mess').html('Fullname is required!');
						return;
					}
					if($('#txt_user').val()=='' || $('#txt_pas').val()=='' ){
						$('.err_mess').html('Username and Password are required!');
						return;
					}
					if( $('#txt_pas').val()!= $('#txt_rpas').val()){
						$('.err_mess').html('Confirm password not match with password!');
						return;
					}
					var _ischeck=$('#isConfirm').is(':checked')?'yes':'no';
					if(_ischeck=='no'){
						$('.err_mess').html('You must accept our User Agreement and Privacy Policy.!');
						return;
					}
					$('.err_mess').html('');
					
					var _url='<?php echo DOMAIN_REGISTER;?>ajaxs/mem/process_register.php';
					var _data={
						
						'fullname':$('#txt_name').val(),
						'username':$('#txt_user').val(),
						'password':$('#txt_pas').val(),
						'grade':$('#txt_class').val(),
						'type':$('#cbo_type').val(),
						'ref_user':$('#txt_ref_user').val(),
						'saler':$('#txt_sale').val()
					}
					$('#btn-process-register').hide();
					$.post(_url,_data,function(req){
						showmess('Đăng ký tài khoản thành công!','');
						setTimeout(function(){ window.location.href="<?php echo ROOTHOST;?>register";}, 3000);
						
					});
				}
				</script>
			</div>
		</div>
	</div>
</div>
<div class='col-sm-2'></div>
<div class="clearfix"></div>
	</div>
</div>
<?php	
}
?>