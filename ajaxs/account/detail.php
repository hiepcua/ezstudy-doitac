<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$item_id=isset($_POST['item_id'])? $_POST['item_id']:'';
	$row=json_decode($item_id, true);
	
	$label=$row['utype']=='chame'? 'Phụ huynh':'Học sinh';
	$class_notic='';
	$status='Chưa active';
	$end_time=(int)$row['edate'];

	if($end_time==''){
		$label_end_time='N/A';
		$status='Chưa kích hoạt';
	}
	else {
		$label_end_time=date('d-m-Y', $end_time);
		$today=time();
		$notic_endtime=$today+DATE_EXTEND*24*60*60; // ngày dự kiến hết hạn +10
		$class_notic='';
		if($end_time <= $notic_endtime){
			$n=date('d', $notic_endtime-$end_time)+1;
			$status='<p>Sắp hết hạn</p> (Còn '.$n.' ngày)';
			$class_notic='notic';
		}
		elseif($today > $end_time){
			$status='Đã hết hạn';
			$class_notic='notic';
		}
		else $status='Đang sử dụng';
	}
	?>
	<div class="detail-account">
    <div class="col-md-6">
	<h4 class="name">Thông tin tài khoản</h4>
		
        <div class="form-group">
            Username: <strong class="pull-right"><?= $row['username'];?></strong>
        </div>
		
        <div class="form-group">
            Loại TK: <strong class="pull-right"><?= $label;?></strong>
        </div> 
		<div class="form-group">
            Join date: <strong class="pull-right"><?php echo date('d-m-Y',$row['cdate']);?></strong>
        </div>
		<div class="form-group">
            Ngày hết hạn: <strong class="pull-right"><?php echo $label_end_time;?></strong>
        </div>
        <div class="form-group">
             Trạng thái:
            <label class="switch text-right pull-right"><?php echo $status;?></label>
        </div>
        </div> 
	 <div class="col-md-6">

	 <h4 class="name">Thông tin Chi tiết</h4>
	 <div class="form-group">
            Fullname: <strong class="pull-right"><?= $row['fullname'];?></strong>
        </div>
	 <div class="form-group">
            Phone: <strong class="pull-right"><?= $row['phone'];?></strong>
        </div> 
		<div class="form-group">
            Email: <strong class="pull-right"><?= $row['email'];?></strong>
        </div> 
		 <div class="form-group">
            Lớp ĐK: <strong class="pull-right"><?= $row['grade'];?></strong>
        </div> 
		<div class="form-group">
            Quản lý bởi: <strong class="pull-right"><?= $row['saler'];?></strong>
        </div>
		<div class="form-group">
           Ref User: <strong class="pull-right"><?= $row['ref_user'];?></strong>
        </div>
        
    </div>
	<div class="clearfix"></div>
    </div>

    
    <?php 
    }

else{
    die('Vui lòng đăng nhập hệ thống');
}
?>

