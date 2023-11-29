<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(libs_path.'cls.mysql.php');
$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username = isset($data['username']) ? antiData($data['username']):'';

if($key == PIT_API_KEY){
	if($username == "")
		echo json_encode(array('status'=>'no','data'=>"incomplete_data"));
	else {
		$total_star = countTotalWallet('ez_wallet_s',$username);
		$total_diamond = countTotalWallet('ez_wallet_d',$username);
		$arr = array('total_star'=>$total_star,'total_diamond'=>$total_diamond);
		echo json_encode(array('status'=>'yes','data'=>$arr));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();