<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$get_type=isset($_GET['type'])? (int)$_GET['type']:'';

if($get_type==1) $title_page="Lịch sử Nạp điểm (Ví B)";
elseif($get_type==2) $title_page="Lịch sử Chuyển điểm (Ví B)";
elseif($get_type==3) $title_page="Lịch sử Kích hoạt (Ví B)";
else $title_page="Lịch sử Ví B";
$type_action=1;
include_once('includes/toolbars.php');
    $strwhere=" ";
    define('OBJ','WALLET_B_HISTORY');
   
    $fromdate=isset($_POST['txt_fromdate']) ? addslashes($_POST['txt_fromdate']):'';
    $todate=isset($_POST['txt_todate']) ? addslashes($_POST['txt_todate']):'';
    $filter_username=isset($_POST['sl_username']) ? addslashes($_POST['sl_username']):'0';
    $type=isset($_POST['sl_type']) ? addslashes($_POST['sl_type']):'';
   
    if($fromdate!='') $strwhere.=" AND `cdate` >= '$fromdate'";
    if($todate!='')  $strwhere.=" AND `cdate` <= '$todate'";
   
	$total_all='';
	if($get_type!='') $type=$get_type;
    if($type!=''){
		$strwhere.=" AND `type`='$type'"; //search
       
	}

    if($filter_username!="0"){
        $strwhere.=" AND `username`='$filter_username' ";
    }
	 $table='tbl_wallet_b_histories';
	$total_all=getReportWallet($table,$type, $strwhere);
    $total_rows=SysCount('tbl_wallet_b_histories',$strwhere);
    $max_rows = 40;
	$cur_page=isset($_POST['txtCurnpage'])? $_POST['txtCurnpage']:1;
    $start=($cur_page-1)*$max_rows;
    $obj_user_wallet=SysGetList('tbl_wallet_b_histories',array()," $strwhere ORDER BY id DESC LIMIT $start,$max_rows", false);
    ?>
    <div class=" user_wallet">
        <div class=" box_account">
            <div class="box2">
                <div class="header-search">
                    <form class="frm_wallet" name="frm_wallet" method="post" action="#">
                        <div class=" " style="margin-bottom:15px;">
                            <div class=" col-md-2">
                                    <label class="mr-sm-2" for="fromdate">Tài khoản</label>
                                    <select name="sl_username" id="sl_username" class="form-control select sl_username"  style="width: 100%">
                                        <option value="0">Tất cả</option>
                                        <?php
                                        $arr=SysGetList('tbl_member', array('fullname','username','email'), " ORDER BY cdate DESC");
                                        foreach($arr as $row){
                                            $fullname=$row['fullname'];
                                            $user=$row['username'];
											$select='';
                                            if($filter_username==$user) $select='selected';
                                            ?>
                                            <option value="<?php echo $user;?>" <?php echo $select;?> data-thumb="avatar_default.png" data-info="<?php echo $user;?>"><?php echo $fullname;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $("#sl_username").select2();
                                            cbo_Selected("sl_username","<?php echo $filter_username; ?>");

                                        });
                                    </script>
                            </div>
                            <?php if($get_type==''){?>
                                <div class=" col-md-2">
                                    <div class="input-group input-time">
                                        <label class="mr-sm-2" for="fromdate">Loại giao dịch</label>
                                        <select name="sl_type" id="sl_type"  class="form-control sl_status">
                                            <option value="">-- Tất cả --</option>
                                            <option value="1">Nạp điểm</option>
                                            <option value="2">Chuyển điểm</option>
                                            <option value="3">Kích hoạt</option>
                                        </select>
                                        <script type="text/javascript">
                                            cbo_Selected("sl_type","<?php echo $type; ?>");
                                        </script>
                                    </div>
                                </div>
                            <?php }?>

                            <div class="col-md-2">
                                <div class="input-time">
                                    <label class="mr-sm-2" for="fromdate">Từ ngày</label>
                                    <input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="fromdate" name="txt_fromdate" placeholder="Từ ngày" value="<?php echo $fromdate;?>"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-time">
                                    <label class="mr-sm-2" for="todate">Đến ngày</label>
                                    <input type="date" class="mb-2 mr-sm-2 mb-sm-0 form-control" id="todate" name="txt_todate" placeholder="Đến ngày" value="<?php echo $todate;?>"/>
                                </div>
                            </div>
                            <label class="mr-sm-2" for="todate"></label>
                            <button type="submit" class="btn btn-primary btn-search">Tìm kiếm</button>
							<span type="button" class="btn btn-default btn-search reset_form"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</span>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
            <?php if($type!=''){?>
         
            <div class="td-price" style="padding: 10px 0px 0px 10px; font-size: 14px; font-weight: bold; color: #ff0000">Total all: <?php echo number_format($total_all,0,",","."); ?>đ</div>
            <?php }?>
            <div class="panel-body">
                <table class="table table-bordered table tbl-main">
                    <thead>
                    <tr>
                        <th>Tài khoản</th>
                        <th>Nội dung</th>
                        <th class="text-left">Loại</th>
                        <th>Thời gian</th>
                        <th class="text-right">Số điểm</th>
                        <th class="text-right">Còn lại</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_quanty=$total_price=0;
                    if($total_rows>0){
                        $i=0;
                        while ($row=$obj_user_wallet->Fetch_Assoc()) {
                            $i++;
                            $id=$row['id'];
                            $username=$row['username'];
                            $money=$row['money'];
                            $cur_wallet=getCurrentWallet('tbl_wallet_b',$username);
                            $cur_wallet=$cur_wallet+$money;
                            $total_price+=$money;
                            $label=$money>0? "+":"";
                            $status=$row['note'];
                            $type=$row['type'];
                            if($type==1) $label_type='Nạp điểm';
                            elseif($type==2) $label_type='Chuyển điểm';
                            else $label_type='Gia hạn';
                            ?>
                            <tr>
                                <td><?php echo $username;?></td>
                                <td><?php echo $status;?></td>
                                <td><?php echo $label_type;?></td>
                                <td><?php echo date("d-m-Y H:i:s",$row['cdate']);?></td>
                                <td class="text-right"><b><?php echo $label.number_format($money,0,",","."); ?>đ</b></td>
                                <td class="text-right"><b><?php echo number_format($cur_wallet,0,",","."); ?>đ</b></td>

                            </tr>
                        <?php
                        }
                        ?>
                      <tr class="total_tr">
                            <td colspan='4' class="text-right" style="color: #333">Tổng:</td>
                            <td class="td-price text-right"><?php echo number_format($total_price,0,",","."); ?>đ</td>
                            <td></td>
                        </tr>
                    <?php
                    }
                    else echo "<tr><td colspan='6' align='center'>Dữ liệu trống</td></tr>";
                    ?>

                    </tbody>
                </table>

                </form>
		 <?php if($type!='') {?>
			<table class="table-total table">
				<tr class="total_tr">
					<td class="td-price text-center">Total All: <?php echo number_format($total_all,0,",","."); ?>đ</td>
				</tr>
			</table>
			<?php } ?>
                <div class='text-center'><?php echo paging($total_rows,50,$cur_page);?></div>
            </div>
        </div>
    </div>
   
<div class="clearfix"></div>
