<?php
session_start();
define("ISHOME",true);
require_once("global/libs/gfinit.php");
require_once("global/libs/gfconfig.php");
require_once("global/libs/gffunc.php");
require_once("global/libs/gffunc_wallet.php");
require_once("includes/gfconfig.php");
require_once("libs/cls.mysql.php");
require_once("libs/cls.guser.php");
require_once("libs/cls.user.php");
require_once("libs/cls.member.php");
require_once('libs/cls.card.php');
require_once("libs/GoogleAuthenticator.php");
$UserLogin = new CLS_USER();
global $UserLogin;
$class=$UserLogin->isLogin()==true ? 'hold-transition skin-blue sidebar-mini':'';
?>
<!DOCTYPE html>
<html language='vi'>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>
    <link rel="stylesheet" href="<?php echo ROOTHOST_ADMIN; ?>global/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo ROOTHOST;?>global/css/bootstrap.min.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo ROOTHOST;?>global/css/font-awesome.min.css" type="text/css" media="all" >
	<link rel="stylesheet" href="<?php echo ROOTHOST_ADMIN;?>global/css/style.min.css?v=2" type="text/css" />
	<link rel="stylesheet" href="<?php echo ROOTHOST_ADMIN;?>global/css/style.responsive.min.css?v=1" type="text/css" />
    <link rel="stylesheet" href="<?php echo ROOTHOST_ADMIN; ?>global/css/select.css?v=3">
    <link rel="stylesheet" href="<?php echo ROOTHOST_ADMIN; ?>global/css/style.css">
	<script src="<?php echo ROOTHOST;?>global/js/jquery-1.11.2.min.js"></script>
	<script src="<?php echo ROOTHOST;?>global/js/bootstrap.min.js"></script>
	<script src="<?php echo ROOTHOST_ADMIN;?>js/script.min.js"></script>
    <script src="<?php echo ROOTHOST;?>js/func.js"></script>
	<script src="<?php echo ROOTHOST_ADMIN; ?>global/dist/js/app.min.js"></script>
    <script src="<?php echo ROOTHOST;?>global/js/select2.min.js"></script>
</head>
<body class="<?php echo $class;?>">
<div class="wrapper">
	<div id="notification" style="display: none;"></div>
	<?php
	if(!$UserLogin->isLogin()){

		if(isset($_GET['com'])) echo "<script>window.location='".ROOTHOST_ADMIN."'</script>";
		include_once(COM_PATH."com_user/task/login.php");
	}else{
		require_once(MOD_PATH."mod_mainmenu/layout.php");
        require_once(MOD_PATH."mod_leftmenu/layout.php");
        $com=isset($_GET['com'])?$_GET['com']:'frontpage';
        ?>
        <div class="content-wrapper <?php if($com=='frontpage') echo 'bg-body';?>">
            <section class="content">
                <?php

                if(!is_file(COM_PATH.'com_'.$com.'/layout.php')){$com='frontpage';}
                include_once(COM_PATH.'com_'.$com.'/layout.php');
                ?>
            </section>
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.3.8
            </div>
            <strong>Copyright &copy; 2021 Study All rightsreserved.</strong>
        </footer>
    </div>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title"></h3>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<?php
	} ?>
</body>
</html>

<script type="text/javascript">
  $(".change_pass").click(function(){
        var user = $(this).attr('dataid');
        var url = "<?php echo ROOTHOST_ADMIN?>ajaxs/member/change_pass.php";
        $('#myModal .modal-body').html('Loading...');
        $.post(url,{'user':user},function(req){
            $('#myModal .modal-title').html('Đổi mật khẩu');
            $('#myModal .modal-body').html(req);
            $('#myModal').modal('show');
        })
    })
$('.reset_form').click(function(){
	$('#fromdate').val('');
	$('#todate').val('');
	$('#sl_username').val('');
})
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
	$(document).ready(function(){
		$('#main-sidebar').click(function(){
			$('#wapper_body').toggleClass('slidebar-open');
		})
	});
    function callActionMember(_this,act){ //1 - chi tiết, 2 - sửa, 3 -đổi MK, 4 - Xóa
        var item_id=$(_this).attr('data-id');
        if(act==2) url='<?php echo ROOTHOST;?>ajaxs/member/edit.php';
        else if(act==3) url='<?php echo ROOTHOST;?>ajaxs/media/change_pass.php';
        else if(act==4) url='<?php echo ROOTHOST_ADMIN;?>ajaxs/media/delete.php';
        else var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/member/detail.php';
        if(act==4){
            if(confirm('Bạn có chắc muốn gỡ bản ghi này')){
                $.post(url,{item_id}, function(response_data){
                    //console.log(response_data);
                    if(response_data==-1) alert(' Không xóa được, Video đang nằm trong Channel!');
                    else   window.location.reload();
                });
            }
        }else{
            $.post(url,{item_id}, function(response_data){
                $('#myModal').modal('show');
                $('#content-modal').html(response_data);
            });
        }
    }
</script>