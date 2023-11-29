<?php
session_start();
header('Access-Control-Allow-Origin: https://hoc247.net');
ini_set('display_errors',1);
ini_set('max_execution_time', '0');
$start=time();
define('incl_path','../global/libs/');
define('libs_path','../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.postgre.php');
require_once('func_247.php');

if(isset($_POST['data'])){
	$grade=$_POST['grade'];
	$subject=$_POST['subject'];
	$arr_lesson=$_POST['data']; unset($_POST);
	$number=SysCount('ez_grade_subjects'," AND grade='$grade' AND subject='$subject'");
	if($number<=0){
		$n_unit=0;
		$unit_arr=array();
		foreach($arr_lesson as $units){
			$n_unit++;
			$units['title']=str_replace('■','',trim($units['title']));
			$unit_arr[]=array('id'=>$n_unit,'title'=>$units['title']);
			
			$arr=array();
			$n_lesson=0;
			$arr_add=array();
			foreach($units['lession'] as $lesson){
				if(strpos($lesson['link'],'http')!==false){
					$n_lesson++;echo $n_lesson.',';
					$lesson['title']=str_replace('■','',trim($lesson['title']));
					$arr_add=array();
					$arr_add['id']=$grade.'_'.$subject.'_'.$n_unit.'.'.$n_lesson;
					$arr_add['grade']=$grade;
					$arr_add['subject']=$subject;
					$arr_add['unit']=$n_unit;
					$arr_add['title']=$lesson['title'];
					$arr_add['link']=urlencode($lesson['link']);
					$arr_add['status']='no';
					SysAdd('ez_grade_subjects_lesson',$arr_add);
				}
			}
		}
		unset($arr_lesson);
		// cập nhập ez_grade_subjects
		$arr_add=array();
		$arr_add['id']=$grade.'_'.$subject;
		$arr_add['grade']=$grade;
		$arr_add['subject']=$subject;
		$arr_add['cdate']=time();
		$arr_add['status']='yes';
		$arr_add['units']=json_encode($unit_arr,JSON_UNESCAPED_UNICODE);
		SysAdd('ez_grade_subjects',$arr_add);
	}else{
		echo "Dữ liệu đã update rồi!";
	}
}
echo "Len ".(time()-$start).'s';