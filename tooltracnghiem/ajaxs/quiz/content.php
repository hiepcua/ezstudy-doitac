<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
$is_view=isset($_POST['isview'])? (int)$_POST['isview']:0;//1 la xem lai dap an
$data=isset($_POST['data'])? $_POST['data']:'';
$data_ans=isset($_POST['data_ans'])? $_POST['data_ans']:'';
$time_start=isset($_POST['time_start'])? $_POST['time_start']:'';
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$page=isset($_POST['page'])? (int)$_POST['page']:1;
$quiz_id=$page-1;
$array=$data;

//echo '<pre>';
//var_dump($data_ans);
//echo '</pre>';

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
	$answer=$item[$quiz_id]['answers'];
	$arr_answer=json_decode($answer, true);
	?>
	<div id="main">
	<div class="page-exam">
	<div class="box-item info-test">
			<h3 class="title-test"><?php echo $title_exam;?></h3>
			<ul class="list-inline">
				<li>Số câu: <?php echo $number_quiz;?></li>
				<li>Thời gian: <?php echo $time_exam;?></li>
			</ul>
			<div class="clearfix"></div>
		<?php if($is_view==1) echo '<ul class="list-inline notic-answer"><li><span class="color red"></span> là đáp án đúng</li><li><span class="color black"></span> gạch chân là đáp án sai</li></ul>';?>
	</div>
	<div class="item-question" id="list_quiz">Hello</div>
	<form method="get" class="box-btn">
		<span class="btn btn-default"  onclick="gotopage('<?php echo $number_quiz?>',1)">Prew</span>
		<span class="btn btn-default "  onclick="gotopage('<?php echo $number_quiz?>',2)">Next</span>
		<input type="hidden" id="page_quiz" name="page" value="<?php echo $page;?>">
		
	</form>
</div>
	
	<div class="box-action act-ctrl" id="act-ctrl">
		<?php if($is_view==0) echo '<div class="box-countdown">Còn lại: <span id="countdown"></span></div>';?>
		<div class="row">
		<div class="item" id="data_frm_quiz">
			<?php
			$stt=0;
			$number=ceil($number_quiz/2);
			foreach($item as $quiz=> $rows){
				$stt++;
				$active='';
				if($page==$stt) $active='active';
				$id=$quiz;
				
				$quession=$rows['content'];
				$answer=$rows['answers'];
				$arr_answer=json_decode($answer, true);
				$number_ans=count($arr_answer);
				if($is_view==0){
				if ($stt % $number == 1) echo '<div class="col-md-6">';
				//onclick='gotopage(number_quiz,3,stt';
				
				?>
				
				<div class="item-act frm-item-quiz<?php echo $id;?> <?php echo $active;?>" onclick="gotopage(<?php echo $number_quiz;?>,3,<?php echo $stt;?>)">
					<span class="label"><?php echo $stt;?></span>
					<?php
					
					$i=0;
					foreach($arr_answer as $key=>$row){
						if($i==0) $label='A';
						elseif($i==1) $label='B';
						elseif($i==2) $label='C';
						else $label='D';
						$name=$row['cnt'];
						$checked='';
						if(isset($data_ans[$id]) && $data_ans[$id]==$key){
							$checked='checked';
						}
						$i++;

						?>
						<input <?php echo $checked;?> id="frm-answer-<?php echo $key;?>" type="checkbox" value="<?php echo $key;?>" name="answer-<?php echo $id;?>" onclick="checkFrm(this,<?php echo $id;?>,<?php echo $number_ans;?>)">
					<?php
					}
					
					?>
				</div>
			<?php if ($stt % $number == 0 || $stt == $number) echo '</div>';
			}
			}
			?>
			<div class="clearfix"></div>
		<div class="btn-rs clearfix" id="btn-submit">
		<?php if($is_view==0){?>
			<span class="btn-send btn-primary btn" type="submit" onclick="getRS()">Nộp bài</span>
		<?php } else{?>
			<span class="btn-send btn-primary btn" type="submit" onclick="againQuiz()">Làm lại bài này</span>
			<span class="btn-send btn-default btn" type="submit" onclick="againQuiz()">Đóng</span>
		<?php }?>
		</div>

	</div>
	</div>
</div>	
		
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
  name_json='data66'+exam_id;
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
		rs+='<span class="btn btn-success" onclick="showRsAnswer()">Xem đáp án</span>';
		rs+='</div>';
		$('#content-modal').html(rs);
		localStorage.setItem(isview, '1');
		
}
var is_view='<?php echo $is_view;?>';
 // Set the date we're counting down to
 if(is_view==0){
    var end_time='<?php echo $time_end;?>';
    var countDownDate = new Date(end_time).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("countdown").innerHTML = minutes + "Phút " + seconds + "Giây";
        if (distance <= 0) {
            clearInterval(x);
            getRS();
            document.getElementById("countdown").innerHTML = "Hết giờ";
        }
    }, 1000);
 }

</script>
