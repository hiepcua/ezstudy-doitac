<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
	#boxcom .com {float:left; width:45%}
</style>
<?php
defined("ISHOME") or die("Can't acess this page, please come back!");
// Check quyền
$objuser=new CLS_USER;
if(!$objuser->isLogin()) die("E01");
$check_permission = $objuser->Permission('user');
$check_permis_group = $objuser->Permission('gusers');
if($check_permission==false || $check_permis_group==false) die('E02');
?>
<div class="com_header color">
	<h3 class="title"><i class="fa fa-list" aria-hidden="true"></i> Thêm mới thành viên</h3>
	<div class="pull-right">
		<form id="frm_menu" name="frm_menu" method="post" action="">
			<input type="hidden" name="txtorders" id="txtorders" />
			<input type="hidden" name="txtids" id="txtids" />
			<input type="hidden" name="txtaction" id="txtaction" />

			<ul class="list-inline">
				<li><a class="btn btn-default"  href="<?php echo ROOTHOST_ADMIN.COMS;?>" title="Đóng"><i class="fa fa-sign-out" aria-hidden="true"></i> Đóng</a></li>
			</ul>
		</form>
	</div>
</div><br/>

<form name="frm-edit" id="frm-action" method="POST">
	<div class="mess cred"></div>
	<div class="body row">
		<div class="col-md-4 col-xs-12">
			<div class="form-group">
				<label><i class="fa fa-user"></i> Username: <small class="cred">(*)</small></label>
				<input type="text" id="txt_username" name="txt_username" class="form-control required">
			</div>

			<div class="form-group">
				<label><i class="fa fa-key"></i> Password: <small class="cred">(*)</small></label>
				<input type="text" name="txt_password" class="form-control required" minlength="6">
			</div>

			<div class="form-group">
				<label>Họ và tên: <small class="cred">(*)</small></label>
				<input type="text" name="txt_fullname" class="form-control required">
			</div>

			<div class="form-group">
				<i class="fa fa-toggle-on" aria-hidden="true"></i> Đại lý:</label>
				<input id="toggle-one" type="checkbox" name="txt_isroot" data-size="mini">
			</div>

			<div class="form-group">
				<i class="fa fa-toggle-on" aria-hidden="true"></i> Active:</label>
				<input id="toggle-one1" type="checkbox" name="txt_active" data-size="mini">
			</div>
		</div>

		<div class="col-md-8 col-xs-12">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Ngày sinh: </label>
						<input type="date" name="txt_birthday" class="form-control">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>SĐT: </label>
						<input type="text" name="txt_phone" class="form-control">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Email: </label>
						<input type="text" name="txt_email" class="form-control">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>CMT: </label>
						<input type="text" name="txt_cmt" class="form-control">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Ngày cấp: </label>
						<input type="date" name="txt_cmt_date" class="form-control">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>Nơi cấp: </label>
						<input type="text" name="txt_cmt_place" class="form-control">
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<label>Địa chỉ: </label>
						<textarea class="form-control" name="txt_address" rows="2"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<hr/>
	<div class="row form-group text-center">
		<input type="button" name="cmd_save" id="cmd_save" value="Tạo tài khoản" class="btn btn-primary">
		<a href="<?php echo ROOTHOST_ADMIN."member";?>" class="btn btn-default">Đóng</a>
	</div>
</form>

<script type="text/javascript">
	$(function() {
		$('#toggle-one').bootstrapToggle();
		$('#toggle-one1').bootstrapToggle();
	})

	function checkall(name,status){
		var objs=document.getElementsByName(name);
		for(i=0;i<objs.length;i++)
			objs[i].checked=status;
	}

	function checkonce(name,all){
		var objs=document.getElementsByName(name);
		var flag=true;
		for(i=0;i<objs.length;i++){
			if(objs[i].checked!=true)
			{
				flag=false;
				break;
			}
		}
		document.getElementById(all).checked=flag;
	}
	$(document).ready(function(){
		$('#cmd_save').click(function(){
			if(validForm()){
				var data = $('#frm-action').serializeArray();
				var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/member/process_add.php'; 
				$.post(url, data, function(req){
					if(req=='E01'){
						showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');
					}else if(req=='Username exist'){
						$('.mess').html('Tài khoản đã được sử dụng');
						$("#txt_username").focus();
					}else if(req=='E03'){
						showMess('Không có quyền sửa nhóm này!','error');
					}else if(req=="success") {
						showMess('Tạo tài khoản thành công!','success');
						setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS;?>';},2000);
					}else showMess(req,'error');
				});
			}
		});
	})

	function validForm(){
		var flag = true;
		$('#frm-action .required').each(function(){
			var val = $(this).val();
			if(!val || val=='' || val=='0') {
				$(this).parents('.form-group').addClass('error');
				flag = false;
			}

			if(flag==false) {
				$('.mess').html('Những mục có đánh dấu * là những thông tin bắt buộc.');
			}
		});
		return flag;
	}
</script>
