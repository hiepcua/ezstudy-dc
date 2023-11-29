<?php
session_start();
define('incl_path','../../../global/libs/');
define('libs_path','../../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_wallet.php');

$type_work=2;// làm nv
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$time_start=isset($_POST['time_start'])? (int)$_POST['time_start']:'';
$data=isset($_POST['data'])? $_POST['data']:'';
$data_ans=isset($_POST['data_ans'])? $_POST['data_ans']:'';
$number_true=isset($_POST['num'])? (int)$_POST['num']:'';
$data_ans=addslashes(json_encode($data_ans));
//var_dump($data_ans);
// lấy info work
$info_work=$data['info_exam'];
$username=$info_work['member_user'];
$number_quiz=$info_work['number_quiz'];
$pass_percent=$info_work['pass_percent'];
$today=$info_work['today'];
$status=$info_work['status'];
if($number_true>=$pass_percent){
	$status=2;
	
}else $status=1;

		
// update kết quả vào work
$url='https://ezstudy.ecos.asia/api/save-work';
$data=array();
$post_data=$arr = array();
$arr['key']   = PIT_API_KEY;
$arr['data_answer']=$data_ans;
$arr['exam_id']=$exam_id;
$arr['time_learn']=time() - $time_start;
$arr['number_true']=$number_true;

$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post($url,json_encode($post_data));
if(is_array($rep['data']) && $rep['data']=='success'){
// cong thuong
	if($number_true>=$pass_percent){
		
		
	}
}

?>

