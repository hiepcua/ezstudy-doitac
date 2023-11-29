<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
$user=getInfo('username');
?>
<h3 class='text-center'>CASH BACK</h3><hr/>
<div class='cred text-center' id='err_mess'></div>
<div class="form-group">
	<label>Number Card</label>
	<input type='text' id='txt_card' value='' class='form-control text-center'/>
</div>
<div class="form-group">
	<label>2FA Code</label>
	<input type='text' id='txt_2fa' value='' class='form-control text-center' placeholder='Google authenticator code'/>
</div>
<div class="form-group text-center">
	<div><button type='button' class='btn btn-primary' id='cmd_processInputCard'>Process >></button></div>
</div>
<script>
$('#cmd_processInputCard').click(function(){
	$('#myModal').modal('hide');
	var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_salesInput.php';
	var _card=$('#txt_card').val();
	if(_card=='' || _card.length!=12){ $('#err_mess').html('The card number is not in the correct format'); return;}
	if($('#txt_2fa').val().length!=6){$('#err_mess').html('2Fa code is 6 characters');return;}
	
	$('#cmd_processInputCard').hide();
	$.post(_url,{'card':_card,'code_2fa':$('#txt_2fa').val()},function(req){
		if(req=='success') window.location.reload();
		else{ 
			$('#err_mess').html(req);
			$('#cmd_processInputCard').show();
		}
	});
});
</script>
<?php }else{
	die('<p class="text-center">Please login to continue!</p>');
}