<?php
defined("ISHOME") or die("Can't acess this page, please come back!");
// Check quyền
$objuser=new CLS_USER;
if(!$objuser->isLogin()) die("E01");
$check_permission = $objuser->Permission('user');
$check_permis_group = $objuser->Permission('gusers');
if($check_permission==false || $check_permis_group==false) die('E02');

$name='';$intro=''; $msg=$permission=''; $id=$par_id=0;
if(isset($_GET["id"]))
    $id=addslashes(strip_tags($_GET["id"]));

else die('Không có mã user');
$user=$id;
$obj->getList(" AND `username`='".$id."'");
$r=$obj->Fetch_Assoc();
$check_kyc = (int)$r['iskyc']==1?"yes":"no";
$check_2fa = $r['is2fa'];
$joindate = $r['cdate'];
$kyc1 = !empty($r['kyc1']) ? ROOTHOST.$r['kyc1'] : ROOTHOST.'/images/thumb_img.png';
$kyc2 = !empty($r['kyc2']) ? ROOTHOST.$r['kyc2'] : ROOTHOST.'/images/thumb_img.png';
$kichhoat=$giahan=$lienket='';
if($r['rates']!=''){
    $rates=json_decode($r['rates'], true);
    $kichhoat=$rates['kichhoat'];
    $giahan=$rates['giahan'];
    $lienket=$rates['lienket'];
}
?>
<div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Cập nhật thành viên
        <div class="pull-right">
            <form id="frm_menu" name="frm_menu" method="post" action="">
                <input type="hidden" name="txtorders" id="txtorders" />
                <input type="hidden" name="txtids" id="txtids" />
                <input type="hidden" name="txtaction" id="txtaction" />

                <ul class="list-inline">
                    <li><a class="btn btn-default"  href="<?php echo ROOTHOST_ADMIN.COMS;?>" title="Đóng"><i class="fa fa-sign-out" aria-hidden="true"></i> Đóng</a></li>
                </ul>
            </form>
        </div>
    </div>
</div><br>
<div class="body col-md-12">
    <div class="col-md-3 col-xs-12">
        <div class="mod card">
            <div class=" card-body">
                <h3 class="title">Thông tin</h3>
                <div class="form-group">
                    <i class="fa fa-user"></i> Username: <strong class="pull-right"><?= $id;?></strong>
                </div>
                <div class="form-group">
                    <i class="fa fa-key"></i> Password: <a href="#" id="btn-changepass" class="pull-right">Change password</a>
                </div>
                <div class="form-group">
                    <i class="fa fa-user"></i> Par User: <strong class="pull-right"><?= $r['par_user'];?></strong>
                </div>
                <div class="form-group">
                    <i class="fa fa-calendar"></i> Join date: <strong class="pull-right"><?php echo trim($joindate)!='N/a'?date('Y-m-d',$joindate):'';?></strong>
                </div>
                <div class="form-group">
                    <i class="fa fa-tree"></i> Active:
                    <label class="switch text-right pull-right">Enable</label>
                </div>
                <hr/>
                <div class='form-group'>
                    <i class="fa fa-cc-discover"></i> 2FA:
                    <label class="switch pull-right">
                        <input id="check_2fa" type="checkbox" disabled <?php if($check_2fa==1) echo 'checked'?> value="0">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class='form-group 2fa_qrcode text-center' id='2fa_qrcode'>
                    <?php
                    if((int)$r['is2fa']==1){
                        $gsecret=$r['gsecret'];
                        $ga = new PHPGangsta_GoogleAuthenticator();
                        echo '<img src="'.$ga->getQRCodeGoogleUrl(NAME_2FA,$gsecret,$id).'" />';
                        echo "<div>2FA Qr-Code</div>";
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-xs-12">
        <div class="col-md-6">
            <?php
            $table='tbl_wallet_b';
            $total_money = getTotalBonus($table, false,$user);
            $money_used = getTotalBonus($table, 0,$user);
            $money_nap = getTotalBonus($table, 1,$user);
            ?>
            <div class="mod card">
                <div class=" card-body">
                        <h3 class="title">WALLET S</h3>
                        <ul class="list-group">
                            <li class="list-group-item justify-content-between">
                                Tổng điểm
                                <span class="badge badge-default badge-pill"><?php echo number_format($total_money);?></span>
                            </li>
                            <li class="list-group-item justify-content-between">
                                Đã nạp
                                <span class="badge badge-default badge-pill"><?php echo number_format($money_nap);?></span>
                            </li>
                            <li class="list-group-item justify-content-between">
                                Đã sử dụng
                                <span class="badge badge-default badge-pill"><?php echo number_format($money_used);?></span>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php
            $table='tbl_wallet_b';
            $total_money = getTotalBonus($table, false,$user);
            $money_used = getTotalBonus($table, 0,$user);
            $money_nap = getTotalBonus($table, 1,$user);
            ?>
            <div class="mod card">
                <div class=" card-body">
                    <h3 class="title">WALLET E</h3>
                    <ul class="list-group">
                        <li class="list-group-item justify-content-between">
                            Tổng điểm
                            <span class="badge badge-default badge-pill"><?php echo number_format($total_money);?></span>
                        </li>
                        <li class="list-group-item justify-content-between">
                            Đã nạp
                            <span class="badge badge-default badge-pill"><?php echo number_format($money_nap);?></span>
                        </li>
                        <li class="list-group-item justify-content-between">
                            Đã sử dụng
                            <span class="badge badge-default badge-pill"><?php echo number_format($money_used);?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="box_new_account">
            <label class="control-label hidden-xs" style="margin-top: 15px">Cấu hình tỉ lệ Chiết khấu</label>
            <div class="form-group">
                    <div class='row'>
                        <div class='col-sm-4 col-xs-12'>
                            <label class="col-sm- control-label hidden-xs">Kích hoạt</label>
                            <input type='text'  value="<?php echo $kichhoat;?>" class='form-control' name='txt_active_account' id='txt_active_account' placeholder='10%' value=''  />
                        </div>
                        <div class='col-sm-4 col-xs-12'>
                            <label class="col-sm- control-label hidden-xs">Gia hạn</label>
                            <input type='text'  value="<?php echo $giahan;?>" class='form-control' name='txt_change' id='txt_refercode' placeholder='5%' />
                        </div>
                        <div class='col-sm-4 col-xs-12'>
                            <label class="col-sm- control-label hidden-xs">Liên kết</label>
                            <input type='text'  value="<?php echo $lienket;?>" class='form-control' name='txt_link' id='txt_refercode' placeholder='5%' />
                        </div>
                        <div class="clearfix"></div>
                    </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
        </form>
    </div>
    <div class="clearfix"></div>
</div>
<style>
    #boxcom .com {float:left; width:45%}
</style>
<script type="text/javascript">
    function checkall(name,status){
        var objs=document.getElementsByName(name);
        for(i=0;i<objs.length;i++)
            objs[i].checked=status;
    }
    function checkonce(name,all){
        var objs=document.getElementsByName(name);
        var flag=true;
        for(i=0;i<objs.length;i++)
            if(objs[i].checked!=true)
            {
                flag=false;
                break;
            }
        document.getElementById(all).checked=flag;
    }
    $(document).ready(function(){
        $("#check_isbus").click(function(){
            if($("#check_isbus").val()==0) {
                var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/member/process_isbus.php';
                $.post(url,{'username':'<?php echo $id;?>'},function(req){
                    if (req=="success") {
                        showMess('Success!','success');
                        setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},1000);
                    }else showMess(req,'error');
                })
            }
        });
        $("#check_kyc").click(function(){
            console.log($(this).prop('checked'));
            if($("#check_kyc1").val()==1 && $("#check_kyc2").val()==1) {
                var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/<?= COMS;?>/process_kyc.php';
                $.post(url,{'username':'<?php echo $id;?>'},function(req){
                    console.log(req);
                    if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
                    if(req=='E03'){showMess('Không có quyền kích hoạt chức năng này!','error');}
                    else if (req=="success") {
                        showMess('Success!','success');
                        setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},1000);
                    }else showMess(req,'error');
                })
            }
        })
        $('#cmd_save').click(function(){
            var data = $('#frm-edit').serializeArray();
            var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/<?= COMS;?>/process_edit.php';
            $.post(url,data,function(req){
                console.log(req);
                if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
                if(req=='E03'){showMess('Không có quyền sửa nhóm này!','error');}
                else if (req=="success") {
                    showMess('Success!','success');
                    setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN.COMS."/edit/".$id;?>';},1000);
                }else showMess(req,'error');
            });
        });
    })
</script>
