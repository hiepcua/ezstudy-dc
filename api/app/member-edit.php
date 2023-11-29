<?php
session_start();
define('root_path','../../');
require_once(root_path.'global/libs/gfconfig.php');
require_once(root_path.'global/libs/gfinit.php');
require_once(root_path.'global/libs/gffunc.php');
require_once(root_path.'global/libs/gffunc_user.php');
require_once(root_path.'libs/cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username	= isset($data['username']) ? antiData($data['username']) : '';

if($key == PIT_API_KEY){
	// Check exist username
	$num = SysCount("ez_member","AND username='".$username."'");
	if($num < 1){
		echo json_encode(array('status'=>'no','data'=>"Username empty"));
		die();
	}

	$arr = array();
	$arr['fullname'] = isset($data['fullname']) ? antiData($data['fullname']):'';
	$arr['phone'] = isset($data['phone']) ? antiData($data['phone']):'';
	$arr['email'] = isset($data['email']) ? antiData($data['email']):'';
	$arr['birthday'] = isset($data['birthday']) && $data['birthday']!="" ? antiData($data['birthday'],'int'):0;
	$arr['gender'] = isset($data['gender']) ? antiData($data['gender']):'nam';
	$arr['address'] = isset($data['address']) ? antiData($data['address']):'';
	$result = SysEdit("ez_member", $arr, " username='".$username."'");
	if(!$result){
		echo json_encode(array('status'=>'no','data'=>"Save error"));
	}else{
		// Update ez_members trên DC
		$json = array();
		$json['key']   = PIT_API_KEY;
		$json['username'] = $username;
		$json['fullname'] = $arr['fullname'];
		$json['phone'] = $arr['phone'];
		$json['email'] = $arr['email'];
		$json['birthday'] = $arr['birthday'];
		$json['gender'] = $arr['gender'];
		$json['address'] = $arr['address'];

		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_MEMBER_UPDATE, json_encode($post_data));

		if(isset($rep['data']) && $rep['data']=="success") {
			echo json_encode(array('status'=>'yes','data'=>"success"));
		}else{
			echo json_encode(array('status'=>'no','data'=>"Save error"));
		}
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"Key fail"));
}