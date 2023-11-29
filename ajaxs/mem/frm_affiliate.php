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
$link1=getInfo('link1');
$link2=getInfo('link2');
$parLink1='';
$parLink2='';
$par_user=getInfo('par_user');
if($par_user!='root' && $par_user!=''){
	$obj=SysGetList('tbl_member',array('link1','link2')," AND username='$par_user'");
	$parLink1=$obj[0]['link1'];
	$parLink2=$obj[0]['link2'];
}
if($parLink1=='') $parLink1.='__';
if($parLink2=='') $parLink2.='__';
?>
<h3 class='text-center'>AFFILIATE PARTNER</h3>
<div class='cred text-center' id='err_mess'></div>
<table class='table'>
	<tr>
		<td width='100' class='text-center'>PARTNER</td>
		<td>
			<div class="form-group row">
				<label class='col-xs-3'>Invite IB <span class='pull-right'>:</span></label>
				<div class='col-xs-9'><?php echo $parLink1;?></div>
			</div>
			<div class="form-group row">
				<label class='col-xs-3'>Invite Client <span class='pull-right'>:</span></label>
				<div class='col-xs-9'><?php echo $parLink2;?></div>
			</div>
		</td>
	</tr>
	<tr>
		<td class='text-center'>AFFILIATE<br/>PARTNER</td>
		<td>
			<div class="form-group row">
				<label class='col-xs-3'>Invite IB <span class='pull-right'>:</span></label>
				<div class='col-xs-9'><input type='text' id='txt_invite1' value='<?php echo $link1;?>' class='form-control' placeholder='...'/></div>
			</div>
			<div class="form-group row">
				<label class='col-xs-3'>Invite Client <span class='pull-right'>:</span></label>
				<div class='col-xs-9'><input type='text' id='txt_invite2' value='<?php echo $link2;?>' class='form-control' placeholder='...'/></div>
			</div>
		</td>
	</tr>
</table>
			<div class='col-sm-3'></div>
			<div class='col-sm-6 text-center'>
				<div class="form-group">
					<input type='text' id='txt_2fa' value='' class='form-control text-center' placeholder='Google authenticator code'/>
				</div>
				<div class="form-group"><button type='button' class='btn btn-primary' id='cmd_updateAffiliate'>Update >></button></div>
			</div>
			<div class='col-sm-3'></div>
			<div class='clearfix'></div>
<script>
$('#cmd_updateAffiliate').click(function(){
	var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_updateAffiliate.php';
	var _link1=$('#txt_invite1').val();
	var _link2=$('#txt_invite2').val();
	if(_link1=='' || _link2==''){ $('#err_mess').html('Invite link IB or Invite link client is empty!'); return;}
	if($('#txt_2fa').val().length!=6){$('#err_mess').html('2Fa code is 6 characters');return;}
	$('#cmd_updateAffiliate').hide();
	$.post(_url,{'link1':_link1,'link2':_link2,'code_2fa':$('#txt_2fa').val()},function(req){
		if(req=='success') window.location.reload();
		else{ 
			$('#err_mess').html(req);
			$('#cmd_updateAffiliate').show();
		}
	});
});
</script>
<?php }else{
	die('<p class="text-center">Please login to continue!</p>');
}