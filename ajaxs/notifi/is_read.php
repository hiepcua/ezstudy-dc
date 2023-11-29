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
$id=isset($_POST['id'])? addslashes($_POST['id']):'';

if(isLogin() && $id!=''){
	$username=getInfo('username');
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['id']   = $id;
	$json['username']   = $username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_ISREAD_NOTIFI,json_encode($post_data)); 

	if(isset($rep['data']) && $rep['data']=='success') echo 'success';

}
else die('Cant process');