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

function convert_to_munit($second){
	return round($second/60);
}

if($key == PIT_API_KEY){
	$strWhere = "";
	$arr_date = getDateReport(2); 
	$first_date = isset($arr_date['first'])? $arr_date['first']:'';
	$last_date = isset($arr_date['last'])? $arr_date['last']:'';
	$last_date = strtotime('+1 day', $last_date);

	$strWhere.=" AND today > $first_date AND today<=$last_date";
	$obj_time_visit = SysGetList("ez_member_time_visit",array(), " AND member='".$username."' $strWhere",false);
	if($obj_time_visit->Num_rows()>0){
		$arr_value = $arr = array();
		while($row = $obj_time_visit->Fetch_Assoc()) { 
			$arr_value[] = convert_to_munit($row['total_time']);
			$arr[$row['day']] = $row;
		}

		$max_total = max($arr_value); 
		if($max_total<=30) $max_value=30;
		else if($max_total>30 && $max_total<=50) $max_value=50;
		else if($max_total>50 && $max_total<=100) $max_value=100;
		else if($max_total>100 && $max_total<=200) $max_value=200;
		else if($max_total>200 && $max_total<=300) $max_value=300;
		else $max_value=300;
		$max_value = $max_total+5;
		$step = ceil($max_total/10);

		$list_value = array();
		for ($i=0; $i < $max_value; $i+=$step) { 
			$list_value[] = $i;
		}
		$list_value[] = $max_value;
		echo json_encode(array('status'=>'yes','data'=>$arr,'list_value'=>$list_value,'max_value'=>$max_value,'step'=>$step));
	}else{
		echo json_encode(array('status'=>'yes','data'=>null));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();