<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
define('OBJ_PAGE','GUSER');
$keyword='';$strwhere='';$action='';

// Khai báo SESSION
if(isset($_POST['txtkeyword'])){
  $keyword=trim($_POST['txtkeyword']);
  $_SESSION['KEY_'.OBJ_PAGE]=$keyword;
}
if(isset($_POST['cbo_active']))
    $_SESSION['ACT'.OBJ_PAGE]=addslashes($_POST['cbo_active']);
if(isset($_SESSION['KEY_'.OBJ_PAGE]))
    $keyword=$_SESSION['KEY_'.OBJ_PAGE];
else
    $keyword='';
$action=isset($_SESSION['ACT'.OBJ_PAGE]) ? $_SESSION['ACT'.OBJ_PAGE]:'';

// Gán strwhere
if($keyword!='')
    $strwhere.=" AND ( `name` like '%$keyword%' )";
if($action!='' && $action!='all' ){
    $strwhere.=" AND `isactive` = '$action'";
}
if(isset($_POST['txtkeyword'])){
    $keyword=trim($_POST['txtkeyword']);
    $_SESSION['KEY_'.OBJ_PAGE]=$keyword;
}
$obj->getList($strwhere,''); 
?>
<script language="javascript">
	function checkinput(){
		var strids=document.getElementById("txtids");
		if(strids.value==""){
			alert('You are select once record to action');
			return false;
		}
		return true;
	}
</script>
 <div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Nhóm quyền
        <div class="pull-right">
            <div id="menus" class="toolbars">
                <form id="frm_menu" name="frm_menu" method="post" action="">
                    <input type="hidden" name="txtorders" id="txtorders" />
                    <input type="hidden" name="txtids" id="txtids" />
                    <input type="hidden" name="txtaction" id="txtaction" />
                    <ul class="list-inline">
                        <li><button class="btn btn-default" onclick="dosubmitAction('frm_menu','public');"><i class="fa fa-check-circle-o cgreen" aria-hidden="true"></i> Hiển thị</button></li>
                        <li><button class="btn btn-default" onclick="dosubmitAction('frm_menu','unpublic');"><i class="fa fa-times-circle-o cred" aria-hidden="true"></i> Ẩn</button></li>
                        <li><a class="addnew btn btn-default" href="<?php echo ROOTHOST_ADMIN.COMS;?>/add" title="Thêm mới"><i class="fa fa-plus-circle cgreen" aria-hidden="true"></i> Thêm mới</a></li>
                        <li><a class="delete btn btn-default" href="#" onclick="javascript:if(confirm('Bạn có chắc chắn muốn xóa thông tin này không?')){dosubmitAction('frm_menu','delete'); }" title="Xóa"><i class="fa fa-times-circle cred" aria-hidden="true"></i> Xóa</a></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div><br>
<div class='col-md-12 user_list'>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr class="header">
                <th width="30" align="center">#</th>
                <th width="50" align="center">Xóa</th>
				<th align="center">Tên nhóm 
					<div class="sort" sort-name="name">
						<i class="fa fa-sort-up" title="Giảm" sort="desc"></i>
						<i class="fa fa-sort-down" title="Tăng" sort="asc"></i>
					</div>
				</th>
                <th width="50" align="center">Hiển thị</th>
                <th width="50" align="center">Sửa</th>
            </tr>
			<?php $stt=0; while($r = $obj->Fetch_Assoc()) { 
				$stt++; $id = $r['id'];
				if($r['isactive']==1) 
					$icon_active="<i class='fa fa-check cgreen' aria-hidden='true'></i>";
				else $icon_active='<i class="fa fa-times-circle-o cred" aria-hidden="true"></i>';
			?>
			<tr><td><?= $stt;?></td>
				<td><a href='<?= ROOTHOST_ADMIN;?>guser/delete/<?=$id;?>' onclick=" return confirm('Bạn có chắc muốn xóa ?')"><i class='fa fa-trash cgray' aria-hidden='true'></i></a></td>
				<td><?= $r['name'];?></td>
				<td><a href="<?= ROOTHOST_ADMIN;?>guser/active/<?=$id;?>"><?= $icon_active;?></a></td>
				<td><a href="<?= ROOTHOST_ADMIN;?>guser/edit/<?=$id;?>"><i class='fa fa-edit'></i></a></td>
			</tr>
			<?php } ?>
        </table>
    </form>
</div>
<?php //----------------------------------------------?>
