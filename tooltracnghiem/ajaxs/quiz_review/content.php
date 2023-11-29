<?php
session_start();
define('incl_path_par','../../../global/libs/');
define('incl_path','../../global/libs/');
require_once(incl_path.'config-tool.php');
require_once(incl_path_par.'gffunc.php');
$array=isset($_POST['data'])? $_POST['data']:'';
$data_ans=isset($_POST['data_ans'])? $_POST['data_ans']:'';
$time_start=isset($_POST['time_start'])? $_POST['time_start']:'';
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$page=isset($_POST['page'])? (int)$_POST['page']:1;
$type=isset($_POST['type'])? (int)$_POST['type']:1;// 1 là nhiemvu(work1), 2 là nhiemvu(work), 3 là bài kiểm tra (exam);
$quiz_id=$page-1;
$knowledge='';
if(!isset($array[$exam_id])) die('Not Found');
$item=$array[$exam_id];
$time_start=$array['time_start'];
//info câu hỏi
$info_exam=$array['info_exam'];
$title_exam=$info_exam['title'];
$number_quiz=$info_exam['total_quiz'];
$time_exam=15;
if(isset($item[$quiz_id])){
	$quession=$item[$quiz_id]['content'];
	$title=$item[$quiz_id]['title'];
	$answer=$item[$quiz_id]['answers'];
	if($answer=='') $type_answer=2;
	else $type_answer=1;
	$flag_know=$flag_guide=false;
	if(isset($item[$quiz_id]['guide']) && $item[$quiz_id]['guide']!='') $flag_guide=true;
	if(isset($item[$quiz_id]['knowledge']) && $item[$quiz_id]['knowledge']!='') $flag_know=true;
	
	$arr_answer=json_decode($answer, true);
	$guide=json_encode($item[$quiz_id]['guide']);
	?>
	<nav class="nav-top">
	<div class="container-fluid">
		
		<h3 class="label-title"><?php echo $title_exam;?></h3>
		<div class="gr-box-btn">
			
			<span class="btn-send btn-default btn" onclick="exit()">Thoát</span>
		</div>
	</div>
	</nav>
	<div id="main">
		<div class="page-exam">
		<div class="box-item info-test">
			<div class="info-quiz">
				<div class="group-btn">
					<?php if($flag_guide==true){?><span class="btn btn1 btn-success" onclick="showGuide('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Hướng dẫn</span><?php }?>
					<?php if($flag_know==true){?><span class="btn btn2 btn-danger" onclick="showDesc('<?php echo $exam_id;?>','<?php echo $quiz_id;?>')">Lý thuyết</span><?php }?>
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
				<h4 class="title-test">Danh sách câu</h4>
			</div>
				<div class="item-row" id="data_frm_quiz">
				<?php
				$stt=0;
				$number=ceil($number_quiz/2);
				foreach($item as $quiz=> $rows){
					$stt++;
					$active='';
					$id=$quiz;
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
			
				<span class="btn-send btn-default btn" onclick="exit()">Thoát</span>
			</div>

			</div>

			</div>
		</div>
	</div>	
<div class="box-action-footer"></div>
<?php
}

?>
<?php $exam_id=(int)$exam_id >0? $exam_id: "'".$exam_id."'";
//$url_full=ROOTHOST_PATH.'lession-exam/'.$exam_id.'.html';
$url_full='';
?>
<script>
var type=<?php echo $type;?>;

var url_root = '<?php echo $url_full?>';
var roothost = '<?php echo ROOTHOST?>';
var exam_id=<?php echo $exam_id;?>;
var time_start='<?php echo $time_start;?>';
name_json='dataweb9999'+exam_id;
var data_name_answer=name_json+'answer';
var isview=name_json+'isview';
function againQuiz(){
	localStorage.removeItem(name_json);
	localStorage.removeItem(data_name_answer);
	localStorage.removeItem(isview);
}
$('#myModal').on('hidden.bs.modal', function () {
    setTimeout(function(){window.location.href=roothost;}, 500);
});
function exit(){
	if(confirm('Bạn có chắc muốn thoát khỏi chương trình!')){
		againQuiz();
		 setTimeout(function(){window.location.href=roothost;}, 500);
		//window.close();
	}
	else return false;
	
}

</script>
