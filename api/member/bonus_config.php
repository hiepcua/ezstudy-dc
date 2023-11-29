<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
if($key == PIT_API_KEY){
	$packet_ez = getInfo('service');
	if($packet_ez == 'N/a' || $packet_ez == '') $packet_ez=array();
	$username = getInfo('username');
	if(count($packet_ez)>=1) $type_tbl=1;
	else $type_tbl=0;
	// lấy ngày trong tuần
	$arr_date=getDateReport(2); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	//if($type_tbl==1) $strwhere=" AND indate > $first_date AND indate<=$last_date";
	if($type_tbl==1) $strwhere_nv=" ";
	else $strwhere_nv=" AND today > $first_date AND today<=$last_date";
	$strwhere_nv="";
	$_Nhiemvu=getDataNhiemVu($strwhere_nv,$type_tbl);
	// lấy ngày today
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	
	$item = SysGetList("ez_bonus_config",array(), "");
	$i=0;
	$arr=array();
	foreach($item as $key=>$v) { 
		$label=$class='';
		$name=$v['name'];
		$type=$v['type'];
		$active=0;
		if(get_bonus_histories($username,$v['id'],$strwhere)>0){
			$class="true";
			$active=1;
		}
		$arr[$key]=$v;
		$arr[$key]['active']=$active;
		$arr[$key]['type']=$type;
	}
	//echo '<pre>';
	//var_dump($arr);
	//echo '</pre>';
	
	echo json_encode(array('status'=>'yes','data'=>$arr));
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();