<?php
session_start();
define('incl_path_par','../../../global/libs/');
define('incl_path','../../global/libs/');
require_once(incl_path.'config-tool.php');
require_once(incl_path_par.'gffunc.php');
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$type=isset($_POST['type'])? (int)$_POST['type']:1;// 1 là work1, 2 là work, 3 là exam, 4 là xem lại bài làm
$review=isset($_POST['review'])? (int)$_POST['review']:0;// 1 là xem lại bài làm
if($type==3) $url=DC_API.'getexam';//bài kiểm tra
else $url=DC_API.'exam-work';// nv (work  và work1)

$data=array();
$post_data=$arr = array();
$arr['key']   = PIT_API_KEY;
$arr['exam_id']=$exam_id;
$arr['type_work']=$type;
$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post($url,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0){
	$data[$exam_id]=$rep['data'];
	$info_exam=$rep['data_exam'][0];
	$info_exam['total_quiz']=$rep['data_exam']['total_quiz'];
	$data['info_exam']=$info_exam;
	if($review==1){
		$results=$info_exam['results'];
		$arr_answer=json_decode($results, true);
		$data['data_answer']=$arr_answer;
	}
	else $data['data_answer']=array();
	$data['time_start']=time();
}
//var_dump($data['data_answer']);
if(count($data)>0) echo json_encode($data);
else echo '1';

?>

