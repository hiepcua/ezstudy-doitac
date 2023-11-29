<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$err=$username=$password='';

if(isset($_POST['txtuser'])){
	$user=addslashes($_POST['txtuser']);
	$pass=addslashes($_POST['txtpass']);
	/*$serc=addslashes($_POST['txt_sercurity']); 
	if($_SESSION['SERCURITY_CODE']!=$serc)
		$err='<font color="red">Mã bảo mật không chính xác</font>';
	else{*/
		global $UserLogin;
		if($UserLogin->LOGIN($user,$pass)==true)
			echo '<script language="javascript">window.location.href="index.php"</script>';
		else
			$err='<font color="red">Đăng nhập không thành công.</font>';
	//}
}
?>
<div class='col-md-4'></div>
<div class='col-md-4'>
	<form id="frmlogin" name="frmlogin" method="post" action="" AUTOCOMPLETE="off" >
		<h2 class="title_login"><span class='glyphicon glyphicon-user'></span> LOGIN</h2>
		<div class="body">
			<div class="form-group row "></div>
			<div class="form-group  row text-center" style="color:red"><b><?php echo $err;?></b></div>
			<div class="form-group row">
				<div class="col-md-4 col-sm-6">
					<label>Tên đăng nhập</label>
					<label>Mật khẩu</label>
					<?php /*<label>Mã bảo mật</label>*/?>
				</div>
				<div class="col-md-8 col-sm-6">
					<input type='text' name='txtuser' id='txtuser' class='form-control' placeholder='Tên đăng nhập' value='<?php echo $username;?>' required/>
					<input type='password' name='txtpass' id='txtpass' class='form-control' placeholder='Mật khẩu' value='<?php echo $password;?>' required/>
					<?php /*
					<input type="text" size="7" name="txt_sercurity" id="txt_sercurity" class='form-control' placeholder='Mã bảo mật' required/>
					<img src="extensions/captcha/CaptchaSecurityImages.php?width=80&height=30" align="left" alt="" />
					*/ ?>
				</div>
			</div>
			<div class="form-group clearfix">
				<input type='submit' name='cmd_login' id='cmd_login' class='btn btn-primary form-control' value='Đăng nhập'/>
			</div>
		</div>
	</form>
</div>
<div class='col-md-4'>
</div>