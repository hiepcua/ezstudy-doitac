<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
$data=isset($_POST['data'])? $_POST['data']:'';
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$quiz_id=isset($_POST['quiz_id'])? $_POST['quiz_id']:0;

echo $quiz_id;
$array=json_decode($data,true);
if(!isset($array[$exam_id])) die('Not Found');
$item=$array[$exam_id];
if(isset($item[$quiz_id])){
	$quession=$item[$quiz_id]['content'];
	$answer=$item[$quiz_id]['answers'];
	$arr_answer=json_decode($answer, true);
	$number_ques=10;
	?>
    <h4><?php echo $quession;?></h4>
	<div class="info-answer">
		<div class="row row-fullheight">
		<?php $i=0;
		foreach($arr_answer as $key=>$row){
			$i++;
			if($i==1) $label='A';
			elseif($i==2) $label='B';
			elseif($i==3) $label='C';
			else $label='D';
			$name=$row['cnt'];
			?>
			<div class="col-md-6 left-side">
				<div class="item-q">
					<input value="<?php echo $key;?>" name="answer-<?php echo $quiz_id;?>" type="checkbox" onclick="checkOne(this,'answer-<?php echo $quiz_id;?>','<?php echo $quiz_id;?>')" >
					<span class="label-asw"><?php echo $label;?>: </span><span><?php echo $name;?></span>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
	
<?php
}
//while($row=$array->Fetch_Assoc()){

?><script>
 
function checkOne(id, name_input, quiz_id){
  var myCheckbox = document.getElementsByName(name_input);
  Array.prototype.forEach.call(myCheckbox,function(el){
    el.checked = false;
  });
   var exam_id='<?php echo $exam_id;?>';
	name_json='data_json1'+exam_id;
  var data=JSON.parse(localStorage.getItem(name_json));
 // data_ansewer[]={"quiz_id": id};
  console.log(data['data_answer']);
  id.checked = true;
}
</script>
