<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(!isLogin()) die("E01");
if(isset($_POST['grade'])) { 
	$username = getInfo('username');
	$grade 	  = antiData($_POST['grade']);
	$grade_version 	  = $_POST['grade_version'] != "" ? antiData($_POST['grade_version']) : $grade."_V01";
	//update grade view cho học sinh
	$arr = array();
	$arr['grade'] = $grade;
	$arr['grade_version'] = $grade_version;
	$result1 = SysEdit("ez_member",$arr," username='$username'");
	
	setInfo('grade',$grade);
	setInfo('grade_version',$grade_version);
	
	// update dữ liệu member về DC
	if($result1) {
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] = $username;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
		//var_dump($post_data);
	}
	
	// ghi log
	/*$arr = array();
	$arr['cdate'] = time();
	$arr['user_create'] = $username;
	$arr['notes'] = "Đổi lớp sang ".$grade;
	$result2 = SysAdd("ez_notify",$arr);*/
	
	die("success");
}