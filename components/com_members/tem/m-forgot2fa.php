<?php
if(!isLogin()){
?>
<div class='col-sm-4'></div>
<div class='col-sm-4'>
	<div class='main-panel'>
		<div class='header'>
			<div class='text-center'>
				<h2 class='title'>WELCOME BACK</h2>
			</div>
		</div>
		<div class='main'>
			<div class='frm'>
				<h3 class='title text-center'>Forgot your 2FA</h3>
				<div class="form-group">
					<label class="control-label">E-mail</label>
					<input type='text' class='form-control' name='txt_email' id='txt_email' placeholder='E-mail' value='' required/>
				</div>
				<div class='form-group text-danger err_mess'></div>
				<div class='form-group'>
					<button type='button' id='btn-process-forgot2fa' class='btn btn-block btn-primary'>RESET 2FA</button>
				</div>
				<div class='form-group text-center'>
					<div>No account yet? <a href='<?php echo ROOTHOST;?>register'>Create account</a></div>
					<div>Already have an account? <a href='<?php echo ROOTHOST;?>login'>Login in</a></div>
				</div>
				<script>
				$('#txt_email').focus();
				$('#btn-process-forgot2fa').click(function(){
					if($('#txt_email').val()==''){
						$('.err_mess').html('E-mail is required');
						return;
					}
					$('.err_mess').html('');
					var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_forgot2fa.php';
					var _data={
						'email':$('#txt_email').val()
					}
					$('.err_mess').html('Processing ...');
					$.post(_url,_data,function(req){
						console.log(req);
						if(req=="success") 
							$('.err_mess').html('2FA information has been sent to your mail. Please check your inbox');
						else $('.err_mess').html(req);
					});
				})
				</script>
			</div>
		</div>
	</div>
</div>
<div class='col-sm-4'></div>
<?php	
}else{
	header('location:'.ROOTHOST);
}
?>