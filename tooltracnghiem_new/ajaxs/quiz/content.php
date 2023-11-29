<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');

$data=isset($_POST['data'])? $_POST['data']:'';
$data_ans=isset($_POST['data_ans'])? $_POST['data_ans']:'';
$time_start=isset($_POST['time_start'])? $_POST['time_start']:'';
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$page=isset($_POST['page'])? (int)$_POST['page']:1;
$quiz_id=$page-1;
$array=$data;

//echo '<pre>';
//var_dump($data);
//echo '</pre>';
$knowledge='';
if(!isset($array[$exam_id])) die('Not Found');
$item=$array[$exam_id];
$time_start=$array['time_start'];
//info câu hỏi
$info_exam=$array['info_exam'];
$title_exam=$info_exam['title'];
$quiz_list=json_decode($info_exam['quiz_list']);
$number_quiz=count($quiz_list);
$time_exam=15;
if(isset($item[$quiz_id])){
	$quession=$item[$quiz_id]['content'];
	$title=$item[$quiz_id]['title'];
	$answer=$item[$quiz_id]['answers'];
	$data_knowledge=$item[$quiz_id]['knowledge'];
	$knowledge=json_decode($data_knowledge,true);
	$str_knowledge=implode('<hr>',$knowledge);
	$arr_answer=json_decode($answer, true);
	$guide=json_encode($item[$quiz_id]['guide']);
	?>
	<div id="main">
		<div class="page-exam">
		<div class="box-item info-test">
			<div class="info-quiz">
				<h3 class="label-title"><?php echo $title_exam;?></h3>
				<div class="group-btn">
					<span class="btn btn1 btn-success" onclick="showGuide('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Hướng dẫn</span>
					<span class="btn btn2 btn-danger" onclick="showDesc('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Lý thuyết</span>
				</div>
				<div class="clearfix"></div>
			</div>
			<ul class="list-inline notic-answer"><li><span class="color red"></span> là đáp án đúng</li><li><span class="color black"></span> gạch chân là đáp án sai</li></ul>
		</div>
		<div class="item-question" id="list_quiz">Hello</div>
		<div class="box-guide" id="box_guide"></div>
		
		<form method="get" class="box-btn">
			<span class="btn btn-default"  onclick="gotopage('<?php echo $number_quiz?>',1)">Prev</span>
			<span class="btn btn-default "  onclick="gotopage('<?php echo $number_quiz?>',2)">Next</span>
			<input type="hidden" id="page_quiz" name="page" value="<?php echo $page;?>">
			
		</form>
		</div>

		<div class="box-action act-ctrl" id="act-ctrl">
			<div class="box-item info-test">
				<h3 class="title-test">Danh sách câu</h3>
			</div>
				<div class="item-row" id="data_frm_quiz">
				<?php
				$stt=0;
				$number=ceil($number_quiz/2);
				foreach($item as $quiz=> $rows){
					$stt++;
					$active='';
					
					$id=$quiz;
					
					$quession=$rows['content'];
					$answer=$rows['answers'];
					$arr_answer=json_decode($answer, true);
					$number_ans=count($arr_answer);
					if(isset($data_ans[$id])) $active='active';
					?>
					<div class="item-col">
					<span class="item-act frm-item-quiz<?php echo $id;?> <?php echo $active;?>" onclick="gotopage(<?php echo $number_quiz;?>,3,<?php echo $stt;?>)">
						<span class="label"><?php echo $stt;?></span>
					</span>
					</div>
					<?php 
					}
					?>
				<div class="clearfix"></div>
			<div class="box-action-footer"></div>
			<div class="btn-rs clearfix" id="btn-submit">
				<span class="btn-send btn-primary btn" type="submit" onclick="getRS()">Hoàn thành nhiệm vụ</span>
			</div>

			</div>

			</div>
		</div>
	</div>	
<div class="box-action-footer"></div>
<?php
}
$time_second=$time_exam*60+2;
$time=time();
$time_end = strtotime("+".$time_second." second", $time_start);
$time_end= date('F j, Y, H:i:s', $time_end);
?>
<script>
var url_root = '<?php echo ROOTHOST_PATH?>';
 var exam_id='<?php echo $exam_id;?>';
  name_json='data3456'+exam_id;
  var data_name_answer=name_json+'answer';
  var isview=name_json+'isview';
function againQuiz(){
	localStorage.removeItem(name_json);
	localStorage.removeItem(data_name_answer);
	localStorage.removeItem(isview);
	window.location.href=url_root;
}

function getRS(){
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	 if(!data_ans)  data_ans = new Object();
	 var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id];
	 number_quiz=item.length;
		num=0;
	 for(var i in item) {
		 var quiz=item[i];
		ans=item[i].answers;
		 var answers=JSON.parse(ans);
		dap_an_dung=get_dap_an_dung(answers);
		//console.log(data_ans);
		if(data_ans[i]){
			user_dapan=data_ans[i];
			if(dap_an_dung==user_dapan) num++;
		}
		 score=(num/number_quiz)*10;
	 }
	 
	// return score;
	 	$('#myModal').modal('show');
	 	$('#myModal .modal-title').html('Kết quả bài test');
		var rs='<div class="box-rs">';
		rs+='<h4>Bạn đã trả lời đúng: '+num+'/'+number_quiz+' câu</h4>';
		rs+='<h5>Số điểm bạn đạt được</h5>';
		rs+='<h1>'+score+'</h1>';
		//rs+='<span class="btn btn-success" onclick="showRsAnswer()">Xem đáp án</span>';
		rs+='</div>';
		$('#content-modal').html(rs);
		localStorage.setItem(isview, '1');
		
}


</script>
