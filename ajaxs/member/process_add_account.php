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

if(!isLogin()) die("E01");

if(isset($_POST['username'])) {
	$arr = array();
	$arr['fullname']=isset($_POST['fullname'])?antiData($_POST['fullname']):'';
	$arr['username']=isset($_POST['username'])?antiData($_POST['username']):'';
	$arr['grade']	=isset($_POST['lop'])?antiData($_POST['lop']):'';
	$arr['utype'] 	="hocsinh";
	$arr['cdate']	=time();
	$arr['status_link']	='yes';
	$arr['status']	='yes';
	$arr['isactive']='yes';
	$arr['par_user']=getInfo('username');
	$password		=isset($_POST['password'])?antiData($_POST['password']):'';
	unset($_POST);
	
	// check max child
	$ref_user 	= getInfo('ref_user');
	$ref_user 	= $ref_user == "N/a" ? null : $ref_user;
	$saler 		= getInfo('saler');
	$saler 		= $saler == "N/a" ? null : $saler;
	$par_user 	= getInfo('username');
	$max_child 	= getInfo('max_child');
	$count_user = sysCount("ez_member"," AND par_user='$par_user'");
	if($count_user >= $max_child) die('over');

	if($arr['username']=='' || $password=='') die('Tên đăng nhập và mật khẩu không được bỏ trống');
	$arr['password'] = hash('sha256', $arr['username']).'|'.hash('sha256', $password);

	// check exist
	$rs = SysCount("ez_member"," AND username='".$arr['username']."'");
	if($rs > 0) die('Tên đăng nhập đã có. Vui lòng nhập tên đăng nhập mới.');

	// Tiến hành thêm mới
	$arr['ref_user'] = $ref_user;
	$arr['saler']    = $saler;
	$req = SysAdd("ez_member",$arr,"1");
	
	if($req) {
		// chuyển dữ liệu member về DC
		$json = array();
		$json['key']   = PIT_API_KEY;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_REGISTER,json_encode($post_data));
		//var_dump($post_data);
		
		die("success");
	} else die("error");
	unset($req);
	unset($arr);
}