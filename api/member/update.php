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


$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username = isset($data['username'])?antiData($data['username']):'';
$fullname = isset($data['fullname'])?antiData($data['fullname']):'';
$gender = isset($data['gender'])?antiData($data['gender']):'';
$phone = isset($data['phone'])?antiData($data['phone']):'';
$email = isset($data['email'])?antiData($data['email']):'';
$address = isset($data['address'])?antiData($data['address']):'';
$birthday = isset($data['birthday'])?antiData($data['birthday']):'';

if($key == PIT_API_KEY){
	if($fullname=='' || $email==''){
		echo json_encode(array('status'=>'no','data'=>"not_found"));
		die;
	}
	$arr = array();
	$arr['fullname'] = $fullname;
	$arr['gender']   = $gender;
	$arr['phone']    = $phone;
	$arr['email']    = $email;
	$arr['address']  = $address;
	$arr['birthday'] = $birthday;

	$result = SysEdit("ez_member",$arr," username='$username'");
	// update dữ liệu member về DC
	if($result) {
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] = $username;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
	}
	echo json_encode(array('status'=>'yes','data'=>"success",'activate'=>$activate));
		
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();