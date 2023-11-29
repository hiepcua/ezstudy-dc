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
if($key == PIT_API_KEY){
	$username = isset($data['username'])?antiData($data['username']):'';
	$pdate = isset($data['pdate'])?antiData($data['pdate']):'';
	$month = isset($data['month'])? (int)$data['month']:0;
	$type = isset($data['type'])? (int)$data['type']:0;// type=1 là TH cộng month date khi đăng ký DV GVHD
	/*$username='hslop12';
	$month=3;
	$pdate=time();*/
	if($username == "" || $pdate == "" || $month== 0)
		echo json_encode(array('status'=>'no','data'=>"incomplete_data"));
	else {
		// get tài khoản
		$item = SysGetList("ez_member",array('activate', 'edate')," AND username='$username'");
		if(isset($item[0])) {
			$rs=$item[0];
			$activate = $rs['activate'] == null ? 1 : '';
			$cur_edate=$rs['edate'];
			if($cur_edate=='') $edate=strtotime("+$month month");
			else $edate=strtotime("+$month month",$cur_edate);
			$arr = array();
			$arr['pdate'] = $pdate;
			$arr['edate'] = $edate;
			$arr['activate'] =1;
			$result = SysEdit("ez_member",$arr," username='$username'");
			if($type==0){
			// update dữ liệu member về DC
				if($result) {
					$json = array();
					$json['key'] = PIT_API_KEY;
					$json['username'] = $username;
					$json['partner_code'] = PARTNER_CODE;
					$json['arr']   = $arr;
					$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
					$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
					//var_dump($post_data);
				}
			}
			
			echo json_encode(array('status'=>'yes','data'=>"success",'activate'=>$activate));
		}
		else echo json_encode(array('status'=>'no','data'=>"not_found"));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();