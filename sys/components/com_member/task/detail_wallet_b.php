<?php
defined("ISHOME") or die("Can't acess this page, please come back!");
// Check quyền
$objuser=new CLS_USER;
if(!$objuser->isLogin()) die("E01");
$check_permission = $objuser->Permission('user');
$check_permis_group = $objuser->Permission('gusers');
if($check_permission==false || $check_permis_group==false) die('E02');
$cur_page=isset($_POST['txtCurnpage'])? (int)$_POST['txtCurnpage']:1;
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
<div class="com_header color">
    <i class="fa fa-list" aria-hidden="true"></i> Thông tin Wallet B
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
<br>
<div class="body-content">
    <form id="frm-wallet-b" method="post">
        <div class="wallet-title">
            <div class="row">
                <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="media">
                            <h3 class="title">E WALLET</h3>
                            <p><span class="number">3456đ</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-user">
                            <h5><?= $id;?></h5>
                            <p><i class="fa fa-user"></i> Par User: <?= $r['par_user'];?></p>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-md-7 align-self-center">
                    <div class="text-lg-center">
                        <div class="row">
                            <div class="col-md-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Tổng nạp</p>
                                    <h5 class="mb-0"> 190,000.00</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Đã chuyển</p>
                                    <h5 class="mb-0">40</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Đã kích hoạt</p>
                                    <h5 class="mb-0">18</h5>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Còn lại</p>
                                    <h5 class="mb-0">18</h5>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h3 class='title'>Lịch sử giao dịch</h3>
                <select name="txt_type" id="type_select" class="select-right">
                    <option value="1">Nạp</option>
                    <option value="2">Chuyển</option>
                    <option value="3">Gia hạn</option>
                </select>
                <div class="clearfix"></div>
            </div>
            <?php
            $strwhere=" AND username ='$user'";
            $type=isset($_POST['txt_type'])? (int)$_POST['txt_type']:'';
            if($type!='') $strwhere.=" AND type ='$type'";
            $total_items=SysCount('tbl_wallet_b',$strwhere);
            $start=($cur_page-1)*MAX_ROWS;
            $strwhere.=" ORDER BY id DESC LIMIT ".$start.",".MAX_ROWS;
            $array=SysGetList('tbl_wallet_b', array(),$strwhere, false);
            getListWallet($array);?>
        </div>
    </form>
    <div class="box-pagination">
        <?php echo paging($total_items,MAX_ROWS,$cur_page); ?>
    </div>
</div>
<div class="clearfix"></div>
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
