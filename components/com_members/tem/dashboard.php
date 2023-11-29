<?php
if(isLogin()){
$username=getInfo('username');
$joindate=getInfo('cdate');
$check_2fa=(int)getInfo('is2fa')==1?"checked":"";
$check_active=(int)getInfo('isactive')==1?"Enable":"Disable";
$check_kyc=(int)getInfo('iskyc')==1?"yes":"no";

$kyc1_url=getInfo('kyc1')!='N/a' && getInfo('kyc1')!=''?ROOTHOST.getInfo('kyc1'):ROOTHOST."images/thumb_default.png";
$kyc2_url=getInfo('kyc2')!='N/a' && getInfo('kyc2')!=''?ROOTHOST.getInfo('kyc2'):ROOTHOST."images/thumb_default.png";
?>
    <div class="card">
    <div class="card-body">
        <div class='row'>
            <div class='col-sm-4'>
                <div class="col-box-left">
                <h3 class='text-center'><?php echo getInfo('fullname');?></h3>
                <div class='form-group'>
                    <i class="fa fa-user"></i> Username: <strong class='pull-right'><?php echo $username;?> </strong>
                </div>
                <div class='form-group'>
                    <i class="fa fa-key"></i> Password: <a href='<?php echo ROOTHOST;?>account/changepass' id='btn-changepass' class='pull-right'>Change password</a>
                </div>
                <div class='form-group'>
                    <i class="fa fa-user"></i> ParUser: <strong class='pull-right'><?php echo getInfo('par_user');?></strong>
                </div>
                <div class='form-group'>
                    <i class="fa fa-calendar"></i> Join date: <strong class='pull-right'><?php echo trim($joindate)!='N/a'?date('Y-m-d',$joindate):'';?></strong>
                </div>
                <div class='form-group'>
                    <i class="fa fa-tree"></i> Active:
                    <label class="switch text-right pull-right">
                        <?php echo $check_active;?>
                    </label>
                </div>
                <hr/>
                <div class='form-group'>
                    <i class="fa fa-cc-discover"></i> 2FA:
                    <label class="switch pull-right">
                        <input id="check_2fa" type="checkbox" <?php echo $check_2fa;?> value="0">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class='form-group 2fa_qrcode text-center' id='2fa_qrcode'>
                <?php
                if((int)getInfo('is2fa')==1){
                    $gsecret=getInfo('gsecret');
                    $ga = new PHPGangsta_GoogleAuthenticator();
                    echo '<img src="'.$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$username).'" />';
                    echo "<div>2FA Qr-Code</div>";
                } ?>
                </div>
                <script>
                $('#btn_create_pitwallet').click(function(){
                    var _url="<?php echo ROOTHOST;?>ajaxs/pit/CreateWallet2FA.php";
                    $.get(_url,function(req){
                        $('#myModal .modal-body').html(req);
                        $('#myModal').modal('show');
                    });
                });
                $('#check_2fa').click(function(){
                    var is2fa=$(this).is(':checked')==true?'yes':'no';
                    var _url="<?php echo ROOTHOST;?>ajaxs/mem/active_2fa.php";
                    var _data={
                        'is2fa':is2fa,
                        'username':'<?php echo $username;?>'
                    }
                    $.post(_url,_data,function(req){
                        console.log(req);
                        $('#2fa_qrcode').html(req);
                    });
                });
                </script>
            </div>
        </div>
        <div class='col-sm-8'>
        <div class='col-box-right'>
            <h4>My Profile</h4>
            <hr/>
            <div class='form-group'>
                <label>Full name</label>
                <input type='text' class='form-control' id='txt_fullname' value='<?php echo getInfo('fullname');?>'/>
            </div>
            <div class='form-group'>
                <label>Điện thoại</label>
                <input type='text' class='form-control' id='txt_phone' value='<?php echo getInfo('phone');?>'/>
            </div>
            <div class='form-group'>
                <label>Số CMT</label>
                <input type='text' class='form-control' id='txt_cmt' value='<?php echo getInfo('cmt');?>'/>
            </div>
            <div class='form-group'>
                <label>Ngày cấp</label>
                <input type='date' class='form-control' id='txt_cmt_date' value='<?php echo getInfo('cmt_date');?>'/>
            </div>
            <div class='form-group'>
                <label>Nơi cấp</label>
                <input type='text' class='form-control' id='txt_cmt_place' value='<?php echo getInfo('cmt_place');?>'/>
            </div>
            <div class='form-group text-right'>
                <button class='btn btn-primary' id='btn_update_info' >Cập nhật thông tin ></button>
            </div>
        </div>
        </div>
        </div>
</div>
</div>
<script>
$('#btn_update_info').click(function(){
	var _url="<?php echo ROOTHOST;?>ajaxs/mem/process_update_member.php";
	var _data={
		'fullname':$('#txt_fullname').val(),
		'phone':$('#txt_phone').val(),
		'cmt':$('#txt_cmt').val(),
		'cmt_date':$('#txt_cmt_date').val(),
		'cmt_place':$('#txt_cmt_place').val()
	}
	$.post(_url,_data,function(req){
		if(req=='success') window.location.reload();
		console.log(req);
	});
});
$('.kyc_img img').dblclick(function(){
	_id=$(this).attr('data');
	$('#'+_id).click();
})
$('#txt_kyc1,#txt_kyc2').change(function(e){
	if($(this).val=='') return;
	var file_data = $(this).prop("files")[0];	
	var type = file_data.type;
	var match = ["image/gif","image/png","image/jpg","image/jpeg",];
	if (type == match[0] || type == match[1] || type == match[2] || type == match[3]) {
		$(this).parent().submit();
	} else {
		alert('File không đúng định dạng!');
		$('#file').val('');
	}
	return false;
});
$('.form_upload').on('submit',function(e){
	var thisForm=$(this);
	e.preventDefault();
	$.ajax({
		url: "<?php echo ROOTHOST;?>ajaxs/mem/upload_image_kyc.php",
		type: "POST",
		data:  new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		success: function(req){
			var url_thumb="<?php echo ROOTHOST;?>"+req;
			console.log(url_thumb);
			thisForm.parent().find('img').attr('src',url_thumb);
		},
		error: function(req){
			console.log(req);
		} 	        
   });
});
</script>
<?php
}else{
	header('location:'.ROOTHOST.'login');
}
?>