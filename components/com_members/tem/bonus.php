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
	<div class="card box-report">
	<div class="card-body">
	<h3 class="title title-main">Tỉ lệ thưởng</h3>
        
            <h3 class="title"></h3>
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
        
<?php
}
}
unset($obj); ?>
