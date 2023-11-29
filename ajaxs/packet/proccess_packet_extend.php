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

$username = isset($_POST['txt_username']) ? antiData($_POST['txt_username']):"";
$month = isset($_POST['txt_packet']) ? (int)$_POST['txt_packet']:0;
$note = isset($_POST['txt_note']) ? antiData($_POST['txt_note']):"";

// Get member info
$json = array();
$json['key'] = PIT_API_KEY;
$json['username'] = $username;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_MEMBER_INFO, json_encode($post_data)); 
$member = array();
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$member = $rep['data'];
}
$grade = $member['grade'];
$cur_packet = $member['packet'];
$edate = $member['edate'];
$sdate = $member['sdate'];
$cur_packet_month = $member['packet_month']!="" ? $member['packet_month']:0;

// Get packet
$json = array();
$json['key'] = PIT_API_KEY;
$json['id'] = $cur_packet;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_PACKET, json_encode($post_data)); 
$arr_packet = array();
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arr_packet = $rep['data'];
}

$packet_conf = isset($arr_packet[$cur_packet]['packet']) ? json_decode($arr_packet[$cur_packet]['packet'], true):array();
$packet_grade = isset($packet_conf[$grade]) ? $packet_conf[$grade]:array();
$packet_month = isset($packet_grade[$month]) ? $packet_grade[$month]:array();
$price = $packet_month['money'];

$arr_return=array();
if(count($member)>0 && $cur_packet!=""){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['member'] = $username;
	$json['packet'] = $cur_packet;
	$json['month'] = $month;
	$json['price'] = $price;
	$json['cdate'] = time();
	$json['status'] = "L0";
	$json['note'] = "Gia hạn thêm";
	$json['type'] = "gia_han";
	$url = API_SERVICE_ADD_ORDER;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$req = Curl_Post($url, json_encode($post_data));

	if(isset($req['data']) && $req['data']=="success"){
		$arr_return['status']='success';
	}else if(isset($req['data']) && $req['data']=="exist"){
		$arr_return['status']='exist';
	}else {
		$arr_return['status']= 'error';
	}
}else{
	// die("Missing param");
	$arr_return['status']= 'Missing param';
	
}
echo json_encode($arr_return);