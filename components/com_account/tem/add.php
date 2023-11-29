<?php
if(!isLogin()){
	echo '<div class="text-center">Chưa đăng nhập</div>'; exit();
}
$this_user=getInfo('username');
$get_utype = isset($_GET['utype']) ? antiData($_GET['utype']):"hocsinh";

// Danh sách khối lớp
$url = API_DC.'grade';
$json = array();
$json['key'] = PIT_API_KEY;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$res_grade = Curl_Post($url,json_encode($post_data));
$arr_grade = $res_grade['data'];
?>
<div class="card">
	<div class="card-body">
		<div class="action-tool">
			<h2 class="label-title">
				Thêm mới Account
			</h2>
			<div id="menus" class="toolbars">
				<a class="btn btn-warning" href="<?php echo ROOTHOST.COMS;?>" title="Đóng"><i class="fa fa-close" aria-hidden="true"></i><span class="hiden-label"> Đóng</span></a>
			</div>
		</div>

		<div class='col-sm-2'></div>
		<div class='col-sm-8'>
			<div class='main-panel1'>
				<?php if($get_utype=="hocsinh"){ ?>
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
										<option value='hocsinh' <?php echo $get_utype=='hocsinh' ? "selected":"";?>>Học sinh</option>
										<option value='chame' <?php echo $get_utype=='chame' ? "selected":"";?>>Phụ huynh</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Khối lớp</label>
									<select name='cbo_class' id='cbo_class' class='form-control'>
										<?php
										foreach ($arr_grade as $key => $value) {
											echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
										}
										?>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Giới thiệu</label>
									<select name="txt_ref_user" class="form-control select txt_user" id="txt_ref_user" data-placeholder="Chọn User">
										<option value="<?php echo $this_user;?>"><?php echo $this_user;?></option>
										<?php
										$arr=SysGetList('tbl_member', array('fullname','username','email'), " AND username!='$this_user' ORDER BY cdate DESC");
										foreach($arr as $row){
											$user = $row['username'];
											$fullname = $row['fullname'];
											echo '<option value="'.$user.'" data-thumb="avatar_default.png" data-info="'.$fullname.'">'.$user.'</option>';
										}
										?>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Saler</label>
									<select name="txt_sale" class="form-control txt_sale" id="txt_sale" data-placeholder="Chọn User">
										<option value="<?php echo $this_user;?>"><?php echo $this_user;?></option>

									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="custom-control-label"><input type="checkbox" class="custom-control-input" id="isConfirm"> Tôi đồng ý với điều khoản, và cam kết thông tin là chính xác.</label>
							</div>

							<div class='form-group text-center'>
								<button type='button' id='btn-process-register' class='btn btn-primary'>Đăng ký</button>
								<a class="btn btn-warning" href="<?php echo ROOTHOST.COMS;?>" title="Đóng"><i class="fa fa-close" aria-hidden="true"></i><span class="hiden-label"> Đóng</span></a>
							</div>
						</div>
					</div>
				<?php }else if($get_utype=="chame"){ ?>
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
										<option value='hocsinh' <?php echo $get_utype=='hocsinh' ? "selected":"";?>>Học sinh</option>
										<option value='chame' <?php echo $get_utype=='chame' ? "selected":"";?>>Phụ huynh</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Giới thiệu</label>
									<select name="txt_ref_user" class="form-control select txt_user" id="txt_ref_user" data-placeholder="Chọn User">
										<option value="<?php echo $this_user;?>"><?php echo $this_user;?></option>
										<?php
										$arr=SysGetList('tbl_member', array('fullname','username','email'), " AND username!='$this_user' ORDER BY cdate DESC");
										foreach($arr as $row){
											$fullname=$row['fullname'];
											$user=$row['username'];
											$select='';
											?>
											<option value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $fullname;?>"><?php echo $user;?></option>
											<?php
										}
										?>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">Saler</label>
									<select name="txt_sale" class="form-control txt_sale" id="txt_sale" data-placeholder="Chọn User">
										<option value="<?php echo $this_user;?>"><?php echo $this_user;?></option>

									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="custom-control-label"><input type="checkbox" class="custom-control-input" id="isConfirm"> Tôi đồng ý với điều khoản, và cam kết thông tin là chính xác.</label>
							</div>

							<div class='form-group text-center'>
								<button type='button' id='btn-process-register' class='btn btn-primary'>Đăng ký</button>
								<a class="btn btn-warning" href="<?php echo ROOTHOST.COMS;?>" title="Đóng"><i class="fa fa-close" aria-hidden="true"></i><span class="hiden-label"> Đóng</span></a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class='col-sm-2'></div>
		<div class="clearfix"></div>
	</div>
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
			$('.err_mess').html('Bạn chưa tích chọn đồng ý điều khoản của chúng tôi!');
			return;
		}
		$('.err_mess').html('');

		var _url='<?php echo ROOTHOST;?>ajaxs/account/process_register.php';
		var _data={
			'fullname':$('#txt_name').val(),
			'username':$('#txt_user').val(),
			'password':$('#txt_pas').val(),
			'grade':$('#cbo_class').val(),
			'type':$('#cbo_type').val(),
			'ref_user':$('#txt_ref_user').val(),
			'saler':$('#txt_sale').val()
		}

		// $('#btn-process-register').hide();
		$.post(_url,_data,function(req){
			// console.log(req);
			if(req=='user_exist'){
				alert('Tài khoản này đã tồn tại. Vui lòng nhập tài khoản khác!');
				$('#btn-process-register').hide();
			}
			if(req=='success'){
				showMess('Đăng ký tài khoản thành công!','');
				setTimeout(function(){ window.location.href="<?php echo ROOTHOST.'account/'.$_GET['utype'];?>";}, 2000);
			}
		});
	}
</script>