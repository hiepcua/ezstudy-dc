<?php
session_start();
define('incl_path','global/libs/');
require_once(incl_path.'config-tool.php');
header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
$req=isset($_GET['req'])?antiData($_GET['req']):'';
$req=str_replace(' ','%2B',$req);
if($req!='') setcookie('RES_USER',$req,time() + (86400 * 30), "/");
define('ISHOME',true);
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
	<title>Example</title>
	<link rel="shortcut icon" href="#" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/style.css?v=1">
	<script src='<?php echo ROOTHOST_PATH;?>global/js/jquery-1.11.2.min.js'></script>
    <script src="<?php echo ROOTHOST_PATH;?>global/js/bootstrap.min.js"></script>
	<script src="https://cdn.ckeditor.com/4.16.1/standard-all/ckeditor.js"></script>
	<script src="<?php echo ROOTHOST_PATH;?>global/js/editorScript.js"></script>
	<script type="text/x-mathjax-config">
			  MathJax.Hub.Config({
				tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
			  });
			</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML"></script>
</head>
<body >
<div class="preloader"></div>
<?php 
$exam_id=isset($_GET['exam_id'])? addslashes($_GET['exam_id']):'';
$type=isset($_GET['type'])? (int)$_GET['type']:1;// 1 là nhiemvu(work1), 2 là nhiemvu(work), 3 là bài kiểm tra (exam);
$review=isset($_GET['review'])? (int)$_GET['review']:0;// xem lại bài làm 
$page=isset($_GET['page'])? (int)$_GET['page']:1;
$url_full=ROOTHOST_PATH.'lession-exam/'.$exam_id.'.html';
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
<div id="myModal2" class="modal  fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" id="content-modal2"></div>
		</div>
	</div>
</div>
<?php $exam_id=(int)$exam_id >0? $exam_id: "'".$exam_id."'";?>
<script>
 var exam_id=<?php echo $exam_id;?>;
 var type=<?php echo $type;?>;
 var review=<?php echo $review;?>;
 var time='<?php echo $time;?>';
 name_json='dataweb9999'+exam_id;
 var data_name_answer=name_json+'answer';
 var isview='';
$(document).ready(function() {
	 var page='<?php echo $page;?>';
	var rs=JSON.parse(localStorage.getItem(name_json));
	 var quiz='<?php echo $quiz;?>';
	if(!rs){
		$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz_review/save.php', {exam_id,type,review}, function(rep){
			if(rep==1) alert('Không lấy đc dữ liệu để lưu');
			else{
				//alert('gọi save');
				localStorage.setItem(name_json, rep);
				var data=JSON.parse(rep);
				data_ans=data['data_answer'];
				getContentPage(data,data_ans,page, exam_id, quiz);
			}
		});
	}
	else{
		var data=JSON.parse(localStorage.getItem(name_json));
		data_ans=data['data_answer'];
		getContentPage(data,data_ans,page, exam_id, quiz);
	}


});
function getContentPage(data,data_ans, page, exam_id, quiz){
	$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz_review/content.php', {data,data_ans,page, exam_id, quiz,type}, function(rep){
		$('#list_data').html(rep);
		getQuiz(exam_id,quiz);
	});
}
function getQuiz(exam_id, quiz_id, type=''){//type=1 là show có đáp 
	var data=JSON.parse(localStorage.getItem(name_json));
	var answer_val='';
	if(type==1){
		var checkbox = document.getElementsByName('answer-'+quiz_id);
		var result = "";
		
		if(checkbox.length>1){
			var type_answer=1;//dang tracnghiem
			for (var i = 0; i < checkbox.length; i++){
				if (checkbox[i].checked === true){
					result=1;
					break;
				}
			}
			if(result==''){alert('Vui lòng chọn đáp án trước khi trả lời!'); return;}
		}
		else{
			
			var type_answer=2;//dang tu luan
			//var answer_val=$('#answer-'+quiz_id).val();//for textarea
			 var answer_val = CKEDITOR.instances['answer-'+quiz_id+''].getData();
			
			if(answer_val==''){ 
				alert('Vui lòng nhập đáp án trước khi trả lời!'); 
				$('#answer-'+quiz_id).focus();
				return;
			}
		}
		
		saveAswer(quiz_id,type_answer);
		$('.frm-item-quiz'+quiz_id).addClass('active');
	}
	var data=JSON.parse(localStorage.getItem(name_json));
	data_ans=data['data_answer'];
	console.log(data_ans);
	var isview=1;
	

	
	 //var isview=JSON.parse(localStorage.getItem(name_isview));
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	  title=item['content'];
	  console.log(item['id']);
	  str_exam_id="'"+exam_id+"'";
	  cau=parseInt(quiz_id)+1;
	

	 var content="<h3 class='cau'>Câu: "+cau+"</h3>";
	 content+="<h4>"+title+"</h4>";
	 var count=0;
	 if(item.answers){
		const answers=JSON.parse(item.answers);
		j=0;
		content+='<div class="list-quiz">';
		for(var i in answers) {
			count++;
		  if(j==0) label='A';
			else if(j==1) label='B';
			else if(j==2) label='C';
			else label='D';
			checked='';
			view='';
			var ans=answers[i].cnt;
			var istrue=answers[i].is_true;
			//console.log(answers[i]);
			if(data_ans && data_ans[quiz_id]){
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
	}
	if(count==0){
		if(data_ans && data_ans[quiz_id] && data_ans[quiz_id]!='') answer_val=data_ans[quiz_id];
		content+= '<div class="form-control" name="answer-'+quiz_id+'" id="answer-'+quiz_id+'" placeholder="Nhập trả lời của bạn">'+answer_val+'</div>';
		//showGuide(exam_id, quiz_id);
	}
	 
	 content+='<div id="rep_guide"></div>';
	 if(type_answer==2 && type==1){
		 var rs=JSON.parse(localStorage.getItem(name_json));
		  var total_quiz=0;
		 if(rs['info_exam']['total_quiz'])  total_quiz=rs['info_exam']['total_quiz'];
		 if(quiz_id< total_quiz){
			 page=quiz_id+1;
			gotopage(total_quiz,2, page);
		 }
	 }
	 else{
		$('#list_quiz').html(content);
		  MathJax.Hub.Queue(["Typeset",MathJax.Hub]);

		
	 }
	
}
function gotopage(total_quiz,type='', page=''){
	//$('.item-act').removeClass('active');
	var url_root = '<?php $url_full;?>';
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

	
	getQuiz(exam_id,quiz);
	$("#page_quiz").val(page);
	//$(".frm-item-quiz"+quiz).addClass('active');
	
	 if (history.pushState) {
		history.pushState(null, null, url_root +'?page=' + page);
	 }
	return false;
	
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

function get_dap_an_dung(answers){
	 for(var j in answers) {
		var istrue=answers[j].is_true;
		
		if(istrue==='yes') return j;
	}
	return '';
}
function showGuide(exam_id, quiz_id){
	var data=JSON.parse(localStorage.getItem(name_json));
	$('#rep_guide').addClass("show-guide");
	 item=data[exam_id][quiz_id];
	var guide = item['guide'];
	$('#rep_guide').html(guide);
}

function showDesc(exam_id, quiz_id){
	var data=JSON.parse(localStorage.getItem(name_json));
	 item=data[exam_id][quiz_id];
	var knowledge =JSON.parse(item['knowledge']);
	//console.log(knowledge);
	$('#myModal2').modal('show');
	$('#myModal2 .modal-title').html('Lý thuyết');
	var rs='<div class="box-lythuyet">';
	rs+=knowledge;
	rs+='</div>';
	$('#content-modal2').html(rs);
}
let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);
$(window).load(function() {
   $('.preloader').fadeOut('slow');
});
</script>
</html>