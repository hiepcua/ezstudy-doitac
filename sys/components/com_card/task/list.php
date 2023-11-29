<?php
	defined('ISHOME') or die("Can't acess this page, please come back!");
	$action=''; $strwhere = '';
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	if(isset($_POST['searchdate']) && $_POST['searchdate']!=''){
		$date = explode("-",$_POST['searchdate']);
		$day=$date[2];
		$month=$date[1];
		$year=$date[0];
		$from = mktime(0,0,0,$month,$day,$year);
		$to = mktime(23,59,59,$month,$day,$year);
		$strwhere.=" `tbl_card_transection`.`cdate`>='$from' AND `tbl_card_transection`.`cdate`<='$to' AND";
	}
	if(isset($_POST['cbo_active'])){
		$action=$_POST['cbo_active'];
	}
	if($action!="" && $action!="all" )
		$strwhere.=" `status` = '$action' AND";
	if($strwhere!='')
		$strwhere=" WHERE ".substr($strwhere,0,strlen($strwhere)-4);
	
	$sql="SELECT tbl_card.*,tbl_card_transection.cdate as udate FROM `tbl_card` LEFT JOIN tbl_card_transection ON tbl_card.cardcode=tbl_card_transection.cardcode $strwhere ORDER BY tbl_card.`status` ASC,tbl_card_transection.`cdate` DESC";
	$objmysql=new CLS_MYSQL;
	$objmysql->Query($sql);
	$total_rows=$objmysql->Num_rows();
		
	if(!isset($_SESSION['CUR_PAGE_CARD'])){
		$_SESSION['CUR_PAGE_CARD']=1;
	}
	if(isset($_POST['txtCurnpage'])){
		$_SESSION['CUR_PAGE_CARD']=$_POST['txtCurnpage'];
	}
	if($_SESSION['CUR_PAGE_CARD']>ceil($total_rows/MAX_ROWS)){
		$_SESSION['CUR_PAGE_CARD']=ceil($total_rows/MAX_ROWS);
	}
	if($_SESSION['CUR_PAGE_CARD']<=0) $_SESSION['CUR_PAGE_CARD']=1;
	$cur_page=$_SESSION['CUR_PAGE_CARD'];
?>
 <div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Danh sách thẻ
        <div class="pull-right">
            <div id="menus" class="toolbars">
                <form id="frm_menu" name="frm_menu" method="post" action="">
                    <input type="hidden" name="txtorders" id="txtorders" />
                    <input type="hidden" name="txtids" id="txtids" />
                    <input type="hidden" name="txtaction" id="txtaction" />
                    <ul class="list-inline">
                        <li><a class="addnew btn btn-default" href="<?php echo ROOTHOST_ADMIN;?>?com=card&task=add" title="Thêm mới"><i class="fa fa-plus-circle cgreen" aria-hidden="true"></i> Thêm mới</a></li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div><br>
<div class='col-sm-12' id="list">
  <form id="frm_list" name="frm_list" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="Header_list">
	  <tr>
		<td><div class='row'><div class='col-sm-6'><div class='input-group'>
			<input class='form-control' type="date" name="searchdate" id="searchdate"/>
			<span class='input-group-addon' onclick="document.frm_list.submit();">Tìm kiếm</span>
		</div></div></div>
		</td>
        <td align="right">
		<div class='row'><div class='col-sm-6'></div>
		<div class='col-sm-6'><div class='input-group'>
			<span class='input-group-addon'>Status:</span>
          <select class='form-control' name="cbo_active" id="cbo_active" onchange="document.frm_list.submit();">
            <option value="">Tất cả</option>
            <option value="1">Đã sử dụng</option>
            <option value="0">Chưa sử dụng</option>
            <option value="-1">Đã hủy</option>
            <script language="javascript">
			cbo_Selected('cbo_active','<?php echo $action;?>');
            </script>
          </select>
		  </div></div>
        </div></div>
        </td>
      </tr>
    </table><br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="table table-bordered  list">
		<tr class="header">
			<td width="30" align="center"><input type="checkbox" name="chkall" id="chkall" value="" onclick="docheckall('chk',this.checked);" /></td>
			<td align="center">STT</td>
			<td align="center">Mã PIN</td>
			<td width="100" align="center">Gói (PIT)</td>
			<td width="150" align="center">Ngày Tạo</td>
			<td width="100" align="center">Người tạo mã</td>
			<td width="100" align="center">Tình trạng</td>
			<td width="150" align="center">Ngày sử dụng</td>
			<td width="100" align="center">Sử dụng bởi</td>
		</tr>
		<?php
		global $PACKET_LIST;
		$star=($cur_page-1)*MAX_ROWS;
		$sql=$sql." LIMIT $star,".MAX_ROWS;
		$objmysql->Query($sql);
		$stt=$star+1;
		while($r=$objmysql->Fetch_Assoc()){
			$id=$r['cardcode'];
			$packet=$r['packet'];
			$cdate=date('H:i:s d/m/Y',$r['cdate']);
			$udate='';
			if($r['udate']!='')
			$udate=date('H:i:s d/m/Y',$r['udate']);
			$author=$r['author'];
			$status=$r['status']==0?"Chưa sử dụng":($r['status']==1?"Đã sử dụng":"Đã hủy");
			$member = $obj->getMemberUsed($id);
			
			echo "<tr name='trow'>";
			echo "<td width='30' align='center'><label>";
			echo "<input type='checkbox' name='chk' id='chk' onclick='docheckonce(\"chk\");' value='$id' />";
			echo "</label></td>";
			echo "<td align='center'>$stt</td>";
			echo "<td align='center'>$id</td>";
			echo "<td align='center'>".number_format($packet)."</td>";
			echo "<td align='center'>$cdate</td>";
			echo "<td align='center'>$author</td>";
			echo "<td align='center'>$status</td>";
			echo "<td align='center'>$udate</td>";
			echo "<td align='center'>$member</td>";
			echo "</tr>";
			$stt++;
		}	?>
    </table>
  </form>
</div>
<?php //----------------------------------------------?>