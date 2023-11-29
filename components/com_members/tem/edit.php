<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
if(isLogin()){
	$this_user=getInfo('username');
    if(isset($_GET['user'])) $id=addslashes($_GET['user']);
    $obj=SysGetList('tbl_member',array()," AND username='$id' AND par_user='$this_user'");
    $row=isset($obj[0])? $obj[0]: array();
    if(count($row)<1) die('Không tìm thấy User này trong danh sách Sale của bạn');
    $path_curent=isset($row['path'])? $row['path']:'';
    $er_mess='';
    
    if(isset($_POST['cmdsave'])){
        // if(!isset($_SESSION['SERCURITY_CODE']) || $_SESSION['SERCURITY_CODE']!=$_POST['txt_code']) $er_mess .= 'Mã bảo mật không chính xác<br/>';
        if($er_mess==''){
            $arr  = array();
            $arr['par_user']=$this_user;
            $arr['fullname']=addslashes($_POST['txtfullname']);
            $arr['birthday']=date('Y-m-d',strtotime($_POST['txtbirthday']));
            $arr['address']=addslashes($_POST['txtaddress']);
            $arr['phone']=addslashes($_POST['txtphone']);
            $arr['email']=addslashes($_POST['txtemail']);
            $arr['isroot']=(int)$_POST['opt_isroot'];
            $arr['cmt']=addslashes(strip_tags($_POST['txt_cmt']));
            $arr['cmt_date']=addslashes($_POST['txt_cmt_date']);
            $arr['cmt_place']=addslashes(strip_tags($_POST['txt_cmt_place']));
            $arr['cdate']=time();
            $arr['isactive']=1;
            $username=addslashes($_POST['txtid']);
            /*$path= $path_curent!=''? $path_curent.$username:$this_user;
            $path.="_";
            $arr['path']=$path;*/
            SysEdit('tbl_member',$arr, " username='$username'");
            ?>
            <script>
                $(function() {
                    showMess('Sửa thông tin thành công','');
                    setTimeout(function(){window.location='<?php echo ROOTHOST."members";?>';},1000);
                });
            </script>
            <?php
        }
    }
    ?>

    <div class="group-box">
        <div class="box-card">
            <div class="action-tool">
                <h2 class="label-title">Sửa tài khoản</h2>
                <?php require_once('modules/toolbar.php');?>
            </div>

            <div class="clearfix"></div>
            <div id="action">
                <p style="color: #ff0000"><?php echo $er_mess;?></p>
                <form id="frm_action" name="frm_action" method="post" action="" class="col-md-8">
                    <input name="txtid" type="hidden" id="txtid" value="<?php echo $id;?>" />
                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Tên đăng nhập*</label>
                        <div class="col-sm-9">
                            <input type="text" required class="form-control" name="txtusername" id="txtusername" readonly="true" value="<?php echo $row['username'];?>"/>
                            <span id="username_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Họ và tên*</label>
                        <div class="col-sm-9">
                            <input type="text" name="txtfullname" id="txtfullname" value="<?php echo $row['fullname'];?>" required class="form-control"/>
                            <span id="fullname_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Ngày sinh*</label>
                        <div class="col-sm-9">
                            <input type="date" name="txtbirthday" id="txtbirthday" value="<?php echo date("Y-m-d",strtotime($row['birthday']));?>" required class="form-control"/>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Đại lý OR Sale*</label>
                        <div class="col-sm-9">
                            <label class="radio-inline"><input type="radio" name="opt_isroot" value="1" <?php if($row['isroot']==1) echo ' checked="checked"';?> /> Đại lý</label>
                            <label class="radio-inline"><input type="radio" name="opt_isroot" value="0" <?php if($row['isroot']==0) echo ' checked="checked"';?>/>Sale</label>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">CMT*</label>
                        <div class='col-sm-9'>
                            <div class='row'>
                                <div class='col-sm-6'><input type='text'  value="<?php echo $row['cmt'];?>" class='form-control' name='txt_cmt' id='txt_cmt' placeholder='CMTND' value='<?php if(isset($_POST['txt_cmt'])) echo $_POST['txt_cmt'];?>' required="true"/></div>
                                <div class='col-sm-6'><input type='date'  value="<?php echo $row['cmt_date'];?>" class='form-control' name='txt_cmt_date' id='txt_cmt_date' placeholder='Ngày cấp' value='<?php if(isset($_POST['txt_cmt_date'])) echo $_POST['txt_cmt_date'];?>' /></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nơi cấp</label>
                        <div class='col-sm-9'>
                            <input type='text'  value="<?php echo $row['cmt_place'];?>" class='form-control' name='txt_cmt_place' id='txt_cmt_place' placeholder='Nơi cấp' value='<?php if(isset($_POST['txt_cmt_place'])) echo $_POST['txt_cmt_place'];?>' />
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label hidden-xs">Địa chỉ </label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control'  name='txtaddress' id='txtaddress' placeholder='Địa chỉ'  value="<?php echo $row['address'];?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label hidden-xs">Điện thoại</label>
                        <div class='col-sm-6'>
                            <input type='tel' class='form-control' name='txtphone' id='txtphone' placeholder='Điện thoại'  value="<?php echo $row['phone'];?>" />
                        </div>
                        <span class='col-sm-3 hidden-xs'>VD: 0988233555</span>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label hidden-xs">Email</label>
                        <div class='col-sm-6'>
                            <input type='email' class='form-control' name='txtemail' id='txtemail' placeholder='Email'  value="<?php echo $row['email'];?>"/>
                        </div>
                        <span class='col-sm-3 hidden-xs'>abc@gmail.com</span>
                        <div class="clearfix"></div>
                    </div>

                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label hidden-xs">Mã bảo mật</label>
                        <div class='col-sm-6 col-xs-12'><div class='row'>
                            <div class='col-sm-6'><input class='form-control' type='text' name='txt_code' id='txt_code' placeholder='security code' /></div>
                            <div class='col-sm-6'><img align="middle" style='float:left;height:35px' src='<?php //echo ROOTHOST;?>extensions/captcha/CaptchaSecurityImages.php'/></div>
                        </div></div>
                        <span class='col-sm-3 hidden-xs'></span>
                    </div> -->

                    <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <?php
} unset($obj); ?>

<script language='javascript'>
    function checkinput(){
        var user=$('#txtusername').val();
        if(user==''){
            $('#username_result').html('Bạn chưa nhập Tên đăng nhập');
            $('#txtusername').focus();
            return false;
        }
        if(user.length<6){
            $('#username_result').html('Tên đăng nhập phải lớn hơn 6 ký tự');
            $('#txtusername').focus();
            return false;
        }
        if($('#txtfullname').val()=='') {
            $('#fullname_result').html('Bạn chưa nhập họ và tên');
            $('#txtfullname').focus();
            return false;
        }
        return true;
    }
</script>
