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
include_once(incl_path."config_api.php"); // get data api

$username_chame = isset($_POST['txt_username_chame']) ? antiData($_POST['txt_username_chame']):"";
$username_hocsinh = isset($_POST['txt_username_hocsinh']) ? antiData($_POST['txt_username_hocsinh']):"";
if($username_chame=="" || $username_hocsinh==""){
	echo "Missing param";
	die();
}

// Kiểm tra tài khoản con có tồn tại hay không
$child = api_get_member_info($username_hocsinh);
if(count($child)<1){
	die("member not exist");
}
// Kiểm tra tài khoản con đã có chame hay chưa
if($child['par_user']!="" || $child['status_link']=="yes"){
	die("parent are true");
}

$arr = array();
$arr['par_user'] = $username_chame;

$res = api_update_member($username_hocsinh, $arr);
if($res) 
	die("success"); 
else 
	die('error');