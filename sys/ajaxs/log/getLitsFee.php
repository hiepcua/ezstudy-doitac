<?php
session_start();
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gffunc.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../libs/cls.mysql.php");
require_once("../../libs/cls.user.php");
$objuser=new CLS_USER;
$objmysql = new CLS_MYSQL;
if(!$objuser->isLogin()) die("E01");

$mc_id=isset($_GET['mc_id'])?(int)$_GET['mc_id']:0;

$sql="SELECT * FROM tbl_log_fee WHERE member_course_id=".$mc_id;
$objmysql->Query($sql);
$total_rows = $objmysql->Num_rows();
if($total_rows>0){
	echo '
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>STT</th>
				<th>Học phí</th>
				<th>Chú thích</th>
				<th>Ngày nộp</th>
				<th>#</th>
			</tr>
		</thead>
		<tbody>';
			$i=0;
			while ($row=$objmysql->Fetch_Assoc()) {
				$i++;
				echo '<td width="30">'.$i.'</td>';
				echo '<td>'.number_format($row['money']).'</td>';
				echo '<td>'.$row['note'].'</td>';
				echo '<td>'.date('d-m-Y H:i:sa',$row['cdate']).'</td>';
				echo '<td width="30"><i class="fa fa-times cred" aria-hidden="true" dataid="'.$row['id'].'" onclick="del_fee(this);"></i></td>';
				echo '</tr>';
			}
			echo '
		</tbody>
	</table>';
}else{}?>
