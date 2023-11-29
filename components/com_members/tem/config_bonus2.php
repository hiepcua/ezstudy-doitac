<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
if(isLogin()){
    $this_user=getInfo('username');
    if(isset($_GET['user'])) $id=addslashes($_GET['user']);
    $obj=SysGetList('tbl_member',array()," AND username='$id'");
    $row=isset($obj[0])? $obj[0]: array();
    $rates=json_decode($row['rates'], true);
    /*echo '<pre>';
   var_dump($rates);
   echo '</pre>';*/
    $kichhoat=$giahan=$lienket='';
    if(isset($rates['0,0'])){
        $type=1;
        $kichhoat=$rates['0,0']['kichhoat'];
        $giahan=$rates['0,0']['giahan'];
        $lienket=$rates['0,0']['lienket'];
    }
    else $type=2;

    if(isset($_POST['cmdsave'])){
        $arr  = array();
        $arr['par_user']=$this_user;
        //save rates
        if(isset($_POST['txt_fromtotal'])){
            $arr_config=array();
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
        $arr['rates']=$rates;
        $arr['isactive']=1;
        SysEdit('tbl_member',$arr, " AND username='$id'");
        echo "<script language=\"javascript\">window.location='".ROOTHOST."members'</script>";
    }

    ?>
    <script language='javascript'>
        function checkinput(){
            return true;
        }
    </script>
    <div class="group-box">
        <div class="box-card">
            <div class="action-tool">
                <h2 class="label-title">Cấu hình tỉ lệ Thưởng</h2>
                <?php require_once('modules/toolbar.php');?>
            </div>
            <div class="clearfix"></div>
            <div id="action">
                <form id="frm_action" name="frm_action" method="post" action="">
                    <input name="txtid" type="hidden" id="txtid" value="<?php echo $id;?>" />
                    <div id="box_new_account">
                        <div class='divider'></div>
                        <p style="font-style: italic">Lưu ý: Nếu có cả 2 cấu hình, hệ thống sẽ ưu tiên cấu hình theo doanh số</p>
                        <ul class="list-inline" id="config-box">
                            <li><span class="btn <?php if($type==1) echo 'active';?>" onclick="show(this,'box-1')">Cấu hình đơn</span></li>
                            <li><span class="btn <?php if($type==2) echo 'active';?>" onclick="show(this,'box-2')">Cấu hình theo doanh số</span></li>
                        </ul>
                        <div class="form-group">
                            <div class="box-content-config <?php if($type==1) echo 'active';?>" id="box-1">
                                <div class='row'>
                                    <div class='col-sm-4 col-xs-12'>
                                        <label class="col-sm- control-label hidden-xs">Kích hoạt (%)</label>
                                        <input type='text' class='form-control' name='txt_active_account' id='txt_active_account' placeholder='10%' value='<?php echo $kichhoat;?>'  />
                                    </div>
                                    <div class='col-sm-4 col-xs-12'>
                                        <label class="col-sm- control-label hidden-xs">Gia hạn (%)</label>
                                        <input type='text' class='form-control' name='txt_change' id='txt_change' placeholder='5%' value='<?php echo $giahan;?>'/>
                                    </div>
                                    <div class='col-sm-4 col-xs-12'>
                                        <label class="col-sm- control-label hidden-xs">Liên kết (%)</label>
                                        <input type='text' class='form-control' name='txt_link' id='txt_link' placeholder='5%' value='<?php echo $giahan;?>'/>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-content-config <?php if($type==2) echo 'active';?>" id="box-2">
                                <div id="frm_config">
                                    <table class="table table-bordered">
                                        <tr class="header-tr">
                                            <td>
                                                <label class="control-label hidden-xs">Từ</label>
                                                <input type='text' class='form-control number_format'  id='txt_fromtotal' placeholder='Doanh số' value=''  />
                                            </td>
                                            <td>
                                                <label class="control-label hidden-xs">Đến</label>
                                                <input type='text' class='form-control number_format'  id='txt_tototal' placeholder='Doanh số' value=''  />
                                            </td>
                                            <td>
                                                <label class=" control-label hidden-xs">Kích hoạt (%)</label>
                                                <input type='text' class='form-control' id='txt_active_account1' placeholder='10%' value=''  />
                                            </td>
                                            <td>
                                                <label class="control-label hidden-xs">Gia hạn (%)</label>
                                                <input type='text' class='form-control' id='txt_change1' placeholder='5%' />
                                            </td>
                                            <td>
                                                <label class=" control-label hidden-xs">Liên kết (%)</label>
                                                <input type='text' class='form-control' id='txt_link1' placeholder='5%' />
                                            </td>
                                            <td>
                                                <label class="col-sm- control-label hidden-xs"></label>
                                                <label class="btn btn-success" id="act-form">Add</label>
                                            </td>
                                        </tr>
                                        <tbody id="content-rs">
                                        <?php
                                        if(count($rates)>1){
                                            foreach($rates as $key=>$vl){
                                                if($key=='0,0') continue;
                                                $item=explode(',',$key);
                                                $doanhso_from=isset($item[0])? $item[0]:'';
                                                $doanhso_to=isset($item[1])? $item[1]:'';
                                                $kichhoat=$vl['kichhoat'];
                                                $giahan=$vl['giahan'];
                                                $lienket=$vl['lienket'];
                                                $doanhso_from=number_format($doanhso_from);
                                                $doanhso_to=number_format($doanhso_to);
                                                ?>
                                                <tr class="tr-box">
                                                    <td> <input type='text' class='form-control number_format'  name='txt_fromtotal[]' placeholder='Doanh số' value='<?php echo $doanhso_from;?>'  /></td>
                                                    <td><input type='text' class='form-control number_format'  name='txt_tototal[]' placeholder='Doanh số' value='<?php echo $doanhso_to;?>'  /></td>
                                                    <td><input type='text' class='form-control' name='txt_active_account1[]' id='txt_active_account1' placeholder='10%' value='<?php echo $kichhoat;?>'  /></span></td>
                                                    <td><input type='text' class='form-control' name='txt_change1[]' id='txt_change1' placeholder='5%' value='<?php echo $giahan;?>'/></td>
                                                    <td><input type='text' class='form-control' name='txt_link1[]' id='txt_link1' placeholder='5%' value='<?php echo $lienket;?>'/></td>
                                                    <td><span class="delete-item"><i class="fa fa-trash-o" aria-hidden="true"></i></span></td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
<?php
} unset($obj); ?>
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


</script>
