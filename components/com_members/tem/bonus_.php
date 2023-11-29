<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
if(isLogin()){
    $this_user=getInfo('username');
    $obj=SysGetList('tbl_member',array()," AND username='$this_user'");
    $row=isset($obj[0])? $obj[0]: array();
	if($row['rates']==''){
		echo '<div class="card"><div class="card-body">Bạn chưa đc cấu hình tỉ lệ thưởng, Liên hệ với admin ngay!</div></div>';
		die;
	}
    if($row['rates']!==''){
        $rates=json_decode($row['rates'], true);
        $kichhoat=$giahan=$lienket='';
        if(isset($rates['0,0'])){
            $kichhoat=$rates['0,0']['kichhoat'];
            $giahan=$rates['0,0']['giahan'];
            $lienket=$rates['0,0']['lienket'];
        }


    ?>
        
            <h3 class="title">Tỉ lệ thưởng đơn</h3>
            <div class='row'>
                <div class='col-sm-4 col-xs-12'>
                    <label class="col-sm- control-label">Kích hoạt (%)</label>
                    <input disabled type='text' class='form-control' name='txt_active_account' id='txt_active_account' placeholder='10%' value='<?php echo $kichhoat;?>'  />
                </div>
                <div class='col-sm-4 col-xs-12'>
                    <label class="col-sm- control-label">Gia hạn (%)</label>
                    <input disabled type='text' class='form-control' name='txt_change' id='txt_change' placeholder='5%' value='<?php echo $giahan;?>'/>
                </div>
                <div class='col-sm-4 col-xs-12'>
                    <label class="col-sm- control-label">Liên kết (%)</label>
                    <input disabled type='text' class='form-control' name='txt_link' id='txt_link' placeholder='5%' value='<?php echo $giahan;?>'/>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        </div>
        <div class="card">
            <div class="card-body">
            <h3 class="title">Tỉ lệ thưởng theo doanh số</h3>
            <table class="table table-bordered">
                <tr class="header-tr">
                    <td>
                        <label class="control-label hidden-xs">Từ doanh số</label>
                    </td>
                    <td>
                        <label class="control-label hidden-xs">Đến doanh số</label>
                    </td>
                    <td>
                        <label class=" control-label hidden-xs">Kích hoạt (%)</label>
                    </td>
                    <td>
                        <label class="control-label hidden-xs">Gia hạn (%)</label>
                    </td>
                    <td>
                        <label class=" control-label hidden-xs">Liên kết (%)</label>
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
                            <td> <input disabled type='text' class='form-control number_format'  name='txt_fromtotal[]' placeholder='Doanh số' value='<?php echo $doanhso_from;?>'  /></td>
                            <td><input disabled type='text' class='form-control number_format'  name='txt_tototal[]' placeholder='Doanh số' value='<?php echo $doanhso_to;?>'  /></td>
                            <td><input disabled type='text' class='form-control' name='txt_active_account1[]' id='txt_active_account1' placeholder='10%' value='<?php echo $kichhoat;?>'  /></span></td>
                            <td><input disabled type='text' class='form-control' name='txt_change1[]' id='txt_change1' placeholder='5%' value='<?php echo $giahan;?>'/></td>
                            <td><input disabled type='text' class='form-control' name='txt_link1[]' id='txt_link1' placeholder='5%' value='<?php echo $lienket;?>'/></td>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
<?php
}
}
unset($obj); ?>
