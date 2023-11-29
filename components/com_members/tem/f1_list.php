<?php 
$objmem = new CLS_MEMBER;
$user = $objmem->getInfo('username');
$uid = '';
if(isset($_GET['uid']) && $_GET['uid']!='') $uid = $_GET['uid'];
$item = $objmem->getRootView($user,$uid);
$code=$item['code'];
if($uid==0 || $uid=='') $uid=$item['code'];
$dsA=$objmem->getDSNhom($code,0);
$dsB=$objmem->getDSNhom($code,1);
?>
<link rel="stylesheet" href="<?php echo ROOTHOST.THIS_TEM_PATH; ?>chart/style.css">
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
<h4 class=''>Nhánh trái: <?php echo number_format($dsA,2);?> PIT</h4>
<h4 class=''>Nhánh phải: <?php echo number_format($dsB,2);?> PIT</h4>
<hr/>
<div class="page-member">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tree">Đại lý của tôi</a></li>
		<li><a data-toggle="tab" href="#bonus">Sơ đồ đại lý</a></li>
    </ul>
    <div class="tab-content" style='padding:11px; background:#fff;'>
		<div id="bonus" class="tab-pane fade in table-responsive">
			<div class="group-item"><div class="box-chart">
				<?php
				function getListChart($user,$code,$level){
					if($code=='root') return;
					$sql="SELECT * FROM tbl_accounts WHERE  par_user='$code'";
					$objdata=new CLS_MYSQL();
					$objdata->Query($sql);
					$class='';
					if($level!=0) $class='child';
					$char="";
					$str='<ul class="">';
					while($rows=$objdata->Fetch_Assoc()){
						$urllink="";
						$str.="<li $class><a href=\"$urllink\" title='".$rows['name']."'><span><i class='fa fa-user'></i> ".$rows["name"]."</span></a>";
						$nextlevel=$level+1;
						$par_code=$rows['code'];
						$str.=getListChart($user,$par_code,$nextlevel);
						$str.='</li>';
					}
					$str.='</ul>';
					return $str;
				}
				echo "<div style='font-size:16px; margin-top:15px; font-weight: bold'><i class='fa fa-user'></i> ".$user."</div>";
				echo getListChart('root',$code,0);
				?>
			</div></div>
		</div>
		<div id="tree" class="tab-pane fade in active">
			<div class="group-item">
				<table class="table table-hover">
				<thead><tr>
					<th>Ngày</th>
					<th>Username</th>
					<th>Mã code</th>
					<th>Cấp đại lý</th>
				</tr></thead>
				<tbody>
				<?php 
				global $_PACKET;
				$obj=new CLS_MYSQL;
				$sql="SELECT * FROM tbl_accounts WHERE par_user='$code' ORDER BY cdate DESC";
				$obj->Query($sql);
				if($obj->Num_rows()>0){
				while($r = $obj->Fetch_Assoc()) {
				$cdate=date('Y-m-d H:i:s',$r['cdate']);
				?>
				<tr>
					<td><?php echo $cdate;?></td>
					<td><?php echo $r['username'];?></td>
					<td><?php echo $r['code'];?></td>
					<td><?php echo $_PACKET['P'.$r['packet']]['name'];?></td>
				</tr>
				<?php } 
				}else{
				?>
				<tr>
					<td colspan='4' class='text-center'>Không có đại lý nào</td>
				</tr>
				<?php }?>
				</tbody>
			</table>
			</div>
		</div>
    </div>
</div>
<style>
.box-chart{}
.box-chart .item .fa{
	color: #888;
}
.thumb-per{
	display: block;
}
.row:before {display: table; content: " ";}
img { vertical-align: middle;}
</style>