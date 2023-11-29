<?php
$strwhere='';
$check_permission = $UserLogin->Permission('user');
if($check_permission==false) die($GLOBALS['MSG_PERMIS']);
// Pagging
if(!isset($_SESSION['CUR_PAGE_USER']))
	$_SESSION['CUR_PAGE_USER']=1;
if(isset($_POST['txtCurnpage'])){
	$_SESSION['CUR_PAGE_USER']=(int)$_POST['txtCurnpage'];
}
?>
			<div class="com_header color">
				<h3 class="title"> Danh sách người dùng</h3>
				<div class="pull-right">
					<button id="cmd_user_add" class="btn btn-default"><i class="fa fa-user-plus" aria-hidden="true"></i> Thêm mới người dùng</button>
				</div>
			</div>
		

		<div class='user_list'>
			<div class="list">
			</div>
		</div>

	<script>
		function user_group_select(_item){
			var _gid=$(_item).attr('dataid');
			$('.user_group_list .menu li').removeClass('checked');
			$(_item).parent().addClass('checked');
			$('#guser_selected').val(_gid);
			getUserByGroup(_gid);
		}
		function getUserByGroup(gid){
			var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/getUserByGroup.php';
			$.post(url,{'gid':gid},function(req){
				$('.user_list .list').html(req);
			});
		}
		function changepass_user($this_user){

		}
		function edit_user($this_user){
			var _gid='';

			var _userid = $($this_user).attr('dataid');
			if(_userid=='' || _userid==0) showMess('Vui lòng chọn thành viên cần sửa','');
			else{
				$('#myModal .modal-dialog').removeClass('modal-sm');
				$('#myModal .modal-dialog').addClass('modal-lg');
				$('#myModal .modal-header .modal-title').html('Sửa người dùng');
				$('#myModal .modal-body').html('<p>Loadding...</p>');
				var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/frm_edit_user.php'; 
				$.post(url,{'userid':_userid,'gid':_gid},function(req){
					
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E02'){showMess('Không có quyền sửa người dùng ở nhóm này!','error');}
					if(req=='E03'){showMess('Không tồn tại người dùng này!','error');}
					else{
						$('#myModal .modal-body').html(req);
						$('#myModal').modal('show');
					}
				})
			}
			return false;
		}
		function del_user($this_user){
			var _gid=$('#guser_selected').val();
			var _userid = $($this_user).attr('dataid');
			if(_userid=='' || _userid==0) showMess('Vui lòng chọn thành viên cần xóa','');
			else{
			if(confirm("Bạn có chắc muốn xóa?")){
				var _gid=$('#guser_selected').val();
				var _userid = $($this_user).attr('dataid');
				if(_userid=='' || _userid==0) showMess('Chọn một cá nhân để xóa','');
				else{
					var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/process_del_user.php'; 
					$.post(url,{'userid':_userid,'gid':_gid},function(req){
						if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
						if(req=='E02'){showMess('Không có quyền xóa người dùng ở nhóm này!','error');}
						if(req=='E03'){showMess('Không tồn tại người dùng này!','error');}
						if(req=='E04'){showMess('Có lỗi trong quá trình sử lý!','error');}
						else{
							getUserByGroup(_gid);
							showMess('Success!','success');
						}
					})
				}
				return false;
			} }
		}
		function active_user($this_user){
			var _gid=$('#guser_selected').val();
			var _userid = $($this_user).attr('dataid');
			if(_userid=='' || _userid==0) showMess('Vui lòng chọn một thành viên','');
			else{
				var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/process_active_user.php'; 
				$.post(url,{'userid':_userid,'gid':_gid},function(req){
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E02'){showMess('Không có quyền sửa người dùng ở nhóm này!','error');}
					if(req=='E03'){showMess('Không tồn tại người dùng này!','error');}
					if(req=='E04'){showMess('Có lỗi trong quá trình sử lý!','error');}
					else{
						getUserByGroup(_gid);
						showMess('Success!','success');
					}
				})
			}
			return false;
		}
		$(document).ready(function(){
			getUserByGroup(0);
			var body_h=$('.body_body').outerHeight();
			$('.body_body .body_col_left').css({'height':body_h+'px'});
			$('.cmd_group_add a').click(function(){
				$('#myModal .modal-dialog').removeClass('modal-sm');
				$('#myModal .modal-dialog').addClass('modal-lg');
				$('#myModal .modal-header .modal-title').html('Thêm mới nhóm người dùng');
				$('#myModal .modal-body').html('<p>Loadding...</p>');
				var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/frm_add_group.php'; 
				$.get(url,function(req){
					if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
					if(req=='E02'){showMess('Không có quyền sửa nhóm này!','error');}
					else{
						$('#myModal .modal-body').html(req);
						$('#myModal').modal('show');
					}
				})
				return false;
			});
			$('.cmd_group_edit a').click(function(){
				var _gid=$('#guser_selected').val();
				if(_gid=='' || _gid==0) showMess('Bạn chưa chọn nhóm để sửa','error');
				else{
					$('#myModal .modal-dialog').removeClass('modal-sm');
					$('#myModal .modal-dialog').addClass('modal-lg');
					$('#myModal .modal-header .modal-title').html('Sửa thông tin nhóm người dùng');
					$('#myModal .modal-body').html('<p>Loadding...</p>');
					var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/frm_add_group.php'; 
					$.get(url,{'gid':_gid},function(req){
						if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
						if(req=='E02'){showMess('Không có quyền sửa nhóm này!','error');}
						else{
							$('#myModal .modal-body').html(req);
							$('#myModal').modal('show');
						}
					})
				}
				return false;
			});
			$('.cmd_group_del a').click(function(){
				var _gid=$('#guser_selected').val();
				if(_gid=='' || _gid==0) showMess('Bạn chưa chọn nhóm để xóa','error');
				else if(confirm("Bạn có chắc muốn xóa?")){
					var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/del_group.php'; 
					$.get(url,{'id':_gid},function(req){
						if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
						if(req=='E02'){showMess('Không có quyền xóa nhóm này!','error');}
						$('.user_group_list').html(req);
					});
					return false;
				} 
				return false;
			});
			$('#cmd_user_add').click(function(){
				var _gid=$('#guser_selected').val();
				if(_gid=='undefined' || _gid==0) showMess('Bạn chưa chọn nhóm để thêm','');
				else{
					$('#myModal .modal-dialog').removeClass('modal-sm');
					$('#myModal .modal-dialog').addClass('modal-lg');
					$('#myModal .modal-header .modal-title').html('Đăng ký người dùng');
					$('#myModal .modal-body').html('<p>Loadding...</p>');
					var url='<?php echo ROOTHOST_ADMIN?>ajaxs/user/frm_add_user.php'; 
					$.get(url,{'id':_gid},function(req){
						if(req=='E01'){showMess('Bạn chưa đăng nhập, xin vui lòng đăng nhập!','error');}
						if(req=='E02'){showMess('Không có quyền thêm người dùng vào nhóm này!','error');}
						else{
							$('#myModal .modal-body').html(req);
							$('#myModal').modal('show');
						}
					})
				}
				return false;
			})
		})
	</script>
