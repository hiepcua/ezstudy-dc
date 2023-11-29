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
$username=getInfo('username');
if(!isLogin()) die("E01");
if(isset($_POST['txt_subject'])) { 
	$version = isset($_POST['txt_version']) ? $_POST['txt_version']:array();
	$subject = isset($_POST['txt_subject']) ? $_POST['txt_subject']:array();
	//update  cho học sinh
	if(isset($version[0]) && count($subject)>0){
		$str = implode(',',$subject);
		$version_id = $version[0];
		$arr = array();
		$arr['grade_version'] = $version_id;
		$arr['subject_list'] = $str;
		$result1 = SysEdit("ez_member",$arr," username='$username'");
		setInfo('grade_version', $version_id);
		setInfo('subject_list', $str);

		// Cập nhật thông tin học sinh sang DC
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] 	= $username;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr'] = $arr;
		$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
		$url = API_MEMBER_UPDATE_INFO;
		$reponse_data = Curl_Post($url,json_encode($post_data));

		if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
			if(isset($reponse_data['data']) && $reponse_data['data']=="success"){
				die("success");
			}
		}
		die("error");
	}
	/* ghi log
	$version_name = antiData($_POST['version_name']);
	$arr = array();
	$arr['cdate'] = time();
	$arr['user_create'] = $username;
	$arr['notes'] = "Chọn bộ sách ".$version_name;
	$result2 = SysAdd("ez_notify",$arr);
	*/
}else{
	die("error");
}