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
$username = isset($data['username']) ? antiData($data['username']) : '';
$subject = isset($data['subject']) ? antiData($data['subject']) : '';

if($key == PIT_API_KEY){
	if($username==""){
		echo json_encode(array('status'=>'no','data'=>"Username empty")); die();
	}

	$arr = array();
	$arr['subject_list'] = $subject;
	$result = SysAdd("ez_member", $arr, "AND username='".$username."'");

	if($result){
		echo json_encode(array('status'=>'no','data'=>'success'));
	}else{
		echo json_encode(array('status'=>'no','data'=>'error'));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();