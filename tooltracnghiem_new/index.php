<?php
session_start();
define('incl_path','../global/libs/');
require_once(incl_path.'gfconfig.php');
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
	<link rel="shortcut icon" href="#" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/style.css?v=1">
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
$time=time();

?>
<div class="container-fluid">
	<div class="box-example">
		<div class="list-data" id="list_data"></div>
	</div>
</div>
<div id="myModal" class="modal  fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
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
 var time='<?php echo $time;?>';
 name_json='data3456'+exam_id;
 var data_name_answer=name_json+'answer';
 var name_isview=name_json+'isview';
 //var isview=JSON.parse(localStorage.getItem(name_isview));
 var isview='';
$(document).ready(function() {
	
	 var page='<?php echo $page;?>';
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	 if(!data_ans)  data_ans = new Object();
	var rs=JSON.parse(localStorage.getItem(name_json));
	//console.log(rs);
	 var quiz='<?php echo $quiz;?>';
	if(!rs){
		$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/save.php', {exam_id}, function(rep){
			if(rep==1) alert('Không lấy đc dữ liệu để lưu');
			else{
				localStorage.setItem(name_json, rep);
				var data=JSON.parse(rep);
				//console.log(data);
				getContentPage(data,data_ans,page, exam_id, quiz);
			}
		});
	}
	else{
		var data=JSON.parse(localStorage.getItem(name_json));
		getContentPage(data,data_ans,page, exam_id, quiz);
	}
	
});

//getResult();
 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
 if(!data_ans)  data_ans = new Object();
var data=JSON.parse(localStorage.getItem(name_json));
	
	
function getContentPage(data,data_ans, page, exam_id, quiz){
	$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/content.php', {data,data_ans,page, exam_id, quiz}, function(rep){
		$('#list_data').html(rep);
		getQuiz(exam_id,quiz);
	});
}

function getQuiz(exam_id, quiz_id, type=''){//type=1 là show có đáp 
	if(type==1){
		var checkbox = document.getElementsByName('answer-'+quiz_id);
		var result = "";
		for (var i = 0; i < checkbox.length; i++){
			if (checkbox[i].checked === true){
				result=1;
				break;
			}
		}
		if(result==''){alert('Vui lòng chọn đáp án trước khi trả lời!'); return;}
		saveAswer(quiz_id);
		$('.frm-item-quiz'+quiz_id).addClass('active');
	}
	 var data_ans=JSON.parse(localStorage.getItem(data_name_answer));
	if(!data_ans)  data_ans = new Object();
	if(data_ans[quiz_id]) {
		var isview=1;
	}else isview='';
	 //var isview=JSON.parse(localStorage.getItem(name_isview));
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	 const answers=JSON.parse(item.answers);
	  title=item['content'];
	  str_exam_id="'"+exam_id+"'";
	  cau=parseInt(quiz_id)+1;
	 var content="<h3 class='cau'>Câu: "+cau+"</h3>";
	 content+="<h4>"+title+"</h4>";
	 j=0;
	 content+='<div class="list-quiz">';
	 for(var i in answers) {
	  if(j==0) label='A';
		else if(j==1) label='B';
		else if(j==2) label='C';
		else label='D';
		checked='';
		view='';
		var ans=answers[i].cnt;
		var istrue=answers[i].is_true;
		//console.log(answers[i]);
		if(data_ans[quiz_id]){
			if(data_ans[quiz_id]==i){
				view='item-false';
				checked='checked';
			}
		}
		if(istrue=='yes' ) view='item-true';
		
		if(isview=='') view='';
		content+= '<div class="item-q item-quiz'+quiz_id+' '+view+'"><input '+checked+' onclick="checkOne(this,'+quiz_id+')" type="checkbox" value="'+i+'" id="answer-'+i+'" name="answer-'+quiz_id+'" class="item_ans_'+quiz_id+'"><span class=\"label-asw\">'+label+': </span><span>'+ans+'</span></div>';
		
		
		 j++;
	}
	
	 content+='</div>';
	 content+='<div id="rep_guide"></div>';
	content+= '<div class="gr-submit"><span class="btn-send btn-primary btn" onclick="getQuiz('+str_exam_id+', '+quiz_id+',1)">Trả lời</span></div>';
	$('#list_quiz').html(content).text();
	
	
	
}
function gotopage(total_quiz,type='', page=''){
	//$('.item-act').removeClass('active');
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
	//$(".frm-item-quiz"+quiz).addClass('active');
	
	 if (history.pushState) {
		history.pushState(null, null, url_root + '?page=' + page);
	 }

	return false;
	
}

function saveAswer(quiz){
	
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
	  
}
function get_dap_an_dung(answers){
	 for(var j in answers) {
		var istrue=answers[j].is_true;
		
		if(istrue==='yes') return j;
	}
	return '';
}
function showGuide(exam_id, quiz_id){
	$('#rep_guide').addClass("show-guide");
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	var guide = item['guide'];
	$('#rep_guide').html(guide);
}
function showDesc(exam_id, quiz_id){
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	var knowledge =JSON.parse(item['knowledge']);
	$('#myModal').modal('show');
	$('#myModal .modal-title').html('Lý thuyết');
	var rs='<div class="box-lythuyet">';
	rs+=knowledge;
	rs+='</div>';
	$('#content-modal').html(rs);
}
let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);
</script>
</html>