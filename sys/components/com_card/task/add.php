<?php
defined("ISHOME") or die("Can't acess this page, please come back!");
?>
<div id="action">
<script language="javascript">
function checkinput(){
	if($("#txtname").val()=="")
	{
	 	$("#txtname_err").fadeTo(200,0.1,function()
		{ 
		  $(this).html('Vui lòng nhập tên').fadeTo(900,1);
		});
	 	$("#txtname").focus();
	 	return false;
	}
	return true;
}
$(document).ready(function(){
	$("#txtname").blur(function() {
		if( $(this).val()=='') {
			$("#txtname_err").fadeTo(200,0.1,function()
			{ 
			  $(this).html('Vui lòng nhập tên').fadeTo(900,1);
			});
		}
		else {
			$("#txtname_err").fadeTo(200,0.1,function()
			{ 
			  $(this).html('').fadeTo(900,1);
			});
		}
	})
})
</script>
  <form id="frm_action" name="frm_action" method="post" action="">
	<div class='col-sm-4'></div>
	<div class='col-sm-4'>
		<h3 class='text-center'>TẠO THẺ</h3><hr/>
		<div class='form-group'>
			<label>Chọn packet<font color="red">*</font></label>
			<input class='form-control' type="number" name="txt_packet" id="txt_packet" min="0" size="9" />
			<label>Số lượng<font color="red">*</font></label>
			<input class='form-control' id="inputCheckOther" type='number' value='1' id='txt_num' name='txt_num' size=2 min="1"/>
            <span id="show-in"></span>
		</div>
		<div class='form-group text-center'>
		<button type="submit" class='btn btn-primary' name="cmdsave" id="cmdsave">Tạo thẻ</button>
		</div>
	</div>
	<div class='col-sm-4'></div>
	<div class='clearfix'></div>
  </form>
</div>