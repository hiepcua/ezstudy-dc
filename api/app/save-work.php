<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username 		= isset($data['username']) ? antiData($data['username']) : '';
$exam_id 		= isset($data['exam_id']) ? antiData($data['exam_id']) : '';
$flag 			= isset($data['flag']) ? antiData($data['flag']) : '';
$rework 		= isset($data['rework']) ? antiData($data['rework']) : 'no';
$number_true 	= isset($data['number_true']) ? antiData($data['number_true'],'int') : '';

if($key == PIT_API_KEY){
	if($username=="" || $exam_id==""){
		echo json_encode(array('status'=>'yes','data'=>"Missing param"));
		die();
	}

	$member_info = api_get_member_info($username);
	if(count($member_info)<1){
		echo json_encode(array('status'=>'yes','data'=>"Username not exist"));
		die();
	}

	// lấy info work
	$info_work = $data['info_exam'];
	if($flag!='L2' || $rework=='yes'){
		$lesson_id=$info_work['lesson_id'];
		
		$number_quiz=$info_work['total_quiz'];
		$mark=$number_true/$number_quiz*100;
		$pass_percent=$info_work['pass_percent'];
		$data_ans=$info_work['data_answer'];
		
		$time_start=$data['time_start'];
		$totalSeconds=time()-$time_start;
		$flag=$info_work['flag'];
		
		
		$status=$info_work['status'];
		if($number_true>=$pass_percent) $status=2; // pass
		else $status=1; // không pass

		// update kết quả vào work
		$data=array();
		$post_data=$arr = array();
		$arr['key']   = PIT_API_KEY;
		$arr['username']=$username;
		$arr['exam_id']=$exam_id;
		$arr['lesson']=$lesson_id;
		
		$arr['data_answer']=json_encode($data_ans,JSON_UNESCAPED_UNICODE);
		$arr['time_learn']=$totalSeconds;
		$arr['number_true']=$number_true;
		$arr['mark']=$mark;
		$arr['rework']=$info_work['rework'];
	
		$arr['status']=$status;
		$arr['flag']='L2'; // câp nhập cờ đang làm/đã làm
		$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_SAVEWORK,json_encode($post_data));
		if($info_work['rework']=='no'){
			if(isset($rep['data']) && $rep['data']=='success'){
				$json = array();
				$json['key']   = PIT_API_KEY;
				$json['username'] = getInfo('username');
				$json['is_today'] 	=1;
				$json['grade'] 	= getInfo('grade');
				$version = getInfo('grade_version');
				$json['version'] 	= $version == "N/a" ? $json['grade']."_V01" : $version;
				$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
				$rep = Curl_Post(API_NHIEMVU_BONNUS,json_encode($post_data)); 
				$list_work=array();
				if(is_array($rep['data_obj']) && count($rep['data_obj']) > 0) $list_work = $rep['data_obj'];	
				
				// cong thuong
				$flag_hoanthanhbai=$flag_dalam=0;
				foreach($list_work as $key=>$item){ 
					$status=$item['status'];
					if($status==1) $flag_dalam++;
					if($status==2) $flag_hoanthanhbai++;
				}
				//AddBonus type: 1 - login, 2- vào làm bài, 3 - làm bài tập, 4 là xem lý thuyết bất kỳ 5 -hoàn thành nv, 6 -hoàn thành xuất sắc
				// user- vào làm bài
				if($flag_dalam==1) AddBonus($username, 1, 2);	//1 quiz bất kỳ
				if($flag_dalam==2) AddBonus($username, 2, 2); 	//2 quiz bất kỳ
				if($flag_dalam==5) AddBonus($username, 5, 2);	//5 quiz bất kỳ

				// user- hoàn thành bài
				if($number_true>=$pass_percent){
					AddBonus($username, 1, 5);
					// user- hoàn thành xuất sắc
					$tile=($number_true/$number_quiz)*100;
					if($tile>=70){
						AddBonus($username, 1, 6);
					}
				}
				// thưởng đạt mốc sao
				AddBonusStar($username);
			}
		}
		die('success');
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();
