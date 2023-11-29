<?php
session_start();
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

/*
username : Tài khoản member
count_work : Số bài tập cần thực hiện
type_work : 1 là đăng nhập, 2 là làm nv, 3 là làm bài tập, 4 xem bài bài lý thuyết, 5 hoàn thành, 6 ht xuất sắc
*/

$username = isset($data['username']) ? antiData($data['username']) : '';
$count_work = isset($data['count_work']) ? antiData($data['count_work']) : '';
$type_work = isset($data['type_work']) ? antiData($data['type_work']) : '';

function updatePushWallet($tbl_wallet,$username,$number){
	$time=time();
	$sql="INSERT INTO $tbl_wallet (username, money, money_total,cdate, mdate, status)
		VALUES ('$username', '$number', '$number','$time','$time','1')
		ON DUPLICATE KEY UPDATE
		  mdate     = '$time',
		  money     = money+$number,
		  money_total = money_total+$number";
	
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}

function getStarDiamond($number_work, $type){//$type 1 là đăng nhập, 2 là làm nv, 3 là làm bài tập, 4 xem bài bài lý thuyết, 5 hoàn thành, 6 ht xuất sắc
	$item = SysGetList("ez_bonus_config",array()," AND number='$number_work' AND type='$type'");
	
	$conf_id=$num_star=$num_diamond='';
	if(isset($item[0])){
		$num_star=$item[0]['num_star'];
		$num_diamond=$item[0]['num_diamond'];
		$conf_id=$item[0]['id'];
	}
	return array('num_star'=>$num_star, 'num_diamond'=>$num_diamond,'conf_id'=>$conf_id);
}


function AddBonus($username, $count_work, $type_work){
	$flag=true;
	//get date today
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";

	//$count_work là số bài thực hiện, $type_work là type thưởng:1 là login, 2 là vào làm bài 1,2,5 bài, 3 là hoàn thành bài
	$arr_bonus=getStarDiamond($count_work,$type_work);
	$number_star=$arr_bonus['num_star'];
	$number_diamond=$arr_bonus['num_diamond'];
	$conf_id=$arr_bonus['conf_id'];
	$count=SysCount('ez_wallet_histories'," AND username='$username' AND bonus_configid='$conf_id' $strwhere");
	
	if($count>0) $flag=false;
	if($number_star=='' OR $number_diamond=='') $flag=false;
	if($flag==true){
		$note='';
		if($type_work==1) $note="đăng nhập hệ thống";
		if($type_work==2) $note="khi bạn làm ".$count_work." bài bất kỳ trong nhiệm vụ";
		if($type_work==3) $note="khi bạn làm ".$count_work." bài tập bất kỳ";
		if($type_work==4) $note="khi bạn xem lý thuyết 1 bài bất kỳ";
		if($type_work==5) $note="khi bạn đã hoàn thành ".$count_work." bài bất kỳ";
		if($type_work==6) $note="khi bạn đã hoàn thành xuất sắc ".$count_work." bài bất kỳ";
		
		$arr=array();
		$arr['cuser']=$arr['username']=$username;
		$arr['type']=1;// 1 là star, 2 là kim cương
		$arr['status']=1;
		$arr['cdate']=time();
		$arr['money']=$number_star;
		$arr['bonus_configid']=$conf_id;
		$arr['note']="+".$number_star." sao ".$note;
		
		$rs1=updatePushWallet('ez_wallet_s',$username,$number_star);
		$rs2=SysAdd('ez_wallet_histories',$arr,1);	
		
		$arr['type']=2;
		$arr['money']=$number_diamond;
		$arr['note']="+".$number_diamond." kim cương ".$note;
		$rs1=updatePushWallet('ez_wallet_d',$username,$number_diamond);
		$rs2=SysAdd('ez_wallet_histories',$arr,1);

		if($rs1 && $rs2){
			return "success";
		}else{
			return "error";
		}
	}else{
		return "finished";
	}
}

if($key == PIT_API_KEY){
	if($username=="" || $type_work==""){
		echo json_encode(array('status'=>'no','data'=>"Missing param"));
		die();
	}

	$member = api_get_member_info($username);
	if(count($member)<=0){
		echo json_encode(array('status'=>'no','data'=>"Username fail"));
		die();
	}

	$result = AddBonus($username, $count_work, $type_work);
	if($result=="success"){
		echo json_encode(array('status'=>'yes','data'=>"success"));
	}else if($result=="finished"){
		echo json_encode(array('status'=>'yes','data'=>"finished"));
	}else{
		echo json_encode(array('status'=>'yes','data'=>"error"));
	}
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();