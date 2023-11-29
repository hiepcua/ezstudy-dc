<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(!isLogin()){
	$type=isset($_POST['type'])? (int)$_POST['type']:1;//type 1 là bài tập, 2 là lý thuyết
	$bonus_configid=isset($_POST['bonus_configid'])? (int)$_POST['bonus_configid']:0;
	/*
	$item = SysGetList("ez_bonus_config",array()," AND id='$bonus_configid'");
	$num_star=$num_diamond='';
	if(isset($item[0])){
		$num_star=$item[0]['num_star'];
		$num_diamond=$item[0]['num_diamond'];
	}
	*/
	$username=getInfo('username');
	
	if($type==1){
		if($bonus_configid==5) AddBonus($username, 1, 3);
		if($bonus_configid==6) AddBonus($username, 2, 3);
	}
	else{
		if($bonus_configid==7) AddBonus($username, 2, 4);
	}
}

