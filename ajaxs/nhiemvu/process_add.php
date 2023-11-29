<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(!isLogin()) die("E01");
if(isset($_POST['txt_subject'])) {
	//var_dump($_POST['txt_subject']);
	//lấy arr bài học và môn đã chọn
	$arr=array();
	foreach($_POST['txt_subject'] as $vl){
		$lesson=isset($_POST['txt_lesson'.$vl])? addslashes($_POST['txt_lesson'.$vl]):'';
		$str=explode('_',$vl);
		$subject_id=isset($str[1]) ? $str[1]:'';
		$arr[$subject_id]=array('subject'=>$subject_id, 'lesson'=>$lesson);
	}
	$json['key'] 		= PIT_API_KEY;
	$json['username'] 	= getInfo('username');
	$json['grade'] 		= getInfo('grade');
	$version = getInfo('grade_version');
	$json['version'] 	= $version == "N/a" ? $json['grade']."_V01" : $version;
	$json['arr_subject_lesson']= json_encode($arr);
	
	$post_data['data']  = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_NHIEMVU_ADD,json_encode($post_data));
	
	if(isset($rep['data'])) echo $rep['data'];
	else echo "error_send";
} ?>