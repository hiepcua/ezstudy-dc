<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
include_once(incl_path."config_api.php"); // get data api

$value = isset($_POST['value'])? (int)$_POST['value']:0;
$username = getInfo('username');
$arr = array();
if($value==1) $arr['status_link']='yes';
else $arr['status_link']='no';

if(api_update_member($username, $arr)) {
	setInfo('status_link','yes');
	echo 'success';
}
else
	echo "error";
die();
?>