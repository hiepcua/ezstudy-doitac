<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$userid=isset($_POST['userid'])?(int)$_POST['userid']:0;
$gid=isset($_POST['gid'])?(int)$_POST['gid']:0;
if(!$objuser->isLogin()){
	die("E01");
} 
$check_permission = $objuser->Permission('user');
if($check_permission==false) die('E02');

$objuser->getList(" AND id=$userid ");
if($objuser->Num_rows()<1) die("E03");
else {
	$row = $objuser->Fetch_Assoc();
}
?>
<style type="text/css">
	hr{
		margin-top: 0;
		margin-bottom: 15px;
	}
	label.title{font-size: 16px;font-weight: 500;}
</style>
<form id="frm-register" class="form-horizontal"  name="frm-register" method="" action="" enctype="multipart/form-data">
	<input type="hidden" class="form-control" id="txt_gid" value="<?php echo $gid;?>">
	<input type="hidden" id="txt_user_id" name="txt_user_id" value="<?php echo $row['id'] ?>" />
	<label class="title">Thông tin cá nhân</label><hr/>
	<div class="form-group">
		<div class="col-md-6">
			<label>Họ đệm<small class="cred"> (*)</small></label>
			<input type="text" id="txt_lastname" name="txt_lastname" class="form-control" value="<?php echo $row['lastname'];?>" placeholder="Họ đệm">
			<small id="er1" class="cred"></small>
		</div>
		<div class="col-md-6">
			<label>Tên<small class="cred"> (*)</small></label>
			<input type="text" id="txt_firstname" name="txt_firstname" class="form-control" value="<?php echo $row['firstname'];?>" placeholder="Tên">
			<small id="er0" class="cred"></small>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6">
			<label>CMTND</label>
			<input type="text" id="txt_cmtnd" name="txt_cmtnd" class="form-control" value="<?php echo $row['identify'];?>" placeholder="Chứng minh thư nhân dân">
			<small id="er7" class="cred"></small>
		</div>
		<div class="col-md-6">
			<label>Ngày sinh</label>
			<input type="date" id="txt_birthday" name="txt_birthday" class="form-control" value="<?php echo date('Y-m-d',strtotime($row['birthday']));?>" placeholder="Ngày sinh">
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6">
			<label>Giới tính</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" value="0" name="opt_gender" <?php if($row['gender']==0) echo 'checked';?>>Nam</label>
				<label class="radio-inline"><input type="radio" value="1" name="opt_gender" <?php if($row['gender']==1) echo 'checked';?>>Nữ</label>
			</div>
		</div>
	</div>
	<br>
	<label class="title">Thông tin liên hệ</label><hr/>
	<div class="form-group">
		<div class="col-md-6">
			<label>Phone</label>
			<input type="number" id="txt_phone" name="txt_phone" class="form-control" value="<?php echo $row['phone'];?>" placeholder="Số điện thoại">
			<small id="er5" class="cred"></small>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6">
			<label>Email</label>
			<input type="email" id="txt_email" name="txt_email" class="form-control" value="<?php echo $row['email'];?>" placeholder="Email">
			<small id="er6" class="cred"></small>
		</div>
	</div>
	<label>Địa chỉ</label>
	<textarea class="form-control" rows="3" id="txt_address" name="txt_address" placeholder="Địa chỉ của bạn"><?php echo $row['address'];?></textarea>
	<br>
	<div class="clearfix"></div><br>
	<input type="hidden" name="cmd_update_user"/>
	<button type="button" class="btn btn-primary" id="cmd_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</button>
	<button type="button" class="btn btn-default" id="cmd_cancel"><i class="fa fa-undo" aria-hidden="true"></i> Hủy</button>
</form>
<div class="clearfix"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#cmd_save').click(function(){
			if(check_validate()==true) {
				var gid=$('#txt_gid').val()!='undefined'?$('#txt_gid').val():0;
				var userid=$('#txt_user_id').val();
				var data = $('#frm-register').serializeArray();
				data.push( {name:'txt_gid', value:gid},{name:'txt_user_id', value:userid} );
				var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/process_update_user.php';
				$.post(url,data,function(req){
				
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E03'){showMess('Không có quyền sửa người dùng cho nhóm này!','error');}
					if(req=='E04'){showMess('Có lỗi trong quá trình thực hiện!','error');}
					else {
						getUserByGroup(gid);
						showMess('Success!','success');
					}
				});
			}else{
				alert("Lỗi!");
			}
		});
		$('#cmd_cancel').click(function(){
			$('#myModalPopup .modal-header .modal-title').html('Sửa thông tin người dùng');
			$('#myModalPopup .modal-body').html('<p>Loadding...</p>');
			$('#myModalPopup').modal('hide');
		});
	})
	function getUserByGroup($gid){
		var url='ajaxs/user/getUserByGroup.php';
		$.post(url,{'gid':$gid},function(req){
			$('.user_list .list').html(req);
		});
	}
	function check_validate(){
		if ($('#txt_firstname').val() =='') {
			$('#er0').text('Không được bỏ trống');
			return false;
		}else{
			$('#er0').text('');
		}
		if ($('#txt_lastname').val() =='') {
			$('#er1').text('Không được bỏ trống');
			return false;
		}else{
			$('#er1').text('');
		}
		/* if ($('#txt_phone').val() =='') {
			$('#er5').text('Không được bỏ trống');
			return false;
		}else{
			$('#er5').text('');
		}
		if ($('#txt_email').val() =='') {
			$('#er6').text('Không được bỏ trống');
			return false;
		}else{
			$('#er6').text('');
		}
		var cmtnd = $('#txt_cmtnd').val().length;
		if (cmtnd<9 || cmtnd>12) {
			$('#er7').text('CMND chỉ cho phép nhập số và có độ dài từ 9 đến 12 số.');
			return false;
		}else{
			$('#er7').text('');
		} */
		return true;
	}
</script>