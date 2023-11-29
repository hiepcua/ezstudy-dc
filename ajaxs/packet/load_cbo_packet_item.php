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

$packet = isset($_POST['packet']) ? antiData($_POST['packet']) : '';
$username = isset($_POST['username']) ? antiData($_POST['username']) : '';

// Get packet
$json = array();
$json['key'] = PIT_API_KEY;
$json['id'] = $packet;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_PACKET, json_encode($post_data)); 
$arr_packet = array();
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arr_packet = $rep['data'];
}
$arr_packet = $arr_packet[$packet];

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

$packet_config = isset($arr_packet['packet'])&&$arr_packet['packet']!="" ? json_decode($arr_packet['packet'],true):array();
$packet_grade = isset($packet_config[$grade]) ? $packet_config[$grade]:array();


foreach ($packet_grade as $key => $value) {
    $price = number_format($value['money']);
    echo '<option value="'.$value['id'].'">Gói '.$value['id'].' tháng - Giá '.$price.'đ</option>';
}
?>