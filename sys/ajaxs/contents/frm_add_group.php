<?php
session_start();
define('COMS','contents');
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
require_once("../../libs/cls.category.php");
require_once("../../libs/cls.contents.php");

$objuser=new CLS_USER;
$obj=new CLS_CONTENTS;
$objcat=new CLS_CATEGORY;
if(!$objuser->isLogin()) die("E01");

$name=$intro=$img=$thumb='';$par_id=0;
$id=isset($_GET['id'])?(int)$_GET['id']:0;
if($id>0){
	$objcat->getList(" WHERE id=$id");
	$r=$objcat->Fetch_Assoc();
	$name=$r['name'];
	$intro=$r['intro'];
	$thumb=$r['thumb'];
	$par_id=$r['par_id'];
}
if($thumb!='') {
	$img=ROOTHOST.$r['thumb'];
	$img="<img src='$img' class='img-responsive'/><br><div><button type='button' class='btn btn-default' id='cmd_delete'>Xóa ảnh</button></div><br>";
}
?>
<form name="frmAdd" id="frmAdd" enctype="multipart/form-data" accept-charset="utf-8">
	<div class="form-group">
		<label for="name">Tên nhóm:</label>
		<input type="text" class="form-control" require name="txt_name" id="txt_name" value="<?php echo $name;?>">
		<input type="hidden" name="txt_id" value="<?php echo $id;?>"/>
	</div>
	<div class="form-group">
		<label>Thuộc nhóm tin</label>
		<select name="cbo_cate" class="form-control" id="cbo_cate">
			<option value="0" title="Top">Root</option>
			<?php $objcat->getListCate(0,0); ?>
		</select>
		<script>cbo_Selected("cbo_cate",'<?php echo $par_id;?>')</script>
	</div>
	<div class="form-group">
		<label for="thumb">Ảnh đại diện:</label>
		<div id="err" style="color:red"></div>
		<div id="box_img"><?php echo $img;?></div>
		<input type="file" class="form-control" require name="txt_thumb" id="txt_thumb" value="">
	</div>
	<div class="form-group">
		<label for="intro">Giới thiệu:</label>
		<textarea class="form-control" name="txt_intro" id="txt_intro" rows=5><?php echo $intro;?></textarea>
	</div>
	<button type="button" class="btn btn-primary" id="cmd_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</button>
	<button type="button" class="btn btn-default" id="cmd_cancel"><i class="fa fa-undo" aria-hidden="true"></i> Hủy</button>
</form>
<script>
	$(document).ready(function(){
		$("#frmAdd").on('submit',function(e) { 
			e.preventDefault();
			$.ajax({
			url: "<?php echo ROOTHOST_ADMIN;?>ajaxs/<?php echo COMS;?>/process_add_group.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(req){
				if(req=='E01')
					showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');
				else if(req=='ERR')
					$('#err').html('Xảy ra lỗi!');
				else if(req=='ERR1')
					$('#err').html('Vui lòng chọn file dung lượng < 1Mb');
				else if(req=='ERR2')
					$('#err').html('Vui lòng chọn kiểu file JPEG|JPG|PNG|GIF');
				else {
					$('.user_group_list').html(req);
					$('#myModalPopup').modal('hide');
				}
			}
		});
		});
		$('#frmAdd #cmd_delete').click(function(){
			if(confirm('Bạn muốn xóa ảnh này?')){
				$.post("<?php echo ROOTHOST_ADMIN;?>ajaxs/<?php echo COMS;?>/del_file_img.php",{'id':'<?php echo $id;?>','thumb':'<?php echo $thumb;?>'},function(data){
					if(data=='E01')
						showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');
					else if(data=='OK') $('#box_img').html('');
				})
			}
		});
		$('#frmAdd #cmd_save').click(function(){
			$('#frmAdd').submit();
		});
		$('#frmAdd #cmd_cancel').click(function(){
			$('#myModalPopup .modal-header .modal-title').html('Sửa thông tin người dùng');
			$('#myModalPopup .modal-body').html('<p>Loadding...</p>');
			$('#myModalPopup').modal('hide');
		});
	})
</script>