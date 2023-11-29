<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(isset($_POST['unit_id'])) { 
	$unit_id = antiData($_POST['unit_id']);
	$unit_title = antiData($_POST['unit_title']);
	$cur_grade = antiData($_POST['cur_grade']);
	$cur_subject = antiData($_POST['cur_subject']);
	$grade_version = getInfo('grade_version');
	//------------------------ get bài học -----------------------------
	$arrLesson = array();
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = $cur_grade;
	$json['subject'] = $cur_subject;
	$json['version'] = $grade_version;
	$json['unit'] = $unit_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arrLesson = $rep['data'];
	}
	?>
	<h2 class="title"><?php echo $unit_title;?></h2>
	<ul class="lession_list">
		<?php 
		if(is_array($arrLesson) && count($arrLesson)>0) {
			foreach($arrLesson as $a=>$b) { 
				$link = ROOTHOST."lession-detail/".$b['id'];
				$link_excercise = ROOTHOST."lession-exercise/".$b['id'];
				$link_video = ROOTHOST."lession-video/".$b['id'];
				echo "<li><div class='row'>
				<div class='col-md-8 col-xs-12 title'><a href='".$link."'>".$b['title']."</a></div>
				<div class='col-md-4 col-xs-12 func'>
				<a href='".$link."' class='btn'>Lý thuyết</a>
				<a href='".$link_excercise."' class='btn'>Bài tập</a>
				<a href='".$link_video."' class='btn'>Bài tập</a>
				</div>
				</div>
				</li>";
			} 
		}
		?>
	</ul>
	<?php } ?>