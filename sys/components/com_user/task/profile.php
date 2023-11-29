<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$msg='';
$check_permission = $UserLogin->Permission('user');
$id=$obj->getInfo('id');
if(isset($_GET['memid'])) $id=(int)$_GET['memid'];

if(isset($_POST['cmdsave']))
{	$obj->ID=$id;
	$obj->UserName=addslashes($_POST['txtusername']);
	$obj->FirstName=addslashes($_POST['txtfirstname']);
	$obj->LastName=addslashes($_POST['txtlastname']);
	
	$txtjoindate = addslashes($_POST['txtbirthday']);
	//$joindate = mktime(0,0,0,substr($txtjoindate,3,2),substr($txtjoindate,0,2),substr($txtjoindate,6,4));
	$obj->Birthday=date('Y-m-d',strtotime($txtjoindate));
		
	$obj->Gender=addslashes($_POST['optgender']);
	$obj->Address=addslashes($_POST['txtaddress']);
	$obj->Mobile=addslashes($_POST['txtmobile']);
	$obj->Email=addslashes($_POST['txtemail']);
	$obj->Gid=(int)$_POST['cbo_gmember'];
	if($obj->Update()) $msg="Cập nhật thành công";
	else $msg="Cập nhật lỗi!";
}
	
$obj->getList(' AND id='.$id);
$row=$obj->Fetch_Assoc();

?>
<script language='javascript'>
function checkinput(){
	 return true;
}
</script>

	<div class="com_header color">
	<h3 class="title">
		<i class="fa fa-pencil-square" aria-hidden="true"></i>  Cập nhật thành viên quản trị
	</h3>
	</div>
	<div class="col-md-12">
	<form id="frm_action" name="frm_action" method="post" action="">
		<div class="row form-group">
			<label class="col-md-2"></label>
			<div class="col-md-8"><span class='msg'><?php echo $msg;?></span></div>
		</div>
		<div class="row form-group">
			<label class="col-md-2 control-label">Tên đăng nhập <span class="star">*</span></label>
			<div class="col-md-3">
				<input class="form-control" id="txtusername" name="txtusername" value="<?php echo $row['username'];?>" type="text" required readonly>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-2 control-label">Họ & đệm <span class="star">*</span></label>
			<div class="col-md-3">
				<input class="form-control" id="txtfirstname" name="txtfirstname" value="<?php echo $row['firstname'];?>" type="text" required>
			</div>
			<label class="col-md-2 control-label text-right">Tên <span class="star">*</span></label>
			<div class="col-md-3">
				<input class="form-control" id="txtlastname" name="txtlastname" value="<?php echo $row['lastname'];?>" type="text" required>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-2 control-label">Ngày sinh</label>
			<div class="col-md-3">
				<input class="form-control" name="txtbirthday" type="date" id="txtbirthday" value="<?php echo $row['birthday'];?>"/>
			</div>
			<label class="col-md-2 control-label text-right">Giới tính</label>
			<div class="col-md-3">
				<input name="optgender" type="radio" value="0" <?php if($row['gender']==0) echo'checked';?>/> Nam
				<input name="optgender" type="radio" value="1" <?php if($row['gender']==1) echo'checked';?>/> Nữ
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row form-group">
			<label class="col-md-2 control-label">Địa chỉ</label>
			<div class="col-md-3">
				<input class="form-control" id="txtaddress" name="txtaddress" value="<?php echo $row['address'];?>" type="text">
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row form-group">
			<label class="col-md-2 control-label">Điện thoại</label>
			<div class="col-md-3">
				<input class="form-control" name="txtmobile" type="tel" id="txtmobile" value="<?php echo $row['mobile'];?>"/>
			</div>
			<label class="col-md-2 control-label text-right">Email</label>
			<div class="col-md-3">
				<input class="form-control" name="txtemail" type="email" id="txtemail" value="<?php echo $row['email'];?>"/>
			</div>
			<div class="col-md-2"></div>
		</div>
		<?php if($check_permission==true) {?>
		<div class="row form-group">
			<label class="col-md-2 control-label">Nhóm quản trị <span class="star">*</span></label>
			<div class="col-md-3">
				<select name="cbo_gmember" id="cbo_gmember" class="form-control" required>
					<option value="0" style="font-weight:bold; background-color:#cccccc">Chọn nhóm quyền</option>
					<?php			
					if(!isset($obju)) $obju = new CLS_GUSER();
					$obju->getList(" AND id=".$row['gid']); 
					$r=$obju->Fetch_Assoc();
					echo "<option value='".$r['id']."'>".$r['name']."</option>";
					unset($obju);
					?>
				</select>
				<script language="javascript">
				  cbo_Selected('cbo_gmember',<?php echo $row['gid'];?>);
				</script>
			</div>
			<div class="col-md-2"></div>
		</div>
		<?php } else { ?>
		<input type="hidden" name="cbo_gmember" id="cbo_gmember" value="<?php echo $row['gid'];?>"/>
		<?php } ?>
		<div class="row form-group">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<input type="submit" name="cmdsave" id="cmdsave" value="Lưu lại" class="btn btn-primary">
			</div>
			<div class="col-md-2"></div>
		</div>
	</form>
	</div>
<div class="clearfix"></div>
<?php unset($obj); ?>