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

if($key == PIT_API_KEY){
	if($username==""){
		echo json_encode(array('status'=>'no','data'=>"Username empty")); die();
	}

	$arr = array();
	$result = SysGetList("ez_member", array(), "AND username='".$username."'");
	$edate = isset($result[0])? $result[0]['edate']:'';
	$e_date = isset($result[0])? $result[0]['edate']:'N/a';
	$p_date = isset($result[0])? $result[0]['pdate']:'N/a';
	$today = time();

	if($edate==""){
		echo json_encode(array('status'=>'yes','data'=>$result[0],'account'=>'Free'));
	}else{
		if($today > $edate){
			echo json_encode(array('status'=>'yes','data'=>$result[0],'account'=>"Expired"));
		}else{
			$edate2 = date('d-m-Y', $e_date);
			$pdate2 = date('d-m-Y', $p_date);
			$today2 = date('d-m-Y');
			if($edate2!='' && $pdate2!=''){
				$date1 = date_create($today2);
				$date2 = date_create($edate2);
				$diff = date_diff($date1, $date2);
			}
			$num_day = $diff->format("%R%a");
			echo json_encode(array('status'=>'yes','data'=>$result[0],'account'=>"Active",'number_days'=>$num_day));
		}
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();