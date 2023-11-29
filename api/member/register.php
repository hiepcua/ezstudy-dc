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
$data 	= json_decode(decrypt($json['regis_data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';

if($key == PIT_API_KEY){
	if(is_array($data)) {
		$arr = array();
		$arr['fullname']= isset($data['fullname'])?antiData($data['fullname']):'';
		$arr['username']= isset($data['username'])?antiData($data['username']):'';
		$type			= isset($data['type'])?antiData($data['type']):'hocsinh';
		$arr['grade']	= isset($data['grade'])?antiData($data['grade']):'';
		$arr['ref_user']= isset($data['ref_user'])?antiData($data['ref_user']):'';
		$arr['saler']	= isset($data['saler'])?antiData($data['saler']):'';
		$password		= isset($data['password'])?antiData($data['password']):'';
		
		if($arr['username']=='' || $password=='' || $arr['fullname']=='' || $arr['saler']=='')
			echo json_encode(array('status'=>'no','data'=>"incomplete_data"));
		else {
			if($type == "hocsinh") 
				$arr['utype'] = "hocsinh";
			else {
				$arr['utype'] = "chame";
				$arr['grade'] = '';
				$arr['max_child'] = MAX_CHILD;
			}

			$arr['cdate']	=time();
			$arr['status']	='yes';
			$arr['isactive']='yes';
			$arr['password'] = hash('sha256', $arr['username']).'|'.hash('sha256', $password);
			// check exist
			$rs = SysCount("ez_member"," AND username='".$arr['username']."'");
			if($rs > 0)
				echo json_encode(array('status'=>'no','mess'=>"user_exist"));
			else {
				// Tiến hành thêm mới
				$req = SysAdd("ez_member", $arr, 1);
				// Chuyển dữ liệu member về DC
				$json = array();
				$json['key']   = PIT_API_KEY;
				$json['partner_code'] = PARTNER_CODE;
				$json['arr']   = $arr;
				$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
				$req = Curl_Post(API_MEMBER_REGISTER,json_encode($post_data));
				echo json_encode(array('status'=>'yes','mess'=>"success"));
			}
		}
		unset($arr);
		unset($data);
	}else 
		echo json_encode(array('status'=>'no','mess'=>"empty_data"));
}else{
	echo json_encode(array('status'=>'no','mess'=>"key_fail"));
}
die();