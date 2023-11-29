<script language="javascript">
	function checkinput() {
		var  email = document.getElementById('email_contact');
		var  title = document.getElementById('web_title');

		if(title.value=='') {
			alert('Vui lòng nhập đầy đủ thông tin cấu hình. Các thông tin sẽ này ảnh hưởng đến việc hiển thị trên website');
			title.focus();
			return false;
		}
		return true;
	}
</script>
<?php
define('COMS','config');

$check_permission = $UserLogin->Permission(COMS);
if($check_permission==false) die($GLOBALS['MSG_PERMIS']);

$title =''; $desc=''; $key='';$email_contact=''; $nickyahoo=''; $nameyahoo='';
$footer=''; $contact=''; $banner=''; $gallery=''; $logo='';
include_once('libs/cls.configsite.php');
$obj = new CLS_CONFIG();
if(isset($_POST['web_title']) && $_POST['web_title']!='') {

	if($_POST['company_name']!='')  $obj->CompanyName = addslashes($_POST['company_name']);
	if($_POST['web_title']!='')     $obj->Title = addslashes($_POST['web_title']);
	if($_POST['web_desc']!='')      $obj->Meta_descript = addslashes($_POST['web_desc']);
	if($_POST['web_keywords']!='')  $obj->Meta_keyword = addslashes($_POST['web_keywords']);
	if($_POST['email_contact']!='') $obj->Email = addslashes($_POST['email_contact']);
	if($_POST['address']!='')       $obj->Address = addslashes($_POST['address']);
	if($_POST['txtphone']!='')      $obj->Phone = addslashes($_POST['txtphone']);
	if($_POST['txttel']!='')        $obj->Tel = addslashes($_POST['txttel']);
	if($_POST['txtfax']!='')        $obj->Fax = addslashes($_POST['txtfax']);
	//if($_POST['txttwitter']!='')    $obj->Twitter = addslashes($_POST['txttwitter']);
	if($_POST['txtgplus']!='')      $obj->Gplus = addslashes($_POST['txtgplus']);
	if($_POST['txtfacebook']!='')   $obj->Facebook = addslashes($_POST['txtfacebook']);
	if($_POST['txtyoutube']!='')    $obj->Youtube = addslashes($_POST['txtyoutube']);

	$obj->Update2();
}    
$obj->getList();
if($obj->Num_rows()<=0) {
	echo 'Dữ liệu trống.';
}
else{
	$row = $obj->Fetch_Assoc();
	$title          = stripslashes($row['title']);
	$company_name   = stripslashes($row['company_name']);
	$desc           = stripslashes($row['meta_descript']);
	$key            = stripslashes($row['meta_keyword']);
	$email_contact  = stripslashes($row['email']);
	$address        = stripslashes($row['address']);
	$phone          = stripslashes($row['phone']);
	$tel            = stripslashes($row['tel']);
	$fax            = stripslashes($row['fax']);
	$facebook       = stripslashes($row['facebook']);
	$youtube        = stripslashes($row['youtube']);
	$gplus          = stripslashes($row['gplus']);
	$twitter        = stripslashes($row['twitter']);
}
unset($obj);
?>
<div class="com_header color">
	<i class="fa fa-cog" aria-hidden="true"></i> THÔNG TIN CẤU HÌNH WEBSITE
	<div class="pull-right">
		<form id="frm_menu" name="frm_menu" method="post" action="">
			<input type="hidden" name="txtorders" id="txtorders" />
			<input type="hidden" name="txtids" id="txtids" />
			<input type="hidden" name="txtaction" id="txtaction" />

			<ul class="list-inline">
				<li><a class="save btn btn-default" href="#" onclick="dosubmitAction('frm_action','save');" title="Lưu"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</a></li>

				<li><a class="btn btn-default"  href="<?php echo ROOTHOST_ADMIN;?>" title="Đóng"><i class="fa fa-sign-out" aria-hidden="true"></i> Đóng</a></li>
			</ul>
		</form>
	</div>
</div><br>
<div id='action' class="col-md-12">
	<form name="frm_action" id="frm_action" action="" method="post">
		<div><b>THÔNG TIN TRANG</b></div><hr size="1" style="margin:10px 0 20px;">
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Tên website<font color="red"><font color="red">*</font></font></label>
			<div class="col-md-9">
				<input type="text" name="web_title" class="form-control" id="web_title" value="<?php echo $title;?>" placeholder="">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Tên công ty</label>
			<div class="col-md-9">
				<input type="text" name="company_name" class="form-control" value="<?php echo $company_name;?>" placeholder="">
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Mô tả website<font color="red"><font color="red">*</font></font></label>
			<div class="col-md-9">
				<input type="text" name="web_desc" class="form-control" id="web_desc" value="<?php echo $desc;?>" placeholder="">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Từ khóa<font color="red">*</font></label>
			<div class="col-md-9">
				<input type="text" name="web_keywords" class="form-control" id="web_keywords" value="<?php echo $key;?>" placeholder="">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div><b>THÔNG TIN LIÊN HỆ</b></div><hr size="1" style="margin:10px 0 20px;">
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Email liên hệ<font color="red">*</font></label>
			<div class="col-md-9">
				<input type="text" name="email_contact" class="form-control" id="email_contact" value="<?php echo $email_contact;?>" placeholder="">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Số điện thoại</label>
			<div class="col-md-9">
				<input type="text" name="txtphone" class="form-control" id="txtphone" value="<?php echo $phone;?>" placeholder="">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Di động</label>
			<div class="col-md-9">
				<input type="text" name="txttel" class="form-control" id="txttel" value="<?php echo $tel;?>" placeholder="Di động">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Fax</label>
			<div class="col-md-9">
				<input type="text" name="txtfax" class="form-control" id="txtfax" value="<?php echo $fax;?>" placeholder="Fax">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Địa chỉ</label>
			<div class="col-md-9">
				<input type="text" name="address" class="form-control" id="address" value="<?php echo $address;?>" placeholder="Địa chỉ">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div><b>MẠNG XÃ HỘI</b></div><hr size="1" style="margin:10px 0 20px;">
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Facebook</label>
			<div class="col-md-9">
				<input type="text" name="txtfacebook" class="form-control" id="txtfacebook" value="<?php echo $facebook;?>" placeholder="Link Facebook của bạn">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Zalo</label>
			<div class="col-md-9">
				<input type="text" name="txtgplus" class="form-control" id="txtgplus" value="<?php echo $gplus;?>"placeholder="Link G+ của bạn">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2 control-label">Youtube</label>
			<div class="col-md-9">
				<input type="text" name="txtyoutube" class="form-control" id="txtyoutube" value="<?php echo $youtube;?>" placeholder="Link Youtube của bạn">
				<div id="txt_name_err" class="mes-error"></div>
			</div>
			<div class="clearfix"></div>
		</div>
		<input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;" />
	</form>
</div>