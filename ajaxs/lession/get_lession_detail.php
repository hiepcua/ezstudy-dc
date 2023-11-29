<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(isset($_POST['lession_id'])) { 
	$lession_id = antiData($_POST['lession_id']);
	//------------------------ get bài học -----------------------------
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['id']    = $lession_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arrLesson = $rep['data'];
	}
	$row = $arrLesson[0];
	?>
	<h2 class="title"><?php echo stripslashes($row['title']);?></h2>
	<div class="intro"><?php if($row['intro']!="") {
		echo "<h3>Mô tả:</h3>";
		echo stripslashes($row['intro']);
	} ?></div>
	<div class="intro"><?php if($row['guide']!="") {
		echo "<h3>Hướng dẫn học:</h3>";
		echo stripslashes($row['guide']);
	} ?></div>
<?php } ?>