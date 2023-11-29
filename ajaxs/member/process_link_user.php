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

if(isset($_POST['link_user'])) { 
	$userlogin = getInfo('username');
	$max_child = getInfo('max_child');
	$link_user = antiData($_POST['link_user']);
	$result = sysGetList("ez_member",array('username','par_user')," AND username='$link_user'");
	if(isset($result[0])) {
		$par_user = $result[0]['par_user'];
		if($par_user != "" || $par_user != null ) die("not_allow");
		
		// check max child
		$count_user = sysCount("ez_member"," AND par_user='$userlogin'");
		if($count_user >= $max_child) die('over');
		
		// liên kết tài khoản học sinh
		$arr = array();
		$arr['par_user'] = $userlogin;
		$result1 = SysEdit("ez_member",$arr," username='$link_user'");
		
		// update dữ liệu member về DC
		if($result1) {
			$json = array();
			$json['key'] = PIT_API_KEY;
			$json['username'] = $link_user;
			$json['partner_code'] = PARTNER_CODE;
			$json['arr']   = $arr;
			$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
			//var_dump($post_data);
			die("success");
		} else die("error");
		
	} else die("not_found");
}