<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc.php');
require_once(libs_path.'cls.mysql.php');
include_once(incl_path."config_api.php"); // get data api

if(isset($_POST['username']) && $_POST['username']!="") {
	$arr = array();
	$arr['fullname'] 	= isset($_POST['fullname']) ? antiData($_POST['fullname']):'';
	$arr['username'] 	= isset($_POST['username']) ? antiData($_POST['username']):'';
	$arr['grade']	 	= isset($_POST['lop']) ? antiData($_POST['lop']):'';
	$arr['par_user'] 	= isset($_POST['par_user']) ? antiData($_POST['par_user']):'';
	$arr['utype'] 	 	= "hocsinh";
	$arr['cdate']		= time();
	$arr['status_link']	= 'yes';
	$arr['partner_code']= 'chame';
	$arr['status']		= 'yes';
	$arr['isactive'] 	= 'yes';
	$password			= isset($_POST['password'])?antiData($_POST['password']):'';
	unset($_POST);

	if($arr['username']=='' || $password=='') die('Tên đăng nhập và mật khẩu không được bỏ trống');
	$arr['password'] = hash('sha256', $arr['username']).'|'.hash('sha256', $password);

	// check exist
	$res = api_get_child_member($arr['username']);
	if(count($res)>0) die('Tên đăng nhập đã có. Vui lòng nhập tên đăng nhập mới.');

	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['arr'] = $arr;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_REGISTER, json_encode($post_data));

	if($rep['status']=="yes" && $rep['data']="success")
		die("success");
	else
		die("error");
}else{
	die("error");
}