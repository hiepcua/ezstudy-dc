<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$date = getdate();
$day_date=$date['wday'];
$type_user = getInfo('utype');
$type=isset($_POST['type'])? (int)$_POST['type']:0;
if($type==1){
	if(isset($_SESSION['show_nv_khung'])) unset($_SESSION['show_nv_khung']);
	if(isset($_SESSION['USER_JOININ'])) unset($_SESSION['USER_JOININ']);
	LogOut(getInfo('user'));
}
if(isset($_POST['last_time'])){
	$last_time=isset($_POST['last_time'])? (int)$_POST['last_time']:0;
	$total_time=isset($_POST['total_time'])? (int)$_POST['total_time']:0;
	 if($day_date==0) $day=8;
	else $day=$day_date+1;
	
	$user=isset($_POST['user'])? addslashes($_POST['user']):0;
	
	if($last_time!='' && $total_time!='' && $user!='' && $type_user=='hocsinh'){
		 $arr2=$array=array();
		 $arr2['last_time']=$array['last_time']=$last_time;
		 $arr2['total_time']=$array['total_time']=$total_time;
		 $array['member']=$user;
		 $array['today']=time();
		 $array['day']=$day;
		 // lấy ngày today
		$arr_date=getDateReport(1); 
		$first_date=isset($arr_date['first'])? $arr_date['first']:'';
		$last_date=isset($arr_date['last'])? $arr_date['last']:'';
		$strwhere=" AND today > $first_date AND today<=$last_date AND member='$user'";
		 $count=SysCount('ez_member_time_visit', "$strwhere");
		if($count<1) SysAdd('ez_member_time_visit', $array);
		else{
			$sql="UPDATE ez_member_time_visit SET total_time=total_time+$total_time, last_time='$last_time' WHERE 1=1 $strwhere";

			$objdata=new CLS_MYSQL;
			$objdata->Query($sql);
		}
	}
	
}

?>