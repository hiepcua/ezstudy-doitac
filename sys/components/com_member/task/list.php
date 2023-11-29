<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
define('OBJ_PAGE','MEMBER');
$keyword='';$strwhere='';
$type=isset($_GET['type'])?addslashes($_GET['type']):1;// 0 là thành viên, 1 là đại lý
$keyword=isset($_GET['q'])?addslashes($_GET['q']):'';
$user_search=isset($_GET['id'])?addslashes($_GET['id']):'';
$user_search2=isset($_GET['txt_daily'])?addslashes($_GET['txt_daily']):'0';
$page=isset($_GET['page'])?addslashes($_GET['page']):1;
$iskyc=isset($_GET['iskyc'])?addslashes($_GET['iskyc']):'';
if($keyword!='')   $strwhere.=" AND ( `fullname` like '%$keyword%' OR `username` like '%$keyword%' OR `phone` like '%$keyword%' )";if($type==0){
    $title_page='Danh sách Thành viên';
    $strwhere.=" AND isroot='0'";
}
else{
    $title_page='Danh sách Đại lý';
    $strwhere.=" AND isroot='1'";
}
if($user_search!='') {
    $strwhere.=" AND par_user='$user_search'";
    $title_page='Danh sách thành viên thuộc Đại lý: '.$user_search;
}
if($user_search2!='0') {
    $strwhere.=" AND par_user='$user_search2'";
    $title_page='Danh sách thành viên';
}
$total_rows=SysCount('tbl_member',$strwhere);
$start=($page-1)*50;
$array=SysGetList('tbl_member',array(),$strwhere. " LIMIT $start,50", false);

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
<?php include_once('includes/toolbars.php'); ?>
<div class=" box_account">
    <?php if($type==1){?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="txtbold title">Nạp tiền Đại lý</span>
            </div>
            <div class="panel-body">
                <form class="frm_input_wallet" name="frm_input_wallet" method="post" action="">
                    <input type="hidden" name="by_user" value="<?php echo $username; ?>">
                    <input type="hidden" name="billid" value="<?php echo $billid;?>">
                    <div class="col-md-3 col_user">
                        <div class="form-group row">
                            <label for="" class="col-sm-3 form-control-label">Đại lý*</label>
                            <div class="col-sm-9">
                                <select name="txtcustomer_push" class="form-control select txtcustomer_push" data-placeholder="Chọn User">
                                    <option value="0">Tất cả</option>
                                    <?php
                                    $obj_mem = SysGetList('tbl_member', array('fullname','username','email'), " AND isroot=1 ORDER BY cdate DESC", false);
                                    while ($row = $obj_mem->Fetch_Assoc()) {
                                        echo '<option value="'.$row['username'].'" data-thumb="avatar_default.png" data-info="'.$row['username'].'">'.$row['fullname'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="text" placeholder="Số tiền nạp" name="number_wallet_push" min="1"  class="form-control number_format number_wallet_push" value="">
                    </div>
                    <div class="col-md-2">
                        <input type="password" placeholder="Secret code" name="secret_code_push" class="form-control secret_code_push" value="">
                    </div>
                    <div class="col-md-3">
                        <input type="text" placeholder="Ghi chú..." name="txt_note_push" class="form-control txt_note_push">
                    </div>
                    <div class="col-md-2">
                        <a href="#" class="btn btn-primary btn_push_wallet"><i class="fa fa-usd"></i> Nạp tiền</a>
                    </div>
                </form>
            </div>
        </div>
    <?php }?>


    <form method='get' name='frm_search' id='frm_search' action=''><div class='search row form-group'>
     <div class='col-md-4'>
      <input type='hidden' name='page' id='txtCurnpage' value='<?php echo $page;?>'/>
      <input type='text' name='q' id='txt_keyword' value='<?php echo $keyword;?>' class='form-control' placeholder='Name, phone or email'/>
  </div>
  <div class="col-md-3 col_user">
      <select name="txt_daily" class="form-control select txt_daily" data-placeholder="Chọn User">
       <option value="0">Tất cả đại lý</option>
       <?php
       $arr=SysGetList('tbl_member', array('fullname','username','email'), " AND isroot=1 ORDER BY cdate DESC");
       foreach($arr as $row){
        $fullname=$row['fullname'];
        $user=$row['username'];
        $select='';
        if($user==$user_search) $select='selected';
        ?>
        <option <?php echo $select;?> value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
        <?php
    }
    ?>
</select>
<div class="clearfix"></div>
</div>
<div class='col-md-3'><button type='submit' class='btn btn-primary'>Tìm kiếm</button></div>
</div>
</form>
<div class='clearfix'></div>
<div class="table-responsive">
  <table class="table tbl-main">
   <tr class="header">
    <th width="30">#</th>
    <th>Tài khoản</th>
    <th>Thông tin</th>
    <th class='text-right'>Ví B</th>
    <th class='text-right'>Ví S</th>
    <th class='text-right'>Doanh số</th>
    <th width="30" align="center"></th>
</tr>
<?php $stt=0;
$total_push=0;
while($r=$array->Fetch_Assoc()){
    $stt++;
    $id = $r['username'];
    if($r['isactive']==1) 
     $icon_active="<i class='fa fa-check cgreen' aria-hidden='true'></i>";
 else $icon_active='<i class="fa fa-times-circle-o cred"></i>';
 $iskyc = $r['iskyc'];
 $isbus = $r['isbus'];
 $this_user=$r['username'];
 $cur_wallet=countTotalWallet('tbl_wallet_b',$id,1);
 $total_push=countTotalWallet('tbl_wallet_b',$id,2);
 ?>
 <tr>
    <td><?php echo $stt;?></td>
    <td>
     <p>Họ tên: <strong><?= $r['fullname'];?></strong></p>
     <p>User: <strong><?= $r['username'];?></strong> (<a href='javascript:void(0);' class='change_pass' dataid='<?php echo $r['username'];?>'><i class="fa fa-unlock-alt"></i> change pass</a>)</p>
     <p>Đại lý: <strong><?= $r['par_user'];?></strong></p>

 </td>
 <td>
     <p>Điện thoại: <?php echo $r['phone'];?></p>
     <p>Email: <?php echo $r['email'];?></p>
     <p>CMT: <?php echo $r['cmt'];?> (<?php echo $r['cmt_date'].'/'.$r['cmt_place'].'';?>)</p>
 </td>
 <td>
     <p>Số dư: <b class='pull-right'><?php echo number_format($cur_wallet);?>đ</b></p>
     <p>Tổng nạp: <b class='pull-right'><?php echo number_format($total_push);?>đ</b> </p>
 </td>
 <td>
     <p>Số dư: <b class='pull-right'><?php echo number_format($cur_wallet);?>đ</b></p>
     <p>Tổng tích lũy: <b class='pull-right'><?php echo number_format($total_push);?>đ</b> </p>
 </td>
 <td>
     <p><b class='pull-right'><?php echo number_format($total_push);?>đ</b></p>
 </td>
 <td class="text-center">
    <div class="btn-group btn-more-act">
        <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?= ROOTHOST_ADMIN.COMS;?>/edit/<?=$id;?>"><i class='fa fa-edit'></i> Sửa thông tin</a>
            <a class="dropdown-item" href="<?= ROOTHOST_ADMIN.COMS;?>/report/<?=$id;?>"><i class="fa fa-credit-card"></i> Xem doanh số</a>
            <a class="dropdown-item" href="<?= ROOTHOST_ADMIN;?>sales/<?=$id;?>"><i class="fa fa-user"></i> Xem thành viên</a>
            <span class="dropdown-item change_pass" dataid="<?php echo $r['username'];?>"><i class="fa fa-unlock-alt"></i>&nbsp;&nbsp; Đổi Mật khẩu</span>
            <a class="dropdown-item" href="<?= ROOTHOST_ADMIN.COMS;?>/delete/<?=$id;?>"><i class="fa fa-trash-o"></i>&nbsp;&nbsp; Xóa</a>
        </div>
    </div>
</td>
</tr>
<?php } ?>
</table>
<div class='text-center'><?php echo paging($total_rows,50,$page);?></div>
</div>
</div>
<script>
    $('#iskyc').change(function(){
     $('#frm_search').submit();
 })
    $(".btn_confirm_packet").click(function(){
     if(confirm("Bạc chắc chắn xác thực gói nâng cấp?")){
      var user = $(this).attr('dataid');
      var url = "<?php echo ROOTHOST;?>ajaxs/mem/confirm_updatePacket.php";
      $.post(url,{'user':user},function(req){
       console.log(user);
       if(req=='success'){
        window.location.reload();
    }else{
        $('#myModalPopup .modal-title').html('Confirm Packet Error');
        $('#myModalPopup .modal-body').html(req);
        $('#myModalPopup').modal('show');
    }
});
  }
});

    $(".change_pass").click(function(){
     var user = $(this).attr('dataid');
     var url = "<?php echo ROOTHOST_ADMIN;?>ajaxs/member/change_pass.php";
     $('#myModalPopup .modal-body').html('Loading...');
     $('#myModalPopup .modal-title').html('Đổi mật khẩu');
     $.post(url,{'user':user},function(req){
      $('#myModalPopup .modal-body').html(req);
      $('#myModalPopup').modal('show');
  })
 })
    function productStyles(selection) {
        if (!selection.id) { return selection.text; }
        var thumb = $(selection.element).data('thumb');
        var info = $(selection.element).data('info');
        if(!thumb){
            return selection.text;
        } else {
            var $selection = $(
                '<img src="<?php echo ROOTHOST;?>images/' + thumb + '" alt="" class="img-select"><div class="box-info-select2"> <span class="img-changer-text">' + $(selection.element).text() + '</span><span class="img-info-text">' + info + '</span></div>'
                );
            return $selection;
        }
    }
    $('.select').select2({

        allowClear: true
    });

// nạp tiền

$(".btn_push_wallet").click(function(){

    if(confirm("Bạn chắc chắn muốn thực hiện thao tác này?")){
        var flag=true;
        var txtcustomer=$(".txtcustomer_push").val();
        var number_wallet=$(".number_wallet_push").val();
        var secret_code=$(".secret_code_push").val();
        var str="";
        if(txtcustomer=="0"){
            str+="Vui lòng chọn username \n";
            flag=false;
        }
        if(parseFloat(number_wallet)<=0 || number_wallet==""){
            str+="Vui lòng nhập số tiền cần nạp \n";
            flag=false;
        }
        if(secret_code==""){
            str+="Vui lòng nhập secret code \n";
            flag=false;
        }
        if(flag){
            $.post("<?php echo ROOTHOST_ADMIN; ?>ajaxs/wallet/push_wallet_user.php",$(".frm_input_wallet").serialize(),function(data){
                console.log(data);
                if(data.trim()=="success"){
                    alert("Nạp tiền thành công");
                    window.location.href='<?php echo ROOTHOST."sys/member";?>';
                }
                else if(data.trim()=="error_secret") {
                    alert("Secret code không chính xác");
                }else if(data.trim()=="not_exist") {
                    alert("Username ko tồn tại!");
                }
            })
        }else alert(str);
    }
    return false;
});


</script>

<?php //----------------------------------------------?>
