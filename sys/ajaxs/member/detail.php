<?php
session_start();
require_once("../../global/libs/gfinit.php");
require_once("../../global/libs/gfconfig.php");
require_once("../../global/libs/gffunc.php");
require_once("../../includes/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
if(!$objuser->isLogin()) die("E01");
$check_permission = $objuser->Permission('user');
$check_permis_group = $objuser->Permission('gusers');
if($check_permission==false || $check_permis_group==false) die('E02');

$name='';$intro=''; $msg=$permission=''; $id=$par_id=0;
if(isset($_GET["id"]))
    $id=addslashes(strip_tags($_GET["id"]));
else die('Không có mã user');
$obj->getList(" AND `username`='".$id."'");
$r=$obj->Fetch_Assoc();
$check_kyc = (int)$r['iskyc']==1?"yes":"no";
$check_2fa = $r['is2fa'];
$joindate = $r['cdate'];
?>

3333
<div class="row">
    <div class="col-md-3">
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
    </div>
    <div class="col-md-9"></div>
</div>
