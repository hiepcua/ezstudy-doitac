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
    $id=trim($_GET["id"]);
$obj->getList(" AND `id`='".$id."'");
$row=$obj->Fetch_Assoc();
$name=$row['name'];
$intro=$row['intro'];
$par_id=$row['par_id'];
$permission=$row['permission'];
?>
<div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Cập nhật nhóm quyền
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
<div class="body col-md-12"><form name="frm-add" id="frm-add" method="POST">
    <div class="row form-group">
		<label class="col-md-2"></label>
		<div class="col-md-8"><span class='msg'><?php echo $msg;?></span></div>
	</div>
	<div class="row form-group">
		<label class="col-md-2 control-label">Tên nhóm <span class="star">*</span></label>
		<div class="col-md-8">
			<input type="hidden" id="txt_gid" name="txt_gid" value="<?php echo $id;?>"/>
			<input type="hidden" id="cmd_update" name="cmd_update" value="1"/>
			<input class="form-control" id="txtname" name="txtname" value="<?php echo $name;?>" type="text" required>
			<label id="txtname_err" class="check_error"></label>
		</div>
	</div>
	<div class="row form-group">
		<label class="col-md-2 control-label">Mô tả </label>
		<div class="col-md-8">
			<input class="form-control" id="txtdesc" name="txtdesc" value="<?php echo $intro;?>" type="text">
			<label id="txtname_err" class="check_error"></label>
		</div>
	</div>
	<div class="row form-group">
		<label class="col-md-2 control-label">Thuộc nhóm <span class="star">*</span></label>
		<div class="col-md-8">
			<select name="cbo_parid" id="cbo_parid" class="form-control">
              <option value="0" selected="selected" style="font-weight:bold"><?php echo "Root";?></option>
              <?php 
			  if(!isset($obju)) $obju = new CLS_GUSER();
			  $obju->getListGmem(0,1); 
			  unset($obju);
			  ?>
			  <script language="javascript">
			  cbo_Selected('cbo_parid',<?php echo $par_id;?>);
			  </script>
            </select>
		</div>
	</div>
	<div class="row form-group">
		<label class="col-md-2 control-label">Phân quyền <span class="star">*</span></label>
		<div class="col-md-10">
			<strong><input type="checkbox" name="permis_all" id="permis_all" value="1" onclick="checkall('permission[]',this.checked);"/> Chọn tất cả</strong>
			<div id='boxcom'>
			<?php
			$str='config';
			foreach($GLOBALS['ARR_COM'] as $key=>$value){
				$com=explode("_",$key);
				$com=$com[0];
				//if($str!=$com) echo "<div class='clearfix'></div>";
				$chk='';
				if($permission & $value) {
					$chk='checked="checked"';
				}
				echo '<div class="com"><input type="checkbox" name="permission[]" value="'.$value.'" onclick="'."checkonce('permission[]','permis_all')".'" '.$chk.'/> '.$GLOBALS['ARR_COM_NAME'][$key].'</div>';
				$str=$com;
			}
			?></div>
		</div>
		<div class="col-md-2"></div>
	</div>
	<div class="row form-group">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<input type="button" name="cmd_save" id="cmd_save" value="Lưu lại" class="btn btn-primary">
		</div>
		<div class="col-md-2"></div>
	</div>
</form></div>
<style>
#boxcom .com {float:left; width:45%}
</style>
<script type="text/javascript">
function checkinput(){
	if($("#txt_name").val()==""){
		$("#txt_name_err").fadeTo(200,0.1,function(){
			$(this).html('Vui lòng nhập tên bài viết').fadeTo(900,1);
		});
		$("#txt_name").focus();
		return false;
	}
	return true;
}
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
	$('#cmd_save').click(function(){
		var data = $('#frm-add').serializeArray();
		var url='<?php echo ROOTHOST_ADMIN;?>ajaxs/guser/process_edit.php'; 
		$.post(url,data,function(req){
			if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
			if(req=='E03'){showMess('Không có quyền sửa nhóm này!','error');}
			else {
				showMess('Success!','success');
				setTimeout(function(){window.location='<?php echo ROOTHOST_ADMIN;?>guser';},1000);
			}
		});
	});
})
</script>
