<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
$er_mess='';
$this_user=getInfo('username');
if(isset($_POST['cmdsave'])){
    $arr2['username']=$arr['username']=addslashes($_POST['txt_user']);
    $arr2['money']=$arr['money']=(int)$_POST['txt_amount'];
    $arr2['cuser']='root_admin';
    $type=(int)$_POST['txttype'];
    if($type==1) $note="Kích hoạt tài khoản";
    elseif($type==2) $note="Gia hạn dịch vụ";
    else $note="Liên kết dịch vụ";
    $arr2['type']=$type;
    $arr2['note']=$note;
    $arr2['status']=$arr['status']=1;
    $arr2['cdate']=$arr['cdate']=time();
    SysAdd('tbl_wallet_s',$arr);
    SysAdd('tbl_wallet_s_histories',$arr2);
}
?>

<div id="action">
    <script language="javascript">
        function checkinput(){
            if($('#chk_user').val()=="0") {
                alert("Tên đăng nhập đã có trong hệ thống. Vui lòng nhập tên khác");
                $('#txt_username').focus();
                return false;
            }
            return true;
        }
    </script>
<div class="group-box">
    <div class="box-card">
        <div class="action-tool">
            <h2 class="label-title">Thêm mới tài khoản</h2>
            <?php require_once('modules/toolbar.php');?>
        </div>
        <div class="clearfix"></div>
        <form id="frm_action" name="frm_action" method="post" action="">
            <?php echo $er_mess;?>
            <div class="form-group">
                <label for="" class="col-sm-3 form-control-label">Số tiền*</label>
                <div class="col-sm-9">
                    <input type="text" name="txt_amount" class="form-control" id="txt_amount" placeholder="" required="true">
                    <input type="hidden" name="chk_user" id="chk_user" value=""/>
                    <span id="username_result"></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 form-control-label">User*</label>
                <div class="col-sm-9">
                    <select name="txt_user" class="form-control select txt_user" data-placeholder="Chọn User">
                        <option value="0">Tất cả</option>
                        <?php
                        $arr=SysGetList('tbl_member', array('fullname','username','email'), " ORDER BY cdate DESC");
                        foreach($arr as $row){
                            $fullname=$row['fullname'];
                            $user=$row['username'];
                            $select='';
                            ?>
                            <option value="<?php echo $user;?>" data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 form-control-label">Type*</label>
                <div class="col-sm-9">
                  <select name="txttype" class="form-control">
                      <option value="1">Kích Hoạt</option>
                      <option value="2">Gia hạn</option>
                      <option value="3">Liên kết</option>
                  </select>
                </div>
                <div class="clearfix"></div>
            </div>
        <input type="submit" name="cmdsave" id="cmdsave" value="Submit" style="display:none;">
    </form>
    </div>
</div>
</div>