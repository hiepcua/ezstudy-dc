<?php
session_start();
define('incl_path_par','../global/libs/');
define('incl_path','global/libs/');
define('libs_path','../libs/');
//require_once(incl_path.'config-tool.php');
require_once(incl_path_par.'gfconfig.php');
require_once(incl_path_par.'gffunc.php');
require_once(incl_path_par.'config_api.php');
require_once(incl_path_par.'gffunc_user.php');
require_once(incl_path_par.'gfinit.php');
require_once(libs_path.'cls.mysql.php');
header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
define('ISHOME',true);
if(isLogin()){
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
	<title>Luyện tập</title>
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
<?php 
$exam_id=isset($_GET['exam_id'])? addslashes($_GET['exam_id']):'';
$type=isset($_GET['type'])? (int)$_GET['type']:1;	//1 là nhiemvu(work1), 2 là nhiemvu(work), 3 là bài kiểm tra (exam);

$page=isset($_GET['page'])? (int)$_GET['page']:1; // thứ tự câu quiz
$url_full=ROOTHOST_PATH.'lession-exam/'.$exam_id.'.html';
$quiz_index=$page-1;
$data=array();
?>
<script>
	let vh = window.innerHeight * 0.01;
	document.documentElement.style.setProperty('--vh', `${vh}px`);
	$(window).load(function() {
		$('.preloader').fadeOut('slow');
	});
	
	var exam_id='<?php echo $exam_id;?>';
	var type='<?php echo $type;?>';
	var name_json='ezWork_'+exam_id;
	var data=JSON.parse(localStorage.getItem(name_json));
		
	$(document).ready(function() {
		var page='<?php echo $page;?>';
		var quiz_index='<?php echo $quiz_index;?>';
		data=null;
		if(!data){
			$.post('<?php echo ROOTHOST_PATH;?>ajaxs/quiz/save.php',{exam_id,type}, function(rep){ // get exam, quiz
				if(rep==1){
					alert('Không tìm thấy dữ liệu của bài thi! Vui lòng kiểm tra trạng thái login!');
					console.log(rep);
				}else{
					try{
						var data=JSON.parse(rep);
						localStorage.setItem(name_json, rep);
						getContentPage(data,page,exam_id,quiz_index);
					}catch(e){
						console.log(rep);
						alert('Có lỗi trong quá trình lấy dữ liệu');
					}
				}
			});
		}
		else{
			getContentPage(data,page,exam_id,quiz_index);
		}
	});
	
	function getContentPage(data,page, exam_id, quiz_index){
		var _url='<?php echo ROOTHOST_PATH;?>ajaxs/quiz/content.php';
		var data_exam=JSON.stringify(data);
		_data={
			'data_quiz':data_exam,
			'exam_id':exam_id,
			'quiz':quiz_index,
			'type':type			
		}
		$.post(_url,_data, function(rep){
			$('#list_data').html(rep);
			showQuiz(exam_id,quiz_index);
			MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
		});
	}

	function getQuiz(exam_id, quiz_index, Quiz_type=1){
		var data=JSON.parse(localStorage.getItem(name_json)); 
		var quizItem=data[exam_id][quiz_index];
		var quiz_id=quizItem['id'];
		var Qtype=quizItem['type']; 
		//T01(True/False), T02(Sigle choice), T03(Multiple choice), T04(Fill blank), T05(Matching)...
		// Ngoài ra có exam_type (trắc nghiệm, tự luận, hỗn hợp)
		if(!data['info_exam']['data_answer']) data['info_exam']['data_answer']=new Object();
		answers=JSON.parse(quizItem.answers);
		
		if(answers.length!==0){
			var checkbox = document.getElementsByName('answer-'+quiz_index);
			var result = [];
			if(checkbox.length>1){
				for (var i = 0; i < checkbox.length; i++){
					if (checkbox[i].checked === true){
						result.push(checkbox[i].value);
					}
				}
				if(result.length===0){
					alert('Vui lòng chọn đáp án trước khi trả lời!'); return;
				}else{
					data['info_exam']['data_answer'][quiz_id]=result;
				}
			}
		}else{
			var answer_val = CKEDITOR.instances['answer-'+quiz_index+''].getData();
			if(answer_val==''){
				alert('Vui lòng nhập đáp án trước khi trả lời!'); 
				$('#answer-'+quiz_index).focus();
				return;
			}else{
				data['info_exam']['data_answer'][quiz_id]=answer_val.replace("\n","");;
			}
		}
		new_data=JSON.stringify(data);
		localStorage.setItem(name_json,new_data); // lưu lại kết quả
		$('.frm-item-quiz'+quiz_index).addClass('active');
		showQuiz(exam_id,quiz_index); // show lại quiz
			
	}
	function showQuiz(exam_id, quiz_index){
		var data=JSON.parse(localStorage.getItem(name_json));
		var item=data[exam_id][quiz_index];
		var quiz_id=item['id'];
	
		var data_ans=data['info_exam']['data_answer']?data['info_exam']['data_answer']:new Object();
		var isviewQuiz='';
		if(data_ans[quiz_id]) isviewQuiz=1;
		
		//console.log(item);
		title=item['content'];
		str_exam_id="'"+exam_id+"'";
		cau=parseInt(quiz_index)+1;
		
		var content="<h3 class='cau'>Câu: "+cau+"</h3>";
		content+="<div class='content_quiz'>"+title+"</div>";
		
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
				
				var ans=answers[i].cnt;
				var istrue=answers[i].is_true;
				
				checked=view='';
				if(isviewQuiz==1){	
					var isAns=false;
					for(var m in data_ans[quiz_id]){
						if(data_ans[quiz_id][m]==i){
							isAns=true;
							break;
						}
					}
					if(isAns){
						view='item-false';
						checked='checked';
					}
					if(istrue=='yes' ) view='item-true';
				}
				content+= '<div class="item-q item-quiz'+quiz_index+' '+view+'" onclick="AnswerSelect(this)"><input '+checked+' onclick="checkOne(this,'+quiz_index+')" type="checkbox" value="'+i+'" id="answer-'+i+'" name="answer-'+quiz_index+'" class="item_ans item_ans_'+quiz_index+'" dataid="'+quiz_index+'"><span class=\"label-asw\"><strong>'+label+': </strong></span><span>'+ans+'</span></div>';
				j++;
			}
			content+='</div>';
		}
		if(count==0){ // không có câu trả lời
			var answer_val='';
			if(data_ans[quiz_id] && data_ans[quiz_id]!='') answer_val=data_ans[quiz_id];
			content+= '<textarea class="form-control" name="answer-'+quiz_index+'" id="answer-'+quiz_index+'" placeholder="Nhập trả lời của bạn">'+answer_val+'</textarea>';
		}
		content+='<div id="rep_guide"></div>';
		content+= '<div class="gr-submit"><span class="btn-send btn-primary btn" onclick="getQuiz('+str_exam_id+', '+quiz_index+',1)">Trả lời</span></div>';
		$('#list_quiz').html(content).text();
		if(count==0){
			CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://ckeditor.com/docs/ckeditor4/4.16.1/examples/assets/plugins/ckeditor_wiris/', 'plugin.js');
			CKEDITOR.replace('answer-'+quiz_index+'',EditOption);
		}
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}
	function gotopage(total_quiz,type='',page=''){
		$('.item-act').removeClass('focus');
		var url_root = '<?php $url_full;?>';
		if(page==''){// for next prev
			var page=$("#page_quiz").val();
		}
		page=parseInt(page);
		total_quiz=parseInt(total_quiz);
		
		if(type==1){
			page=page-1;
		}else if(type==2){
			page=page+1;
		}else{
			
		}
		
		if(page<1) page=total_quiz;
		if(page>total_quiz) page=1;
		quiz=page-1;
		
		showQuiz(exam_id,quiz,1,1);
		
		$("#page_quiz").val(page);
		$(".frm-item-quiz"+quiz).addClass('focus');

		if (history.pushState) {
			history.pushState(null, null, url_root +'?page=' + page);
		}
		return false;
	}

	function fomat_checbox(name_input){
		var myCheckbox = document.getElementsByName(name_input);
		Array.prototype.forEach.call(myCheckbox,function(el){
			el.checked = false;
		}); 
	}

	function AnswerSelect(thisAns){
		$(thisAns).find('.item_ans').each(function(){
			_thisid=$(this).attr('dataid');
			checkOne(this,_thisid);
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
		var data=JSON.parse(localStorage.getItem(name_json));
		$('#rep_guide').addClass("show-guide");
		item=data[exam_id][quiz_id];
		var guide = item['guide'];
		$('#rep_guide').html(guide);
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}

	function showDesc(exam_id, quiz_id){
		var data=JSON.parse(localStorage.getItem(name_json));
		item=data[exam_id][quiz_id];
		var knowledge =JSON.parse(item['knowledge']);
		$('#myModal2').modal('show');
		$('#myModal2 .modal-title').html('Lý thuyết');
		var rs='<div class="box-lythuyet">';
		rs+=knowledge;
		rs+='</div>';
		$('#content-modal2').html(rs);
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}
</script>
</html>
<?php }
else echo '<h3 style="text-align:center">Bạn đang sử dụng khóa học miễn phí. Hãy nâng cấp khóa học để sử dụng chức năng này!</h3>';?>