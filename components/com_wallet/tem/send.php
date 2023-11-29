<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
if(isLogin()){
    $er_mess='';
    $this_user=getInfo('username');
    $total_money=countTotalWallet('tbl_wallet_b',$this_user);
    if(isset($_POST['cmdsave']) && isset($_POST['txt_money'])){
        $flag=true;
        $p_money=str_replace(',','',$_POST['txt_money']);
        $money_tranfer = (int)$p_money;
        // if(!isset($_SESSION['SERCURITY_CODE']) || $_SESSION['SERCURITY_CODE']!=$_POST['txt_code']){
        //     $er_mess.= '<span style=" color: red">Mã bảo mật không chính xác</span><br/>';
        //     $flag=false;
        // }

        if($money_tranfer>$total_money) {
            $er_mess.= "Số Điểm cần chuyển không được vượt quá ".number_format($total_money).'<br/>';
            $flag=false;
        }

        $username=isset($_POST['txt_user'])? addslashes($_POST['txt_user']):'';
        $txt_note=isset($_POST['txt_note'])? addslashes($_POST['txt_note']):'';
        if($username=='')  $flag=false;
        if($flag==true){
            $arr2=array();
            $arr2['cuser']=$this_user;
            $arr2['type']=2;
            $arr2['status']=1;
            $arr2['cdate']=time();
            $arr2['username']=$this_user;
			// trừ người gửi
            $arr2['money']=-1*$money_tranfer;
            $arr2['note']="Lệnh chuyển  ".number_format($money_tranfer)."đ tới user ".$username; 
            $rs1=updatePayWallet('tbl_wallet_b',$this_user,$money_tranfer);
            if($rs1==true) $rs2=SysAdd('tbl_wallet_b_histories',$arr2,1);
            // cộng cho người nhận
            $arr2['username']=$username;
            $arr2['money']=$money_tranfer;
            if($txt_note!='') $note=$txt_note." (nhận điểm bởi ".$this_user.")";
            else  $note="Nhận điểm ".$money_tranfer." từ user ".$this_user;
            $arr2['note']=$note;
            if($rs2==true) $rs3=updatePushWallet('tbl_wallet_b',$username,$money_tranfer);
            if($rs3==true){
                SysAdd('tbl_wallet_b_histories',$arr2,1);
                ?>
                <script>
                    $(document).ready(function() {
                       showMess('Chuyển khoản thành công','');
                       setTimeout(function(){window.location.href="<?php echo ROOTHOST;?>";},3000);
                   })
               </script>
               <?php
           }
       }
   }
   ?>
   <script language="javascript">
    function checkinput(){
        if($('#txt_money').val()=="") {
            $('#txt_money_result').html('Bạn chưa nhập số điểm bạn muốn chuyển');
            $('#txt_money').focus();
            return false;
        }
        if($('#txt_user').val()=="") {
            $('#txt_user_result').html('Chọn người nhận');
            $('#txt_user').focus();
            return false;
        }
        if($('#txt_code').val()=="") {
            $('#txt_code_result').html('Nhập mã bảo mật');
            $('#txt_code').focus();
            return false;
        }
        return true;
    }
</script>
<div class='card'>
    <div class='card-body'>
        <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-8'>
                <div class='header'>
                    <div class='text-center'>
                        <h3 class='title'>Chuyển điểm</h3>
                    </div>
                </div><hr/>

                <form id="frm_action" name="frm_action" method="post" action="">
                    <p class="text-center"><?php echo $er_mess;?></p>
                    <div class='col-sm-12 main' id='send_pit_panel'>
                        <div class='text-center mes-error' style='color:red;'></div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 form-control-label">Số tiền chuyển*</label>
                            <div class="col-sm-9">
                                <input type="text" name="txt_money" class="form-control number_format" id="txt_money" placeholder="" required="true">
                                <span id="txt_money_result" class="mes-error"></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-3 form-control-label">Người nhận*</label>
                            <div class="col-sm-9">
                                <select name="txt_user" class="form-control select txt_user" id="txt_user" data-placeholder="Chọn User">
                                    <option value="0">Chọn người nhận</option>
                                    <?php
                                    $arr=SysGetList('tbl_member', array('fullname','username','email'), " AND username!='$this_user' ORDER BY cdate DESC");
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
                                <span id="txt_user_result" class="mes-error"></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class='form-group row'>
                            <label for="" class="col-sm-3 form-control-label">Note</label>
                            <div class="col-sm-9">
                                <textarea class='form-control form-textarea' id='txt_note' name='txt_note' placeholder='Send message'> </textarea>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label class="col-sm-3 control-label hidden-xs">Mã bảo mật*</label>
                            <div class='col-sm-7 col-xs-12'><div class='row'>
                                <div class='col-sm-8'><input class='form-control' type='text' name='txt_code' id='txt_code' required="" placeholder='security code' /></div>
                                <div class='col-sm-4'><img align="middle" style='float:left;height:35px' src='<?php //echo ROOTHOST;?>extensions/captcha/CaptchaSecurityImages.php'/></div>
                            </div></div>
                            <span class='col-sm-2 hidden-xs'></span>
                            <div class="clearfix"></div>
                        </div> -->

                        <hr>
                        <div class='form-group text-center'>
                            <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
                            <span class='btn btn-primary'  onclick="dosubmitAction('frm_action','save');">Thực hiện ></span>
                        </div>
                        <div class='clearfix'></div>
                    </div>
                </form>
                <div class='clearfix'></div>
                <div class='col-sm-2'></div>
            </div>
        </div>
    </div>
    <?php
}
else{
    header('location:'.ROOTHOST);
}
?>




