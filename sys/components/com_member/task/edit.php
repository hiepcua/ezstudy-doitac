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

$name='';$intro=''; $msg=$permission=''; $id=$par_id=0;
if(isset($_GET["id"]))
	$id = addslashes(strip_tags($_GET["id"]));
else die('Không có mã user');
$obj->getList(" AND `username`='".$id."'");
$r=$obj->Fetch_Assoc();
$check_kyc = (int)$r['iskyc']==1?"yes":"no";
$check_2fa = $r['is2fa'];
$joindate = $r['cdate'];
$isactive = $r['isactive'];
$isroot = $r['isroot'];
$kyc1 = !empty($r['kyc1']) ? ROOTHOST.$r['kyc1'] : ROOTHOST.'/images/thumb_img.png';
$kyc2 = !empty($r['kyc2']) ? ROOTHOST.$r['kyc2'] : ROOTHOST.'/images/thumb_img.png';
?>
<div class="com_header color">
	<h3 class="title"><i class="fa fa-list" aria-hidden="true"></i> Cập nhật thành viên</h3>
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
</div>

<form name="frm-edit" id="frm-action" method="POST">
	<div class="mess cred"></div>
	<div class="body row">
		<div class="col-md-4 col-xs-12">
			<div class="form-group">
				<label><i class="fa fa-user"></i> Username: <small class="cred">(*)</small></label>
				<strong class="pull-right"><?php echo $r['username'];?></strong>
			</div>

			<div class="form-group">
				<i class="fa fa-key"></i> Password: <a href="#" id="btn-changepass" class="change_pass pull-right" dataid="<?php echo $id;?>">Change password</a>
			</div>

			<div class="form-group">
				<i class="fa fa-user"></i> Par User: <strong class="pull-right"><?= $r['par_user'];?></strong>
			</div>

			<div class="form-group">
				<i class="fa fa-calendar"></i> Join date: <strong class="pull-right"><?php echo trim($joindate)!='N/a'?date('Y-m-d',$joindate):'';?></strong>
			</div>

			<div class="form-group">
				<i class="fa fa-suitcase"></i> Đại lý:
				<div class=" pull-right">
					<input id="toggle-one1" <?php if($isroot==1) echo 'checked';?> type="checkbox" name="txt_isroot" data-size="mini">
				</div>
			</div>

			<div class="form-group">
				<i class="fa fa-tree"></i> Active:
				<div class=" pull-right">
					<input id="toggle-one" <?php if($isactive==1) echo 'checked';?> type="checkbox" name="txt_active" data-size="mini">
				</div>
			</div>

			<div class='form-group wallet_qrcode text-center' id='wallet_qrcode'>
				<?php
				/*if($check_kyc=='yes'){
				$obj=SysGetList('tbl_member_wallet',array()," AND username='$id'");
				$wallet=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
				if($wallet!==''){
				$url="https://chart.googleapis.com/chart?cht=qr&choe=UTF-8&chs=300x300&chl=$wallet";
				echo '<div class="text-center"><img src="'.$url.'" /></div>';
				echo '<div class="text-center">Wallet: '.$wallet.'</div>';
				}
				}else{
					echo "<span class='red'>You have not kyc. Let kyc now!</span>";
				} */?>
			</div>

			<!--<div class='form-group'>
			<i class="fa fa-cc-discover"></i> 2FA:
			<label class="switch pull-right">
			<input id="check_2fa" type="checkbox" disabled <?php /*if($check_2fa==1) echo 'checked'*/?> value="0">
			<span class="slider round"></span>
			</label>
			</div>
			<div class='form-group 2fa_qrcode text-center' id='2fa_qrcode'>
			<?php
			/*if((int)$r['is2fa']==1){
			$gsecret=$r['gsecret'];
			$ga = new PHPGangsta_GoogleAuthenticator();
			echo '<img src="'.$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$id).'" />';
				echo "<div>2FA Qr-Code</div>";
			} */?></div>-->
		</div>

		<div class="col-md-8 col-xs-12">
			<div class="row form-group">
				<label class="col-md-2"></label>
				<div class="col-md-4"><span class='msg'><?php echo $msg;?></span></div>
			</div>

			<div class="row form-group">
				<label class="col-md-2 control-label">Họ và tên</label>
				<div class="col-md-4">
					<input class="form-control" id="username" name="username" value="<?php echo $r['username'];?>" type="hidden">
					<input class="form-control" id="fullname" name="fullname" value="<?php echo $r['fullname'];?>" type="text">
				</div>
				<label class="col-md-2 control-label">Ngày sinh</label>
				<div class="col-md-4">
					<input class="form-control" id="birthday" name="birthday" value="<?php echo (int)$r['birthday']>0?date('Y-m-d', $r['cmt_date']):null;?>" type="date">
				</div>
			</div>

			<div class="row form-group">
				<label class="col-md-2 control-label">SĐT</label>
				<div class="col-md-4">
					<input class="form-control" id="phone" name="phone" value="<?php echo $r['phone'];?>" type="text">
				</div>
				<label class="col-md-2 control-label">Email</label>
				<div class="col-md-4">
					<input class="form-control" id="email" name="email" value="<?php echo $r['email'];?>" type="email">
				</div>
			</div>

			<div class="row form-group">
				<label class="col-md-2 control-label">CMT</label>
				<div class="col-md-4">
					<input class="form-control" id="cmt" name="cmt" value="<?php echo $r['cmt'];?>" type="text">
				</div>
				<label class="col-md-2 control-label">Ngày cấp</label>
				<div class="col-md-4">
					<input class="form-control" id="cmt_date" name="cmt_date" value="<?php echo (int)$r['cmt_date']>0?date('Y-m-d', $r['cmt_date']):null;?>" type="date">
				</div>
			</div>

			<div class="row form-group">
				<label class="col-md-2 control-label">Nơi cấp</label>
				<div class="col-md-4">
					<input class="form-control" id="cmt_place" name="cmt_place" value="<?php echo $r['cmt_place'];?>" type="text">
				</div>
				<label class="col-md-2 control-label">Địa chỉ</label>
				<div class="col-md-4">
					<input class="form-control" id="address" name="address" value="<?php echo $r['address'];?>" type="text">
				</div>
			</div>
		</div>
	</div>

	<hr>
	<div class="row form-group text-center">
		<input type="button" name="cmd_save" id="cmd_save" value="Cập nhật thông tin" class="btn btn-primary">
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
		$("#check_isbus").click(function(){
			if($("#check_isbus").val()==0) {
				var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/member/process_isbus.php'; 
				$.post(url,{'username':'<?php echo $id;?>'},function(req){
					if (req=="success") {
						showMess('Success!','success');
						setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},1000);
					}else showMess(req,'error');
				})
			}
		});

		$("#check_kyc").click(function(){
			console.log($(this).prop('checked'));
			if($("#check_kyc1").val()==1 && $("#check_kyc2").val()==1) {
				var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/<?= COMS;?>/process_kyc.php'; 
				$.post(url,{'username':'<?php echo $id;?>'},function(req){
					console.log(req);
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E03'){showMess('Không có quyền kích hoạt chức năng này!','error');}
					else if (req=="success") {
						showMess('Success!','success');
						setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},1000);
					}else showMess(req,'error');
				})
			}
		})

		$(".change_pass").click(function(){
			var user = $(this).attr('dataid');
			var url = "<?php echo ROOTHOST_ADMIN;?>ajaxs/member/change_pass.php";
			$('#myModalPopup .modal-body').html('Loading...');
			$('#myModalPopup .modal-title').html('Đổi mật khẩu');
			$.post(url,{'user':user},function(req){
				$('#myModalPopup .modal-body').html(req);
				$('#myModalPopup').modal('show');
			})
		})

		$('#cmd_save').click(function(){
			var data = $('#frm-action').serializeArray();
			var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/<?= COMS;?>/process_edit.php'; 
			$.post(url, data, function(req){
				if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
				if(req=='E03'){showMess('Không có quyền sửa nhóm này!','error');}
				else if (req=="success") {
					showMess('Success!','success');
					setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},2000);
				}else showMess(req,'error');
			});
		});
	})
</script>
