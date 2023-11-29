<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_media.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
?>
<h3 class='text-center'>CONFIRM 2FA</h3><hr/>
<div class="form-group">
	<div class='err_mess cred text-center'></div>
	<input type='text' class='form-control form-control-lg text-center' name='txt_2fa' id='txt_2fa' placeholder='Google authenticator code' value='' required/>
</div>
<div class='form-group'>
	<button type='button' id='btn-confirm-2fa' class='btn btn-block btn-lg btn-primary'>CONFIRM 2FA</button>
</div>
<script>
$("#txt_2fa").focus();
$("#txt_2fa").on('keyup', function (e) {
    if (e.keyCode === 13) {
        event.preventDefault();
		Confirm2FA();
        return false;
    }
});
$('#btn-confirm-2fa').click(function(){
	Confirm2FA();
	return false;
});
function Confirm2FA(){
	if($('#txt_2fa').val().length!=6){
		$('.err_mess').html('2FA code must have 6 characters');
		return;
	}
	var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_2fa.php';
	var _data={
		'2fa_code':$('#txt_2fa').val()
	}
	$.post(_url,_data,function(req){
		if(req=='success') window.location.reload();
		else $('.err_mess').html(req);
	})
}
</script>