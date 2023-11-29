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
$packet = isset($_POST['cbo_packet']) ? antiData($_POST['cbo_packet']):"";
$month = isset($_POST['txt_packet']) ? (int)$_POST['txt_packet']:0;

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
$cur_packet_status = $member['packet_status']!="" ? $member['packet_status']:"";

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
$packet_grade = isset($arr_packet[$packet]) ? $arr_packet[$packet]:array();
$packet_item = isset($packet_grade['packet']) && $packet_grade['packet']!="" ? json_decode($packet_grade['packet'], true):array();
$packet_item = isset($packet_item[$grade]) ? $packet_item[$grade]:array();
$item = $packet_item[$month];
$price = $item['money'];

if(count($member)>0 && $packet!=""){
	if($cur_packet==""){ // Lần đầu đăng ký dịch vụ
		$json = array();
		$json['key']   = PIT_API_KEY;
		$json['member'] = $username;
		$json['packet'] = $packet;
		$json['month'] = $month;
		$json['price'] = $price;
		$json['cdate'] = time();
		$json['status'] = "L0";
		$json['note'] = "Đăng ký mới";
		$url = API_SERVICE_ADD_ORDER;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post($url, json_encode($post_data));

		if(isset($req['data']) && $req['data']=="success") 
			die("success"); 
		else 
			die('error');
	}else if($cur_packet=="EZ1"){
		// Nâng cấp gói dịch vụ
		$packet = "EZ2";
		$startime = time();
		$num_day = $cur_packet_month * 30;
		$price_per_day_EZ2 = ceil($price/$num_day);

		if($edate < $startime){ // Gói đã hết hạn
			$arr = array();
			$arr['packet'] = $packet;
			$arr['price_per_day'] = $price_per_day;

			$json = array();
			$json['key']   = PIT_API_KEY;
			$json['username'] = $username;
			$json['arr'] = $arr;
			$url = API_MEMBER_UPDATE_INFO;
			$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$req = Curl_Post($url, json_encode($post_data));

			if(isset($req['data']) && $req['data']=="success") 
				die("success"); 
			else 
				die('error');
		}else{ // Gói chưa hết hạn
			$cur_price_per_day = number_format((float)$member['price_per_day'], 2, '.', '');
			$remaining_days = $edate - time();
			$remaining_days = round($remaining_days / (60 * 60 * 24)); // Số ngày còn hạn
			
			$day2 = $remaining_days / $cur_price_per_day;
			$day2 = number_format((float)$day2, 2, '.', '');
			$endtime = strtotime("+$day2 day");
			
			$arr = array();
			$arr['packet'] = $packet;
			$arr['edate'] = $endtime;
			$arr['price_per_day'] = $price_per_day_EZ2;

			$json = array();
			$json['key']   = PIT_API_KEY;
			$json['username'] = $username;
			$json['arr'] = $arr;
			$url = API_MEMBER_UPDATE_INFO;
			$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$req = Curl_Post($url, json_encode($post_data));

			if(isset($req['data']) && $req['data']=="success") 
				die("success"); 
			else 
				die('error');
		}
	}
}else{
	die("Missing param");
}