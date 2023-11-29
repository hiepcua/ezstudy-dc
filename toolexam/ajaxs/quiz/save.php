<?php
session_start(); ini_set('display_errors',1);
define('incl_path_par','../../../global/libs/');
define('incl_path','../../global/libs/');
require_once(incl_path.'config-tool.php');
require_once(incl_path_par.'gffunc.php');
require_once(incl_path_par.'gffunc_user.php');

$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$url=DC_API.'getexam';

$data=array();
$post_data=$arr = array();
$arr['key']   = PIT_API_KEY;
$arr['username']=getInfo('username');
$arr['exam_id']=$exam_id;
$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);

$rep = Curl_Post($url,json_encode($post_data));
if($rep['status']=='yes' && is_array($rep['data']) && count($rep['data']) > 0){
	$data[$exam_id]=$rep['data']; // list quiz
	$data['info_exam']=$rep['data_exam'];
	$data['time_start']=time();
	$data['iscomplete']='no';
}
if(count($data)>0){
	$_SESSION['EXAM_LIST'][$exam_id]='yes'; // trong phiên thì thêm vào session quiz đã lấy
	echo json_encode($data,JSON_UNESCAPED_UNICODE );
}else die(1);
?>