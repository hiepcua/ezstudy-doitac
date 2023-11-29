
<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
if(isLogin()){
    $this_user=getInfo('username');
	$mess='';
	//get par_rates
	$obj=SysGetList('tbl_member',array()," AND username='$this_user'");
	 $row=isset($obj[0])? $obj[0]: array();
	 $par_rates=json_decode($row['rates'], true);
	 
	 $total_par_rate=0;
    if(isset($rates['0,0'])){
        $total_par_rate=$rates['0,0']['kichhoat']+$rates['0,0']['giahan']+$rates['0,0']['lienket'];
    }
	
	
	
	//get rate user select
    if(isset($_GET['user'])) $id=addslashes($_GET['user']);
    $obj=SysGetList('tbl_member',array()," AND username='$id' AND par_user='$this_user'");
    $row=isset($obj[0])? $obj[0]: array();
	if(count($row)<1) die('Không tìm thấy User này trong danh sách Sale của bạn');
    $rates=json_decode($row['rates'], true);
    
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
		$total_rates=$kichhoat+$giahan+$lienket;
		if($total_rates>=  $total_par_rate) $mess='Tổng tỉ lệ thưởng của Đại lý hoặc Sale không được lớn hơn của Bạn';
		else{
			$rates=json_encode($arr_config);
			$arr['rates']=$rates;
			$arr['isactive']=1;
			SysEdit('tbl_member',$arr, " username='$id'");
			echo "<script language=\"javascript\">window.location='".ROOTHOST."members'</script>";
		}
    }

    ?>
    <script language='javascript'>
        function checkinput(){
			if($('#txt_active_account').val()=='' || $('#txt_change').val()=='' || $('#txt_link').val()==''){
				alert('Vui lòng nhập đủ nội dung');
				return false;
			}
            return true;
        }
    </script>
    <div class="group-box">
        <div class="box-card">
            <div class="action-tool">
                <h2 class="label-title">Cấu hình tỉ lệ Thưởng > <?php echo $id;?></h2>
                <?php require_once('modules/toolbar.php');?>
            </div>
            <div class="clearfix"></div>
            <div id="action">
                <form id="frm_action" name="frm_action" method="post" action="">
                    <input name="txtid" type="hidden" id="txtid" value="<?php echo $id;?>" />
                    <div class="row">
                        <div class='divider'></div>
                        
                       
                        <div class="col-md-6">
						<p style="color:red"><?php  echo $mess;?></p>
                        <div class="form-group">
                            <div class="box-content-config <?php if($type==1) echo 'active';?>" id="box-1">
                                <div class='row'>
                                    <div class='col-sm-4 col-xs-4'>
                                        <label class="col-sm- control-label ">Kích hoạt (%)</label>
                                        <input type='text' class='form-control' name='txt_active_account' id='txt_active_account' placeholder='10%' value='<?php echo $kichhoat;?>'  />
                                    </div>
                                    <div class='col-sm-4 col-xs-4'>
                                        <label class="col-sm- control-label ">Gia hạn (%)</label>
                                        <input type='text' class='form-control' name='txt_change' id='txt_change' placeholder='5%' value='<?php echo $giahan;?>'/>
                                    </div>
                                    <div class='col-sm-4 col-xs-4'>
                                        <label class="col-sm- control-label ">Liên kết (%)</label>
                                        <input type='text' class='form-control' name='txt_link' id='txt_link' placeholder='5%' value='<?php echo $lienket;?>'/>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            
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
