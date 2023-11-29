<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
require_once("../../libs/cls.members.php");
$objuser=new CLS_USER;
$objmem=new CLS_MEMBERS;
if(!$objuser->isLogin()){
	die("E01");
}
$mc_id = isset($_POST['mc_id'])?(int)$_POST['mc_id']:0;
$fee = isset($_POST['fee'])?(int)$_POST['fee']:'';
?>
<style type="text/css">
	hr{margin-top: 0;margin-bottom: 15px;}
	label.title{font-size: 16px;font-weight: 500;}
</style>
<form id="frm-register" class="form-horizontal"  name="frm-register" method="" action="" enctype="multipart/form-data">
	<input type="hidden" name="mc_id" value="<?php echo $mc_id;?>">
	<?php
	if($fee==1){
		?>
		<input type="hidden" name="frm_fee">
		<label class="title">Thông tin đóng học phí</label><hr/>
		<div class="form-group">
			<div class="col-md-6">
				<label>Học phí<small class="cred"> (*)</small></label>
				<input type="number" id="txt_money" name="txt_money" min="0" class="form-control" value="" placeholder="Học phí">
				<small id="er1" class="cred"></small>
			</div>
		</div>
		<?php
	}else{
		?>
		<label class="title">Thông tin hồ sơ học viên</label><hr/>
		<div class="form-group">
			<div class="col-md-6">
				<label>Tên hồ sơ<small class="cred"> (*)</small></label>
				<input type="text" id="txt_name" name="txt_name" class="form-control" value="" placeholder="Tên hồ sơ">
				<small id="er1" class="cred"></small>
			</div>
		</div>
		<?php
	}
	?>
	<div class="form-group col-md-12">
		<label>Chú thích</label>
		<textarea class="form-control" rows="3" id="txt_note" name="txt_note" placeholder="Chú thích"></textarea>
	</div>
	<br>
	<div class="clearfix"></div><br>
	<button type="button" class="btn btn-primary" id="cmd_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</button>
	<button type="button" class="btn btn-default" id="cmd_cancel"><i class="fa fa-undo" aria-hidden="true"></i> Hủy</button>
</form>
<div class="clearfix"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#cmd_save').click(function(){
			if(check_validate()==true){
				var data = $('#frm-register').serializeArray();
				var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/log/process_update_log.php';
				$.post(url,data,function(req){
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E04'){showMess('Có lỗi trong quá trình thực hiện!','error');}
					else {
						getListProfile();
						getListFee();
						showMess('Success!','success');
					}
				});
			}
		});
		$('#cmd_cancel').click(function(){
			$('#myModalPopup .modal-header .modal-title').html('Sửa thông tin người dùng');
			$('#myModalPopup .modal-body').html('<p>Loadding...</p>');
			$('#myModalPopup').modal('hide');
		});
	})
	function check_validate(){
		if ($('#txt_money').val() =='') {
			$('#er1').text('Không được bỏ trống');
			return false;
		}else{
			$('#er1').text('');
		}
		if ($('#txt_name').val() =='') {
			$('#er1').text('Không được bỏ trống');
			return false;
		}else{
			$('#er1').text('');
		}
		return true;
	}
</script>