<?php
if(!isLogin()){
$userCookie=isset($_COOKIE['LOGIN_USER'])?json_decode(decrypt($_COOKIE['LOGIN_USER']),true):array();
$user=isset($userCookie['username'])?$userCookie['username']:'';
$pass=isset($userCookie['password'])?$userCookie['password']:'';
$ischeck=isset($userCookie['ischeck']) && $userCookie['ischeck']=='yes'?'checked=true':'';
?>
<div class='col-sm-4'></div>
<div class='col-sm-4'>
    <div class='main-panel'>
        <div class='main'>
            <div class='frm'>
                <h3 class='title text-center'>AMS</h3>
                <div class='err_mess cred text-center'></div>
                <div class='form'>
                    <div class="form-group">
                        <label class="control-label">Tài khoản</label>
                        <input type='text' class='form-control' name='txt_user' id='txt_user' placeholder='Tài khoản' value='<?php echo $user;?>' required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mật khẩu</label>
                        <input type='password' class='form-control' name='txt_pas' id='txt_pas' placeholder='Mật khẩu' value='<?php echo $pass;?>' required/>
                    </div>
                    <div class="form-group">
                        <label class="custom-control-label"><input type="checkbox" class="custom-control-input" id="isConfirm" <?php echo $ischeck;?>> Nhớ tài khoản của tôi</a>.</label>
                    </div>
                    <div class='form-group'>
                        <button type='button' id='btn-process-login' class='btn btn-block btn-primary'>ĐĂNG NHẬP</button>
                    </div>
                    <div class='form-group text-center'>
                        <a href='<?php echo ROOTHOST;?>forgot-password'>Quên mật khẩu?</a>
                    </div>
                    <script>
                        $('#txt_user').focus();
                        $('#btn-process-login').click(function(){
                            login();
                        })
                        $('#txt_user,#txt_pas').keyup(function(e){
                            var code = (e.keyCode ? e.keyCode : e.which);
                            if (code==13) {
                                e.preventDefault();
                                login();
                            }
                        })
                        function login(){
                            if($('#txt_user').val()=='' || $('#txt_pas').val()=='' ){
                                $('.err_mess').html('Tên người dùng và mật khẩu là bắt buộc');
                                return;
                            }
                            $('.err_mess').html('');
                            var _ischeck=$('#isConfirm').is(':checked')?'yes':'no';
                            var _url='<?php echo ROOTHOST;?>ajaxs/mem/process_login.php';
                            var _data={
                                'username':$('#txt_user').val(),
                                'password':$('#txt_pas').val(),
                                'ischeck':_ischeck
                            }
                            $.post(_url,_data,function(req){
                                 console.log(req);
                                if(req=='success'){
                                    window.location.href='<?php echo ROOTHOST;?>';
                                }else $('.err_mess').html(req);
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='col-sm-4'></div>
<?php
}?>