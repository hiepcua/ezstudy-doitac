<?php
session_start();
define('incl_path','global/libs/');
define('libs_path','libs/');
define('com_path','components/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(incl_path.'gffunc_member.php');
require_once(incl_path.'gffunc_packet.php');
require_once(incl_path.'gffunc_member.php');
//-----------------------------------------
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'cls.template.php');
require_once(libs_path.'GoogleAuthenticator.php');
//-----------------------------------------


header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
$req=isset($_GET['req'])?antiData($_GET['req']):'';
$req=str_replace(' ','%2B',$req);
if($req!='') setcookie('RES_USER',$req,time() + (86400 * 30), "/");
define('ISHOME',true);
$tmp=new CLS_TEMPLATE();
$_COM=isset($_GET['com'])?addslashes(strip_tags($_GET['com'])):'';
$_VIEW=isset($_GET['viewtype'])?addslashes(strip_tags($_GET['viewtype'])):'';
?>
<!DOCTYPE html>
<html lang='vi'>
<head profile="http://www.w3.org/2005/10/profile">
	<meta charset="utf-8">
	<meta name="google" content="notranslate" />
	<meta http-equiv="Content-Language" content="vi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="referrer" content="no-referrer" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php echo SITE_NAME;?></title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic">
	<link rel="shortcut icon" href="#" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST; ?>global/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST; ?>global/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST; ?>global/css/style.css?v=6">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST; ?>global/css/style-responsive.css?v=6">
	<link rel="stylesheet" href="<?php echo ROOTHOST; ?>global/css/select.css?v=1">
	<script src='<?php echo ROOTHOST;?>global/js/jquery-1.11.2.min.js'></script>
	<script src="<?php echo ROOTHOST;?>global/js/bootstrap.min.js"></script>
	<script src="<?php echo ROOTHOST;?>js/func.js"></script>
	<script src="<?php echo ROOTHOST;?>global/js/select2.min.js"></script>
	<script src="<?php echo ROOTHOST;?>js/script.min.js"></script>
</head>
<body >
	<?php if(isLogin()){
		$username=getInfo('username');
		?>
		<div id='site_header'><?php require_once('modules/site-header.php');?><div class="clearfix"></div></div>
		<div class="body <?php if($_COM=='') echo 'page-frontpage';?>">
			<div id="notification" style="display: none;"></div>
			<div class="container-fluid">
				<div class="row">
					<?php require_once('modules/left-sitebar.php');?>
					<div class="col-md-9">
						<div class="body">
							<?php
							$viewtype=isset($_GET['viewtype'])? addslashes($_GET['viewtype']):'';
							if(!is_dir(COM_PATH.'com_'.$_COM)) $_COM='frontpage';
							include(COM_PATH.'com_'.$_COM.'/layout.php');
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="loading"></div>
		</div>
	<?php }
	else {
		include_once(com_path.'com_members/tem/m-login.php');
	}
	?>

	<div class="modal fade" id='myModal' role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body" id="">
					<p>One fine body&hellip;</p>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
<script>
	$(".number_format").keyup(function(){
		var selection = window.getSelection().toString();
		if ( selection !== '' ) {
			return;
		}
		if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
			return;
		}
		var $this = $( this );
		var input = $this.val();
		if(input==0) return 0;
		var input = input.replace(/[\D\s\._\-]+/g, "");
		input = input ? parseInt( input, 10 ) : 0;
		$this.val( function() {
			return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
		} );
	})
	function productStyles(selection) {
		if (!selection.id) { return selection.text; }
		var thumb = $(selection.element).data('thumb');
		var info = $(selection.element).data('info');
		if(!thumb){
			return selection.text;
		} else {
			var $selection = $(
				'<img src="<?php echo ROOTHOST;?>images/' + thumb + '" alt="" class="img-select"><div class="box-info-select2"> <span class="img-changer-text">' + $(selection.element).text() + '</span><span class="img-info-text">' + info + '</span></div>'
				);
			return $selection;
		}
	}
	$('.select').select2({
		templateResult: productStyles,
		allowClear: true
	});
	function callActionAccount(_this,act){
		var item_id=$(_this).attr('data-id');
		var activate='';
		if(act==1){
			var url='<?php echo ROOTHOST;?>ajaxs/account/register.php';
			var title='Đăng ký dịch vụ';
			var activate=$(_this).attr('data-activate');
		}
		else if(act==2){
			var url='<?php echo ROOTHOST;?>ajaxs/account/extend.php';
			var title='Gia hạn tài khoản';
			var activate=$(_this).attr('data-activate');
		}
		else if(act==3) {
			url='<?php echo ROOTHOST;?>ajaxs/account/delete.php';
			title='Xóa tài khoản';
		}
		else if(act==4){
			url='<?php echo ROOTHOST;?>ajaxs/account/change_pass.php';
			title='Đổi mật khẩu';
		}
		else{
			url='<?php echo ROOTHOST;?>ajaxs/account/detail.php';
			title='Thông tin chi tiết';
		}
		if(act==3){
			var item=$('.item-channel-'+item_id);
			if(confirm('Bạn có chắc xóa bản ghi này')){
				$.post(url,{item_id}, function(response_data){
					item.remove();
				});
			}
		}else{
			$.post(url,{item_id,activate}, function(response_data){
				//console.log(response_data);
				$('#myModal').modal('show');
				$('#myModal .modal-body').html(response_data);
				$('#myModal .modal-title').html(title);
			});
		}

	}
	$('document').ready(function(){

		$('.logout').click(function(){
			$.get('<?php echo ROOTHOST;?>ajaxs/mem/logout.php',function(req){
				console.log(req);
				window.location.reload();
			});
		});
	});
</script>
</html>