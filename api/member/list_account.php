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
$strwhere= isset($data['strwhere']) ? $data['strwhere'] : '';
$key	= isset($data['key']) ? antiData($data['key']) : '';
$page	= isset($data['page']) ? (int)$data['page'] : 1;
$max_row	= isset($data['max_row']) ? (int)$data['max_row'] : 30;
$start=($page-1)*$max_row;
if($key == PIT_API_KEY){
	$saler = isset($data['saler'])?antiData($data['saler']):'';
	if($saler == "") {
		echo json_encode(array('status'=>'no','data'=>"saler_empty"));
	}else {
		// get tài khoản
		$count = SysCount("ez_member"," AND saler='$saler' $strwhere");
		$rs = SysGetList("ez_member",array()," AND saler='$saler' $strwhere ORDER BY cdate DESC LIMIT $start,$max_row");
		echo json_encode(array('status'=>'yes','data'=>$rs,'total_rows'=>$count));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();