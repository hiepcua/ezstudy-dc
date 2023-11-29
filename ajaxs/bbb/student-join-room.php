<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$type = "T01";
$isteacher = "no";
$username = getInfo("username");
$live_id = isset($_POST['live_id']) ? antiData($_POST['live_id']) : '';
if($live_id!="" && $username!="") { 
	$data = array();
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['user'] = $username;
	$json['live_id'] = $live_id;
	$json['type'] = $type;
	$json['isteacher'] = $isteacher;

	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_JOIN_ROOM, json_encode($post_data));

	if($rep['status']=="yes" && $rep['data']!="") {
		echo $rep['data'];
	}else{
		echo "error";
	}
}
die();