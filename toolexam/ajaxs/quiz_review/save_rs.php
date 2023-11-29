<?php
session_start();
define('incl_path_par','../../../global/libs/');
define('libs_path','../../../libs/');
define('incl_path','../../global/libs/');
require_once(incl_path_par.'gfconfig.php');
require_once(incl_path_par.'gffunc.php');
require_once(incl_path_par.'config_api.php');
require_once(incl_path_par.'gfinit.php');
require_once(incl_path_par.'gffunc_wallet.php');
require_once(incl_path_par.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$username=getInfo('username');
$exam_id=isset($_POST['exam_id'])? $_POST['exam_id']:'';
$time_start=isset($_POST['time_start'])? (int)$_POST['time_start']:'';
$data=isset($_POST['data'])? $_POST['data']:'';
$data_ans=isset($_POST['data_ans'])? $_POST['data_ans']:'';
//var_dump($data_ans); die;
$number_true=isset($_POST['num'])? (int)$_POST['num']:'';
$type_work=isset($_POST['type'])? (int)$_POST['type']:1;// 1 là nhiemvu(work1), 2 là nhiemvu(work), 3 là bài kiểm tra (exam);
$data_ans=json_encode($data_ans);

// lấy info work
$info_work=$data['info_exam'];
$number_quiz=$info_work['total_quiz'];
$pass_percent=$info_work['pass_percent'];
$today=$info_work['today'];
$status=$info_work['status'];
if($number_true>=$pass_percent){
	$status=2;
	
}else $status=1;

// lấy ngày today
$arr_date=getDateReport(1); 
$first_date=isset($arr_date['first'])? $arr_date['first']:'';
$last_date=isset($arr_date['last'])? $arr_date['last']:'';
$strwhere=" AND start_time > $first_date AND start_time<=$last_date";

// update kết quả vào work
$data=array();
$post_data=$arr = array();
$arr['key']   = PIT_API_KEY;
$arr['data_answer']=$data_ans;
$arr['exam_id']=$exam_id;
$arr['start_time']=time();
$arr['time_learn']=time() - $time_start;
$arr['number_true']=$number_true;
$arr['type_tbl']=$type_work;

$post_data['data'] = encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_SAVEWORK,json_encode($post_data));

if(isset($rep['data']) && $rep['data']=='success'){
	
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = getInfo('username');
	$json['strwhere'] 	= addslashes($strwhere);
	$json['grade'] 	= getInfo('grade');
	$json['type_tbl'] 	= $type_work;
	$version = getInfo('grade_version');
	$json['version'] 	= $version == "N/a" ? $json['grade']."_V01" : $version;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_NHIEMVU,json_encode($post_data)); 
	$list_work=array();
	if(is_array($rep['data_obj']) && count($rep['data_obj']) > 0) $list_work = $rep['data_obj'];	
	// cong thuong
	$flag_hoanthanhbai=$flag_dalam=0;
	foreach($list_work as $key=>$item){ 
		$status=$item['status'];
		if($status==1) $flag_dalam++;
		if($status==2) $flag_hoanthanhbai++;
	}
	//AddBonus type: 1 - login, 2- vào làm bài, 3 - làm bài tập, 4 là xem lý thuyết bất kỳ 5 -hoàn thành nv, 6 -hoàn thành xuất sắc
	// user- vào làm bài
	if($flag_dalam==1) AddBonus($username, 1, 2);// làm 1 quiz bất kỳ
	if($flag_dalam==2) AddBonus($username, 2, 2); //2 quiz bất kỳ
	if($flag_dalam==5) AddBonus($username, 5, 2);//5 quiz bất kỳ

	// user- hoàn thành bài
	if($number_true>=$pass_percent){
		AddBonus($username, 1, 5);
		
	// user- hoàn thành xuất sắc
		$tile=($number_true/$number_quiz)*100;
		if($tile>=70){
			AddBonus($username, 1, 6);
		}
	}
	// thưởng đạt mốc sao
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$sql ="SELECT SUM(money) as number FROM ez_wallet_histories WHERE username='$username' AND type=1 AND  bonus_configid > 0 $strwhere";
	 $obj = new CLS_MYSQL;
    $obj->Query($sql);
    $row=$obj->Fetch_Assoc();
    $number=$row['number'];
	$star=$diamond=0;
	if($number>=10 && $number<20){
		$star=2;
		$diamond=5;
		$cur_star=10;
	}
	if($number>=20 && $number<39){
		$star=5;
		$diamond=20;
		$cur_star=20;
	}
	if($number>=40 && $number<59){
		$star=10;
		$diamond=50;
		$cur_star=40;
	}
	if($number>=60){
		$star=20;
		$diamond=100;
		$cur_star=60;
	}
	if($star>0 && $diamond>0){
		$time=time();
		$note="+".$star." sao thưởng khi đạt ngưỡng ".$cur_star." sao";
		$count=SysCount("ez_wallet_histories", " AND username='$username' AND money='$star' AND type='1' AND bonus_configid='0' $strwhere");
		if($count==0){
			$sql ="INSERT INTO ez_wallet_histories(username,money,cdate,note, type, cuser,status, bonus_configid)
			VALUES('$username','$star','$time','$note','1','$username','1','0')";
			 $obj = new CLS_MYSQL;
			if($obj->Query($sql)==true){
				updatePushWallet('ez_wallet_s',$username,$number_star);
				$note="+".$diamond." kim cương thưởng khi đạt ngưỡng ".$cur_star." sao";
				$sql ="INSERT INTO ez_wallet_histories(username,money,cdate,note, type, cuser,status, bonus_configid)
				VALUES('$username','$diamond','$time','$note','2','$username','1','0')";
				 $obj = new CLS_MYSQL;
				if($obj->Query($sql)==true) updatePushWallet('ez_wallet_d',$username,$number_star);
			}
		}
	}
	
}

?>

