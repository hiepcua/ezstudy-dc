<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$username=getInfo('username');
	$list_notifi=api_get_notifi($username);
	$number_read=0;
	$class='';
	if(count($list_notifi)>0){	
		foreach($list_notifi as $row){
			$is_read=$row['is_read'];
			$arr_isread=$is_read!=''? json_decode($is_read, true):array();
			if(!in_array($username, $arr_isread)) $number_read++;
		}
	}
	echo $number_read;
}
else die('Cant process');