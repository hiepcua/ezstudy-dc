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

if(isset($_POST['username'])) {
	$username = antiData($_POST['username']);
	setcookie('MEMBER_CHILD',$username,time() + (86400 * 30), "/");
	$_SESSION['MEMBER_CHILD'] = $username;
	echo "success";
}else{
	echo "error";
}
die();
?>