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
$obj=SysGetList('tbl_member_packet',array()," AND username='$user'");
$r=$obj[0];
$thisPacket=$r['packet'];
global $_PACKET;
?>
<h3 class='text-center'>UPGRADE PACKET</h3><hr/>
<div class='cred text-center' id='err_mess'></div>
<div class="form-group">
	<label>Select packet</label>
	<select class='form-control' id='cbo_packet'>
		<?php 
		foreach($_PACKET as $item){
		if($item['price']<=$thisPacket) continue;	
		?>
		<option value='<?php echo $item['price'];?>'><?php echo $item['name'];?> (<?php echo $item['price'];?> PIT)</option>
		<?php }?>
	</select>
</div>
<div class="form-group">
	<label>2FA Code</label>
	<input type='text' id='txt_2fa' value='' class='form-control text-center' placeholder='Google authenticator code'/>
</div>
<div class="form-group text-center">
	<div><button type='button' class='btn btn-primary' id='cmd_processUpdatePacket'>Upgrade >></button></div>
</div>
<script>
$('#cmd_processUpdatePacket').click(function(){
	var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_updatePacket.php';
	var _data={
		'packet':$('#cbo_packet').val(),
		'code_2fa':$('#txt_2fa').val()
	}
	if($('#txt_2fa').val().length==6){
		$('#cmd_processUpdatePacket').hide();
		$.post(_url,_data,function(req){
			if(req=='success') window.location.reload();
			else{
				$('#err_mess').html(req);
				$('#cmd_processUpdatePacket').show();
			}
		});
	}else{
		$('#err_mess').html('2Fa code is 6 characters');
	}
});

</script>
<?php }else{
	die('<p class="text-center">Please login to continue!</p>');
}