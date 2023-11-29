<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$sday=0;
if(isset($_GET['searchdate']) && $_GET['searchdate']!=''){
	$date = explode("-",$_GET['searchdate']);
	$day=$date[2];
	$month=$date[1];
	$year=$date[0];
	$sday = strtotime($year.'-'.$month.'-'.$day);
}
?>
 <div class=''>
    <div class="com_header color">
        <i class="fa fa-list" aria-hidden="true"></i> Prices histories
    </div>
</div><br>
<div class='col-sm-12' id="list">
	<form method='get' action='' name='frm_list'>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="Header_list">
	  <tr>
		<td><div class='row'><div class='col-sm-6'><div class='input-group'>
			<input type="hidden" name="com" value='<?php echo $_GET['com'];?>'/>
			<input class='form-control' type="date" name="searchdate" id="searchdate" value='<?php echo $year.'-'.$month.'-'.$day;?>'/>
			<span class='input-group-addon' onclick="document.frm_list.submit();">Tìm kiếm</span>
		</div></div></div>
		</td>
        <td align="right">
        </td>
      </tr>
    </table><br/>
	</form>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="table table-bordered  list">
		<tr class="header">
			<th align="center">
				<input type='hidden' id='txt_key'/>
				<input type='date' id='txt_day' class='form-control' placeholder='Day'/>
			</th>
			<th align="center"><input type='text' id='txt_price' class='form-control' placeholder='Price'/></th>
			<th align="center"><input type='text' id='txt_height' class='form-control' placeholder='Height'/></th>
			<th align="center"><input type='text' id='txt_low' class='form-control' placeholder='Low'/></th>
			<th align="center"><input type='text' id='txt_volum' class='form-control' placeholder='Volum'/></th>
			<th align="center"><button type='button' id='cmd_save_price' class='btn btn-primary'>Save</button></th>
		</tr>
		<tr class="header">
			<th align="center">Date</th>
			<th align="center">Price</th>
			<th align="center">Height</th>
			<th align="center">Low</th>
			<th align="center">Volum 24H</th>
			<th align="center"></th>
		</tr>
		<?php
		
		$dataPrice=json_decode(file_get_contents(ROOTHOST.'price.json'),true);
		//var_dump($dataPrice);
		foreach($dataPrice as $key=> $item){
			if($sday>0 && $sday!=(int)$item['cdate']) continue;
			$cdate=date('Y-m-d',$item['cdate']);
			$price=$item['price'];
			$height=$item['height'];
			$low=$item['low'];
			$volum=$item['volum'];
			echo "<tr name='trow' dataday='$cdate' dataprice='$price' dataheight='$height' datalow='$low' datavolum='$volum'>";
			echo "<td align='center'>$cdate</td>";
			echo "<td align='center'>$price</td>";
			echo "<td align='center'>$height</td>";
			echo "<td align='center'>$low</td>";
			echo "<td align='center'>$volum</td>";
			echo "<td align='center'><a href='javascript:void(0);' class='cmd_edit' dataid='$key'>Edit</a></td>";
			echo "</tr>";
		}	?>
    </table>
	<script>
	$('.cmd_edit').click(function(){
		var _item=$(this).parent().parent();
		$('#txt_key').val($(this).attr('dataid'));
		$('#txt_day').val($(_item).attr('dataday'));
		$('#txt_price').val($(_item).attr('dataprice'));
		$('#txt_height').val($(_item).attr('dataheight'));
		$('#txt_low').val($(_item).attr('datalow'));
		$('#txt_volum').val($(_item).attr('datavolum'));
	})
	$('#cmd_save_price').click(function(){
		var _url="<?php echo ROOTHOST;?>process_save_price.php";
		var _data={
			'key':$('#txt_key').val(),
			'day':$('#txt_day').val(),
			'price':$('#txt_price').val(),
			'height':$('#txt_height').val(),
			'low':$('#txt_low').val(),
			'volum':$('#txt_volum').val()
		}
		$.post(_url,_data,function(req){
			if(req=='success'){ alert('Update success!'); window.location.reload();}
			else alert(req);
		})
	})
	</script>
</div>
<?php //----------------------------------------------?>