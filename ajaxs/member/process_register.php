<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isset($_POST['username'])) {
	$arr = array();
	$type	=isset($_POST['type'])?antiData($_POST['type']):'student';
	$arr['fullname']=isset($_POST['fullname'])?antiData($_POST['fullname']):'';
	$arr['username']=isset($_POST['username'])?antiData($_POST['username']):'';
	$arr['grade']	=isset($_POST['lop'])?antiData($_POST['lop']):'';
	$arr['ref_user']=isset($_POST['ref_user'])?antiData($_POST['ref_user']):'';
	if($type == "student") {
		$arr['utype'] = "hocsinh";
	} else {
		$arr['utype'] = "chame";
		$arr['grade'] = '';
		$arr['max_child'] = MAX_CHILD;
	}
	
	$arr['cdate']	=time();
	$arr['status']	='yes';
	$arr['isactive']='yes';
	$password		=isset($_POST['password'])?antiData($_POST['password']):'';
	unset($_POST);

	if($arr['username']=='' || $password=='') die('Tên đăng nhập và mật khẩu không được bỏ trống');
	$arr['password'] = hash('sha256', $arr['username']).'|'.hash('sha256', $password);

	// check exist
	$rs = SysCount("ez_member"," AND username='".$arr['username']."'");
	if($rs > 0) die('Tên đăng nhập đã có. Vui lòng nhập tên đăng nhập mới.');
	// Tiến hành thêm mới
	$result = SysAdd("ez_member",$arr,"1");
	if($result) {
		// chuyển dữ liệu member về DC
		$json = array();
		$json['key']   = PIT_API_KEY;
		$arr['partner_code']=PARTNER_CODE;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_REGISTER,json_encode($post_data));
		die("success");
	} else die("error");
	unset($result);
	unset($arr);
}