<?php
session_start();

define('incl_path','../global/libs/');
define('libs_path','../libs/');
define('com_path','components/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');

header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
$req=isset($_GET['req'])?antiData($_GET['req']):'';
$req=str_replace(' ','%2B',$req);
if($req!='') setcookie('RES_USER',$req,time() + (86400 * 30), "/");
define('ISHOME',true);
$page=isset($_GET['page'])? $_GET['page']:1;
?>
<!DOCTYPE html>
<html lang='vi'>
<head profile="http://www.w3.org/2005/10/profile">
	<meta charset="utf-8">
	<meta name="google" content="notranslate" />
	<meta http-equiv="Content-Language" content="vi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="referrer" content="no-referrer" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php echo SITE_NAME;?></title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic">
	<link rel="shortcut icon" href="#" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>style.css">
    <link rel="stylesheet" href="<?php echo ROOTHOST_PATH; ?>global/css/select.css?v=2">
	<script src='<?php echo ROOTHOST_PATH;?>global/js/jquery-1.11.2.min.js'></script>
    <script src="<?php echo ROOTHOST_PATH;?>global/js/bootstrap.min.js"></script>
</head>
<body >
<?php 
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$exam_id='K12_M01_1';
$page=isset($_GET['page'])? (int)$_GET['page']:1;
$quiz=$page-1;
$data=array();
?>
<div class="container-fluid">
	<div class="box-example">
		<div class="list-data" id="list_data"></div>
	</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content" id="content-modal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" id="content-modal"></div>
		</div>
	</div>
</div>
<script>
 var exam_id='<?php echo $exam_id;?>';
 name_json='data91'+exam_id;
 var data_name_answer='name_dat91';
$(document).ready(function() {
	 var page='<?php echo $page;?>';
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	 if(!data_ans)  data_ans = new Object();
	var rs=JSON.parse(localStorage.getItem(name_json));
	console.log(rs);
	 var quiz='<?php echo $quiz;?>';
	if(!rs){
		$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/save.php', {exam_id}, function(rep){
			//var js_array =<?php echo json_encode($data);?>;
			localStorage.setItem(name_json, rep);
			var data=JSON.parse(rep);
			$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/content.php', {data,data_ans,page, exam_id, quiz}, function(rep){
				$('#list_data').html(rep);
				getQuiz(exam_id,quiz);
			});
		});
	}
	else{
		var data=JSON.parse(localStorage.getItem(name_json));
		$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/content.php', {data,data_ans,page, exam_id, quiz}, function(rep){
			$('#list_data').html(rep);
			getQuiz(exam_id,quiz);
		});
	}
	
});
//getResult();

function getQuiz(exam_id, quiz_id){
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	  if(!data_ans)  data_ans = new Object();
	 console.log(data_ans);
	//alert(quiz_id);
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	 const answers=JSON.parse(item.answers);
	  title=item['content'];
	 var content="<h4>"+title+"</h4>";
	 j=0;
	 content+='<div class="list-quiz">';
	 for(var i in answers) {
		
		  if(j==0) label='A';
			else if(j==1) label='B';
			else if(j==2) label='C';
			else label='D';
			checked='';
		
		var ans=answers[i].cnt;
		//console.log(data_ans[j]);
		if(data_ans[quiz_id]){
			if(data_ans[quiz_id]==i) checked='checked';
			
		}
		content+= '<div class="item-q item-quiz'+quiz_id+'"><input '+checked+' onclick="checkOne(this,'+quiz_id+')" type="checkbox" value="'+i+'" id="answer-'+i+'" name="answer-'+quiz_id+'" class="item_ans_'+quiz_id+'"><span class=\"label-asw\">'+label+': </span><span>'+ans+'</span></div>';
		 j++;
	}
	
	 content+='</div>';

	$('#list_quiz').html(content).text();
	
}
function gotopage(total_quiz,type='', page=''){
	$('.item-act').removeClass('active');
	var url_root = '<?php echo ROOTHOST_PATH?>';
	if(page==''){// for next prev
		var page=$("#page_quiz").val();
	}
	page=parseInt(page);
	total_quiz=parseInt(total_quiz);
	if(type==1){
		page=page-1;
		quiz=page-1;
	} 
	else if(type==2){
		quiz=page;
		page=page+1;
	}else quiz=page-1;
	
	if(page<1) return;
	if(page>total_quiz) return;
	 var exam_id='<?php echo $exam_id;?>';
	
	getQuiz(exam_id,quiz);
	$("#page_quiz").val(page);
	$(".frm-item-quiz"+quiz).addClass('active');
	
	 if (history.pushState) {
		history.pushState(null, null, url_root + '?page=' + page);
	 }

	return false;
	
}

function saveAswer(quiz){
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	 if(!data_ans)  data_ans = new Object();
	 var name_input='answer-'+quiz;
	 var checked = $('input[name="'+name_input+'"]:checked' ).val();
	  data_ans[quiz]=checked;
	 var data=JSON.stringify(data_ans);
	 localStorage.setItem(data_name_answer, data);
}
function fomat_checbox(name_input){
	var myCheckbox = document.getElementsByName(name_input);
	  Array.prototype.forEach.call(myCheckbox,function(el){
		el.checked = false;
	  });
	 
}
function checkOne(id, quiz){
	name_input='answer-'+quiz;
	class_frm_box='frm-item-quiz'+quiz;
	class_box='item-quiz'+quiz;
	fomat_checbox(name_input);
   id.checked = true;
	var checked = $('.'+class_box+' input[name="'+name_input+'"]:checked' ).val();
	 id_input='frm-answer-'+checked;
	 $('.'+class_frm_box+' #'+id_input).prop('checked', true);
	  saveAswer(quiz);
}
function checkFrm(id, quiz, total_quiz){
	gotopage(total_quiz,3,quiz+1);
	name_input='answer-'+quiz;
	class_frm_box='frm-item-quiz'+quiz;
	class_box='item-quiz'+quiz;
	fomat_checbox(name_input);
	id.checked = true;
	var checked =  $('.'+class_frm_box+' input[name="'+name_input+'"]:checked' ).val();
	//id_input='answer-'+checked;
	//alert(id_input);
	id_input='answer-'+checked;
	 $('#'+id_input).prop('checked', true);
	  saveAswer(quiz);
}
function get_dap_an_dung(answers){
	 for(var j in answers) {
		var istrue=answers[j].is_true;
		
		if(istrue==='yes') return j;
	}
	return '';
}
function getRS(){
	//var data_name_answer='name_data2';
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	 if(!data_ans)  data_ans = new Object();
	 var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id];
	 number_quiz=item.length;
		score=0;
	 for(var i in item) {
		 var quiz=item[i];
		ans=item[i].answers;
		 var answers=JSON.parse(ans);
		dap_an_dung=get_dap_an_dung(answers);
		//console.log(data_ans);
		if(data_ans[i]){
			user_dapan=data_ans[i];
			if(dap_an_dung==user_dapan) score++;
		}
		 
	 }
	 //console.log(score);
	 return score;
	 	$('#myModal').modal('show');
	 	$('#myModal .modal-title').html('Kết quả bài thi');
		$('#content-modal').html(response_data);
}

</script>
</html>