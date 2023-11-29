<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';

$username = isset($data['username']) ? antiData($data['username']) : '';
$type_tbl = isset($data['type']) ? antiData($data['type']) : '';

if($key == PIT_API_KEY){
	$member = api_get_member_info($username);
	if(count($member)<=0){
		echo json_encode(array('status'=>'no','data'=>"Username fail"));
		die();
	}

	// 1: Nhiệm vụ ngày | 0: Nhiệm vụ tuần
	if($type_tbl==1){
		// Lấy ngày today
		$arr_date=getDateReport(1); 
		$first_date=isset($arr_date['first'])? $arr_date['first']:''; // Hôm qua
		$last_date=isset($arr_date['last'])? $arr_date['last']:''; // Hôm nay
		$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
		$item = SysGetList("ez_bonus_config",array(), "");
		$i=0;
		$arr=array();
		foreach($item as $key=>$v) { 
			$label=$class='';
			$name=$v['name'];
			$type=$v['type'];
			$active=0;
			if(get_bonus_histories($username,$v['id'],$strwhere)>0){
				$class="true";
				$active=1;
			}
			$arr[$key]=$v;
			$arr[$key]['active']=$active;
			$arr[$key]['type']=$type;
		}
	}else if($type_tbl==0){
		$arr_date = getDateReport(2); 
		$first_date = isset($arr_date['first'])? $arr_date['first']:'';
		$last_date = isset($arr_date['last'])? $arr_date['last']:'';
		$strwhere=" AND today > $first_date AND today<=$last_date";

		$item = SysGetList("ez_bonus_config",array(), "");
		$arr = array();
		foreach($item as $key=>$v) { 
			$label=$class='';
			$name=$v['name'];
			$type=$v['type'];
			$active=0;
			if(get_bonus_histories($username,$v['id'],$strwhere)>0){
				$class="true";
				$active=1;
			}
			$arr[$key]=$v;
			$arr[$key]['active']=$active;
			$arr[$key]['type']=$type;
		}
	}
	
	echo json_encode(array('status'=>'yes','data'=>$arr));
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();