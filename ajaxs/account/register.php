<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(incl_path.'gffunc_wallet.php');
if(isLogin()){
	$this_user = getInfo('username');
	$table = 'tbl_wallet_b';
	$total_wallet = countTotalWallet($table,$this_user);
	$username = isset($_POST['item_id'])? addslashes($_POST['item_id']):'';
	$activate = isset($_POST['activate'])? (int)$_POST['activate']:'';

	// Danh sách packet
	$url = API_DC.'list-packet';
	$arr = array();
	$arr['key'] = PIT_API_KEY;
	$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post($url,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arr_packet=$rep['data'];
		?>
		<form method="post" action="" id="frm_action_extend">
			<input type="hidden" value="" name="money_packet" disabled  id="money_packet">
			<input type="hidden" value="<?php echo $activate;?>" name="txt_activate" disabled  id="txt_activate">
			<div class="modal-body-content">
				<div class='bg-info' style="padding: 8px 15px">Số dư ví B: <b style="color: red"><?php echo number_format($total_wallet);?>đ</b></div>
				<p class="mes-error" id="mes_err"></p>

				<div class="form-group">
					<label>Tài khoản</label>
					<input type="text" value="<?php echo $username;?>" name="txt_username" disabled class="form-control" id="txt_username" placeholder="">
					<span id="title_rr" class="input-err"></span>
					<div class="clearfix"></div>
				</div>

				<div class="form-group">
					<label>Chọn gói:</label>
					<select name="txt_packet" class="form-control txt_packet" id="txt_packet">
						<option value="">Chọn gói EZ</option>
						<?php
						foreach($arr_packet as $key=>$vl){
							?>
							<option value="<?php echo $key;?>"><?php echo $key." - ".$vl['name'];?></option>
							<?php
						}
						?>
					</select>
					<div class="input-err clearfix" id="packet_err"></div>
				</div>

				<div class="form-group" id='packet_price' style='display:none;'>
					<label>Chọn thời gian:</label>
					<select name="txt_month" class="form-control txt_month" id="txt_month" data-placeholder="Chọn Packet">   
					</select>
					<div class="input-err clearfix" id="channel_err"></div>
				</div>
			</div>

			<span id="act-spinner"><img src="<?php echo ROOTHOST."images/ajax-loader.gif";?>"></span>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
				<span class="btn btn-danger act-delete" id="act-process" value="Ok">Đăng ký</span>
			</div>
		</form>
		<?php 
	}
	else echo 'Không tồn tại dữ liệu Packet. Vui lòng quay lại sau!';
}
else{
	die('Vui lòng đăng nhập hệ thống');
}
?>
<script>
	$('#txt_packet').change(function(){
		var packet_id = $(this).val();
		var username = $("#txt_username").val();
		if(packet_id!=''){$('#packet_price').show() }
		var data = {
			"packet_id": packet_id,
			"username": username,
		};
		$.post('<?php echo ROOTHOST;?>ajaxs/account/get_packet.php', data, function(res){
			//console.log(res);
			$('#txt_month').html(res);
		});
	});

	function checkinput(){
		if($("#txt_month").val()==""){
			$("#channel_err").fadeTo(200,0.1,function(){
				$(this).html('Vui lòng chọn Packet').fadeTo(900,1);
			});
			$("#txt_month").focus();
			return false;
		}
		var money_packet=parseInt($('#money_packet').val());
		var total_wallet=parseInt('<?php echo $total_wallet;?>');

		if(total_wallet< money_packet){
			$('#mes_err').html('Số dư không đủ để thực hiện giao dịch!');
			return false;
		}
		return true;
	};

	$('#act-process').click(function(){
		if(checkinput()==false) return false;
		var form = $('#frm_action_extend');
		var postData = {
			txt_username: $('#txt_username').val(),
			txt_packet: $('#txt_packet').val(),
			txt_month: $('#txt_month').val(),
			txt_activate: $('#txt_activate').val(),
		};

		$.post('<?php echo ROOTHOST;?>ajaxs/account/process_register_packet.php',postData, function(res){
			if(res=='success'){
				$('#myModalPopup').modal('hide');
				showMess('Giao dịch thành công!','');
				setTimeout(function(){window.location.reload();},2000);
			}
			else{
				$('#mes_err').html(res);
			}
		});
	});
</script>
