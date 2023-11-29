<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
$er_mess='';
if(isLogin()){
    $this_user=getInfo('username');
    $path_curent=getInfo('path');
    $isroot=getInfo('isroot');

    if($isroot!=1){
        echo '<div class="group-box"><div class="box-card"><h4> Chức năng này chỉ sử dụng cho tài khoản là Đại lý. Xin cảm ơn!</h4></div></div>';
        die;
    }
    if(isset($_POST['cmdsave'])){
        $username1=$_POST['txtusername'];
        $par_user=addslashes($_POST['txt_paruser']);
        $username=un_unicode(addslashes(trim(strip_tags($_POST['txtusername']))));
        if($username=='')
            $er_mess.='Tên đăng nhập không được trống<br/>';
        if(strlen($username)<=6)
            $er_mess.='Tên đăng nhập ít nhất 6 ký tự<br/>';
        if($username1!=$username) $mess.='Tài khoản nhập phải là không có dấu và không chứa ký tự đặc biệt<br/>';
        if($_POST['txtpassword']!=$_POST['txtrepass'])
            $er_mess.='Nhập lại mật khẩu chưa chính xác<br/>';
        if(MemberCountList(" AND `isactive`=1 AND `username`='$username'")>0)  $er_mess.='Tài khoản này đã tồn tại. Vui lòng đăng ký 1 tài khoản khác<br>';
        if(!isset($_SESSION['SERCURITY_CODE']) || $_SESSION['SERCURITY_CODE']!=$_POST['txt_code'])
               $er_mess .= 'Mã bảo mật không chính xác<br/>';
		   
        $flag_config=0;
        if(!isset($_POST['txt_active_account']) OR $_POST['txt_active_account']=='') $flag_config=1;
        if(!isset($_POST['txt_fromtotal']) OR $_POST['txt_fromtotal']=='') $flag_config=1;
        if(isset($_POST['txt_fromtotal']) && $_POST['txt_fromtotal']!='') $flag_config=1;
        if($flag_config==0) $er_mess .= 'Chưa cấu hình tỉ lệ thưởng<br/>';
        if($er_mess==''){
			$it_file=SysGetList('tbl_member', array('path'), " AND username='$par_user'");
			$user_path=isset($it_file[0]['path'])? $it_file[0]['path']:'';
			
            $arr  = array();
            $arr['username']=$username;
            $arr['par_user']=$par_user;
            $arr['fullname']=addslashes($_POST['txtfullname']);
            $arr['birthday']=date('Y-m-d',strtotime($_POST['txtbirthday']));
            $arr['address']=addslashes($_POST['txtaddress']);
            $arr['phone']=addslashes($_POST['txtphone']);
            $arr['email']=addslashes($_POST['txtemail']);
            $arr['isroot']=isset($_POST['opt_isroot']) ? (int)$_POST['opt_isroot']:'0';
            $arr['cmt']=addslashes(strip_tags($_POST['txt_cmt']));
            $arr['cmt_date']=addslashes($_POST['txt_cmt_date']);
            $arr['cmt_place']=addslashes(strip_tags($_POST['txt_cmt_place']));

            $arr['cdate']=time();
            $arr['isactive']=1;
            //save rates
            $arr_config=array();
			if(isset($_POST['txt_fromtotal'])){
				foreach($_POST['txt_fromtotal'] as $key=>$val){
					$doanhso_from=isset($_POST['txt_fromtotal'][$key])? $_POST['txt_fromtotal'][$key]:'';
					$doanhso_to=isset($_POST['txt_tototal'][$key])? $_POST['txt_tototal'][$key]:'';
					$kichhoat=isset($_POST['txt_active_account1'][$key])? $_POST['txt_active_account1'][$key]:'';
					$giahan=isset($_POST['txt_change1'][$key])? $_POST['txt_change1'][$key]:'';
					$lienket=isset($_POST['txt_link1'][$key])? $_POST['txt_link1'][$key]:'';
					$doanhso_from=str_replace(',','',$doanhso_from);
					$doanhso_to=str_replace(',','',$doanhso_to);
					$label=$doanhso_from.','.$doanhso_to;
					$arr_config[$label]=array('kichhoat'=>$kichhoat, 'giahan'=>$giahan, 'lienket'=>$lienket);
				}
			}
			if(isset($_POST['txt_active_account']) && $_POST['txt_active_account']!=''){
				$kichhoat=isset($_POST['txt_active_account'])? (int)$_POST['txt_active_account']:'';
				$giahan=isset($_POST['txt_change'])? (int)$_POST['txt_change']:'';
				$lienket=isset($_POST['txt_link'])? (int)$_POST['txt_link']:'';
				$label='0,0';
				$arr_config[$label]=array('kichhoat'=>$kichhoat, 'giahan'=>$giahan, 'lienket'=>$lienket);
			}
		   $rates=json_encode($arr_config);
			//end rates
			$arr['rates']=$rates;
			$pass= addslashes($_POST['txtpassword']);
			$arr['password']=hash('sha256', $username).'|'.hash('sha256', $pass);
			$path= $user_path.$username."_";
			$arr['path']=$path;
		   SysAdd('tbl_member',$arr);
		   SysEdit('tbl_member', array('path'=>$path), " AND username='$username'");
			?>
		<script>
                $(function() {
                    showMess('Thêm mới thông tin thành công','');
                    setTimeout(function(){window.location='<?php echo ROOTHOST."saler";?>';},2000);
                });
            </script>
		<?php
		}

	}


?>

    <script language="javascript">
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
            var pass=$('#txtpassword').val();
            if(pass.length<6){
                $('#pass_result').html('Password phải lớn hơn 6 ký tự');
                $('#txtpassword').focus();
                return false;
            }
            if($('#txtpassword').val() != $('#txtrepass').val()) {
                $('#repass_result').html('Mật khẩu 1 và mật khẩu 2 không trùng nhau');
                return false;
            }
            if($('#txtfullname').val()=='') {
                $('#fullname_result').html('Bạn chưa nhập họ và tên');
                $('#txtfullname').focus();
                return false;
            }
			if($('#txt_active_account').val()=='' || $('#txt_change').val()=='' || $('#txt_link').val()=='') {
                $('#config_result').html('Bạn chưa nhập cấu hình');
               
                return false;
            }
			
			
            return true;
        }
    </script>
    <div class="group-box">
         <div class="box-card">
        <div class="action-tool">
            <h2 class="label-title">Thêm mới tài khoản</h2>
            <?php require_once('modules/toolbar.php');?>
        </div>
        <div class="clearfix"></div>
        <form id="frm_action" name="frm_action" method="post" action="">
            <p style="color: #ff0000"><?php echo $er_mess;?></p>
            <div class="row">
                <div class="col-sm-8">
                    <p style="margin-bottom: 15px">Các mục đánh dấu <font color="red">*</font> là thông tin bắt buộc</p>
                    <h4 class="name">Thông tin tài khoản</h4>
                    <span id="msgbox" style="display:none"></span>
                    <input name="txttask" type="hidden" id="txttask" value="1" />
                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Tên đăng nhập*</label>
                        <div class="col-sm-9">
                            <input type="text" name="txtusername" class="form-control" id="txtusername" placeholder="" value="" required="true">
                            <input type="hidden" name="chk_user" id="chk_user" value=""/>
                            <span id="username_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Mật khẩu*</label>
                        <div class="col-sm-9">
                            <input type="password" name="txtpassword" class="form-control" id="txtpassword" placeholder="" required="true">
                            <span id="pass_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">Nhập lại mật khẩu*</label>
                        <div class="col-sm-9">
                            <input type="password" name="txtrepass" class="form-control" id="txtrepass" placeholder="" required="true">
                            <span id="repass_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Par User</label>
                        <div class='col-sm-9'>
                            <select name="txt_paruser" class="form-control select txt_paruser" id="txt_paruser" data-placeholder="Chọn User">
                                <option value="<?php echo $this_user;?>">Root - <?php echo $this_user;?></option>
                                <?php
                                /*$str_list=str_replace('_',',',$user_path);
                                if($str_list!='') $str_list=substr($str_list,0,-1);*/
                                $arr=SysGetList('tbl_member', array('fullname','username','email'), " AND path like '$path_curent%' ORDER BY cdate DESC");
                                foreach($arr as $row){
                                    $fullname=$row['fullname'];
                                    $user=$row['username'];
                                    $select='';
                                    ?>
                                    <option value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 form-control-label">is Daily</label>
                        <div class="col-sm-9">
                            <label class="radio-inline"><input type="radio" name="opt_isroot" value="1" >Đại lý</label>
                            <label class="radio-inline"><input type="radio" name="opt_isroot" value="0" checked>Sale</label>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-8">
                    <h4 class="name">Thông tin người dùng</h4>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Họ tên*</label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control'  name='txtfullname' id='txtfullname' placeholder='Họ tên đầy đủ' value='<?php if(isset($_POST['txtfullname'])) echo $_POST['txtfullname'];?>' required="true"/>
                            <span id="fullname_result" class="mes-error"></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ngày sinh*</label>
                        <div class='col-sm-9'>
                            <input type='date' class='form-control'  name='txtbirthday' id='txtbirthday' placeholder='Ngày sinh' value='<?php if(isset($_POST['txtbirthday'])) echo $_POST['txtbirthday'];?>' required="true"/>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">CMT*</label>
                        <div class='col-sm-9'>
                            <div class='row'>
                                <div class='col-sm-6'><input type='text' class='form-control' name='txt_cmt' id='txt_cmt' placeholder='CMTND' value='<?php if(isset($_POST['txt_cmt'])) echo $_POST['txt_cmt'];?>' required="true"/></div>
                                <div class='col-sm-6'><input type='date' class='form-control' name='txt_cmt_date' id='txt_cmt_date' placeholder='Ngày cấp' value='<?php if(isset($_POST['txt_cmt_date'])) echo $_POST['txt_cmt_date'];?>' /></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nơi cấp</label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control' name='txt_cmt_place' id='txt_cmt_place' placeholder='Nơi cấp' value='<?php if(isset($_POST['txt_cmt_place'])) echo $_POST['txt_cmt_place'];?>' />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Địa chỉ </label>
                        <div class='col-sm-9'>
                            <input type='text' class='form-control'  name='txtaddress' id='txtaddress' placeholder='Địa chỉ' value='<?php if(isset($_POST['txtaddress'])) echo $_POST['txtaddress'];?>'/>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Điện thoại</label>
                        <div class='col-sm-6'>
                            <input type='tel' class='form-control' name='txtphone' id='txtphone' placeholder='Điện thoại' value='<?php if(isset($_POST['txtphone'])) echo $_POST['txtphone'];?>' />
                        </div>
                        <span class='col-sm-3 '>(+01) 111 111 1111</span>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Email</label>
                        <div class='col-sm-6'>
                            <input type='email' class='form-control' name='txtemail' id='txtemail' placeholder='Email' value='<?php if(isset($_POST['txtemail'])) echo $_POST['txtemail'];?>'/>
                        </div>
                        <span class='col-sm-3 '>abc@gmail.com</span>
                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>

            <hr>

            <div id="box_new_account">
                <h4 class="name">Cấu hình tỉ lệ Thưởng</h4>
                <div class='divider'></div>
                <!--<ul class="list-inline" id="config-box">
                    <li><span class="btn active" onclick="show(this,'box-1')">Cấu hình đơn</span></li>
                    <li><span class="btn active" onclick="show(this,'box-2')">Cấu hình theo doanh số</span></li>
                </ul>-->
                <div class="form-group">
                    <div class="box-content-config active" id="box-1">
						<h4 class="name2">Cấu hình mặc định</h4>
                        <div class='row'>
                            <div class='col-sm-4 col-xs-12'>
                                <label class="col-sm- control-label ">Kích hoạt (%)</label>
                                <input type='text' class='form-control' name='txt_active_account' id='txt_active_account' placeholder='' value='<?php if(isset($_POST['txt_active_account'])) echo $_POST['txt_active_account'];?>' required='true'/>
								<span id="config_result" class="mes-error"></span>
                            </div>
                            <div class='col-sm-4 col-xs-12'>
                                <label class="col-sm- control-label ">Gia hạn (%)</label>
                                <input type='text' class='form-control' name='txt_change' id='txt_change' placeholder=''  value='<?php if(isset($_POST['txt_change'])) echo $_POST['txt_change'];?>' required='true'/>
                            </div>
                            <div class='col-sm-4 col-xs-12'>
                                <label class="col-sm- control-label ">Liên kết (%)</label>
                                <input type='text' class='form-control' name='txt_link' id='txt_link' placeholder='' value='<?php if(isset($_POST['txt_link'])) echo $_POST['txt_link'];?>' required='true'/>
                            </div>
                            <div class="clearfix"></div>
                           
							 
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="box-content-config active" id="box-2">
					
                        <div id="frm_config">
						<h4 class="name2">Cấu hình theo doanh số</h4>
                            <table class="table table-bordered">
                                <tr class="header-tr">
                                    <td>
                                        <label class="control-label ">Từ</label>
                                        <input type='text' class='form-control number_format'  id='txt_fromtotal' placeholder='Doanh số' value=''  />
                                    </td>
                                    <td>
                                        <label class="control-label ">Đến</label>
                                        <input type='text' class='form-control number_format'  id='txt_tototal' placeholder='Doanh số' value=''  />
                                    </td>
                                    <td>
                                        <label class=" control-label ">Kích hoạt (%)</label>
                                        <input type='text' class='form-control' id='txt_active_account1' placeholder='10' value=''  />
                                    </td>
                                    <td>
                                        <label class="control-label ">Gia hạn (%)</label>
                                        <input type='text' class='form-control' id='txt_change1' placeholder='5' />
                                    </td>
                                    <td>
                                        <label class=" control-label ">Liên kết (%)</label>
                                        <input type='text' class='form-control' id='txt_link1' placeholder='1' />
                                    </td>
                                    <td>
                                        <label class="col-sm- control-label "></label>
                                        <label class="btn btn-success" id="act-form">Add</label>
                                    </td>
                                </tr>
                                <tbody id="content-rs"></tbody>
                            </table>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row">

                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label ">Mã bảo mật</label>
                            <div class='col-sm-9 col-xs-12'>
                                <div class='row'>
                                    <div class='col-sm-8'><input class='form-control' type='text' name='txt_code' id='txt_code' placeholder='security code' /></div>
                                    <div class='col-sm-4'><img align="middle" style='float:left;height:35px' src='<?php echo ROOTHOST;?>extensions/captcha/CaptchaSecurityImages.php'/></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    </div>
            </div>
        <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
		<hr>
		<div class="box-btn text-right">
			<a class="btn btn-primary" href="#" onclick="dosubmitAction('frm_action','save');" title="Lưu"><i class="fa fa-floppy-o" aria-hidden="true"></i><span class="hiden-label"> Lưu</span></a>
			<a class="btn btn-warning" href="<?php echo ROOTHOST.COMS;?>" title="Đóng"><i class="fa fa-close" aria-hidden="true"></i><span class="hiden-label"> Đóng</span></a>
		</div>
    </form>
    </div>
    </div>
<?php }?>
<script>
    function show(_this,id){
        $('#config-box .btn').removeClass('active');
        $('.box-content-config').hide();
        $(_this).addClass('active');
        $('#'+id).show();
    }
    $('#act-form').click(function(){
        var doanhso_from=$('#txt_fromtotal').val();
        var doanhso_to=$('#txt_tototal').val();
        var kichhoat=$('#txt_active_account1').val();
        var giahan=$('#txt_change1').val();
        var lienket=$('#txt_link1').val();
        if(doanhso_from=='' || doanhso_to=='' || kichhoat=='' || giahan==''|| lienket=='' ){
            alert('Bạn chưa nhập đủ nội dung');
            return false;
        }
        $.post('<?php echo ROOTHOST."ajaxs/mem/config_chietkhau.php";?>', {doanhso_from,doanhso_to,kichhoat,giahan, lienket}, function(data){
            $('#content-rs').prepend(data);

            $('#txt_fromtotal').val('');
            $('#txt_tototal').val('');
            $('#txt_active_account1').val('');
            $('#txt_change1').val('');
            $('#txt_link1').val('');
            $('#txt_fromtotal').focus();
        })
    });
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

</script>