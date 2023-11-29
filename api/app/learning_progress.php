<?php
session_start();
define('root_path','../../');
require_once(root_path.'global/libs/gfconfig.php');
require_once(root_path.'global/libs/gfinit.php');
require_once(root_path.'global/libs/gffunc.php');
require_once(root_path.'global/libs/config_api.php');
require_once(root_path.'global/libs/gffunc_user.php');
require_once(root_path.'libs/cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$grade 	  	= isset($data['grade']) ? antiData($data['grade']) : '';
$username 	= isset($data['username']) ? antiData($data['username']) : '';
$type_tbl 	= isset($data['type_tbl']) ? antiData($data['type_tbl']) : 0;

function get_report_subject($mon,$Nhiemvu){
	$num_nowork=0;
	$num_total=$num_work=0;
	if(isset($Nhiemvu[$mon])){
		foreach($Nhiemvu[$mon] as $key=>$item) { 
			$status  = $item['status'];
			if($status=='') $num_nowork++;
			if($status>=1) $num_work++;
			$num_total++;
		}
	}
	
	return array('num_nowork'=>$num_nowork,'num_work'=>$num_work,'num_total'=>$num_total);
}

if($key == PIT_API_KEY){
	// Get nhiệm vụ của học sinh
	$json2 = array();
	$json2['key'] = PIT_API_KEY;
	$json2['username'] = $username;
	$json2['type_tbl'] = $type_tbl;
	$json2['grade'] = $grade;
	$post_data2['data'] = encrypt(json_encode($json2),PIT_API_KEY);
	$url2 = API_DC_URL.'app_list_work';
	$reponse_nhiemvu = Curl_Post($url2,json_encode($post_data2));
	if(isset($reponse_nhiemvu['status']) && $reponse_nhiemvu['status']=='yes'){
		if(isset($reponse_nhiemvu['data']) && !empty($reponse_nhiemvu['data'])){
			$obj_nhiemvu = array();
			foreach ($reponse_nhiemvu['data'] as $key => $value) {
				$value2['member_user'] = $value['member_user'];
				$value2['title'] = $value['title'];
				$value2['status'] = $value['status'];
				$value2['subject'] = $value['subject'];
				$obj_nhiemvu[$value['subject']][] = $value2;
			}
		}
	}

	// Get danh sách môn học mà học sinh đăng ký.
	$member = api_get_member_info($username);
	$reg_subject = $member['subject_list']!="" ? explode(',', $member['subject_list']):array();

	// Get danh sách môn học của khối lớp học sinh đang học
	$json3 = array();
	$json3['key'] = PIT_API_KEY;
	$json3['grade'] = $grade;
	$post_data3['data'] = encrypt(json_encode($json3),PIT_API_KEY);
	$url3 = API_DC_URL.'app/get_subject';
	$reponse_data3 = Curl_Post($url3,json_encode($post_data3));

	$obj_data3 = array();
	if(isset($reponse_data3['status']) && $reponse_data3['status']=='yes'){
		if(isset($reponse_data3['data']) && !empty($reponse_data3['data'])){
			foreach ($reponse_data3['data'] as $key => $value) {
				$obj_data3[$value['id']] = $value;
			}
		}
	}

	$arr_data = array();
	foreach ($obj_data3 as $key => $value) {
		if(in_array($key, $reg_subject)){
			$k = $value['subject'];
			$arr_data[$key][] = get_report_subject($k, $obj_nhiemvu);
		}
	}

	$arr_data = count($arr_data)>0 ? $arr_data:null;

	echo json_encode(array('status'=>'yes','data'=>$arr_data));
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();