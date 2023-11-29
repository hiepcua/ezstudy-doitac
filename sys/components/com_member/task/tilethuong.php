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
$kichhoat=$giahan=$lienket='';
if($r['rates']!=''){
    $rates=json_decode($r['rates'], true);
    $kichhoat=$rates['kichhoat'];
    $giahan=$rates['giahan'];
    $lienket=$rates['lienket'];
}
?>

<form id="frm-wallet-b" method="post">
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
</form>
<script type="text/javascript">
    $('.nav-tabs a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
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
        $('#type_select').change(function(){
           $('#frm-wallet-b').submit();
        });
    })
</script>
