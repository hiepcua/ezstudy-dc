<?php
session_start();
define('incl_path_par','../../../global/libs/');
define('libs_path','../../../libs/');
define('incl_path','../../global/libs/');
require_once(incl_path_par.'gfconfig.php');
require_once(incl_path_par.'gffunc.php');
require_once(incl_path_par.'config_api.php');
require_once(incl_path_par.'gfinit.php');
require_once(incl_path_par.'gffunc_wallet.php');
require_once(incl_path_par.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$packet=getInfo('packet'); $packet_status=getInfo('packet_status');
if(isLogin()){
	$username=getInfo('username');
	$data=isset($_POST['data'])? $_POST['data']:'';
	$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
	$number_true=isset($_POST['num'])? (int)$_POST['num']:'';
	// lấy info work
	$info_work=$data['info_exam'];
	if($info_work['flag']!='L2' || $info_work['rework']=='yes'){
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
	die('Bạn không thể lưu bài luyện tập, hãy nâng cấp!');
}

?>

