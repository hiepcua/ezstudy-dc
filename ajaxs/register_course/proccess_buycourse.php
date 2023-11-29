<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');

require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'config_sanpham.php');

if(isset($_POST['username'])){
	$sl_product=isset($_POST['sl_product'])?antiData($_POST['sl_product']):"";
	$sl_child=isset($_POST['sl_child'])?antiData($_POST['sl_child']):"";
	$txt_note=isset($_POST['txt_note'])?antiData($_POST['txt_note']):"";
	$json=array();
	$json['key']=PIT_API_KEY;
	$json['user_use']=$sl_child;
	$json['user_buy']=$username;
	$json['id_product']=$sl_product;
	$json['note']=$txt_note;
	$json['partner_code']=PARTNER_CODE;
	$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
	$url=API_PUSH_DON_HANG;
	$reponse_data=Curl_Post($url,json_encode($post_data));
	

	if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
		if(isset($reponse_data['data']) &&  $reponse_data['data']=="success"){
			die("success");
		}
	}else if(isset($reponse_data['status']) && $reponse_data['status']=='no'){
		 if(isset($reponse_data['data']) &&  $reponse_data['data']=="exist"){
			 die("registed");
			//echo "<script language='javascript'>alert('Bạn đã đăng ký gói này');</script>";
		}
	}
}
