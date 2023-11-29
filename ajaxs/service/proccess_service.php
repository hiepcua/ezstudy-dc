<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(isset($_POST['username'])){
	if(api_get_member_service()==true) $type=2;// gia han
	else $type=1;//dang ky moi
	$id=isset($_POST['id'])?(int)$_POST['id']:'';
	$username=isset($_POST['username'])? addslashes($_POST['username']):'';
	$price=isset($_POST['price'])? (int)$_POST['price']:'';
	$arr['key']   = PIT_API_KEY;
	$arr['packet'] = $id;
	$arr['month'] = $id;
	$arr['member'] = $username;
	$arr['price'] = $price;
	$arr['type'] = $type;
	$url=API_REGISTER_SERVICE;
	$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$reponse_data = Curl_Post($url,json_encode($post_data));
	if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
		if(isset($reponse_data['data']) &&  $reponse_data['data']=="success"){
			die("success");
		}
	}
	else if(isset($reponse_data['data']) &&  $reponse_data['data']=="exist"){
		die("registed");
	}
	else die('1');
}
