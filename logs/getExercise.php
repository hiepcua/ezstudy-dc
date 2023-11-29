<?php
session_start();
header('Access-Control-Allow-Origin: https://hoc247.net');
ini_set('display_errors',1);
ini_set('max_execution_time', '0');
define('incl_path','../global/libs/');
define('libs_path','../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.postgre.php');
require_once('func_247.php');

$obj=SysGetList('ez_grade_subjects_lesson',array()," AND status='no' LIMIT 3 ",false);
while($r=$obj->Fetch_Assoc()){
	$lesson_id=$r['id'];
	$url=$r['link'];
	$grade=$r['grade'];
	$subject=$r['subject'];
	$n_unit=$r['unit'];
	getCauhoi(urldecode($url),$grade,$subject,$n_unit,$lesson_id);
	
	$arr=array('status'=>'yes');
	SysEdit('ez_grade_subjects_lesson',$arr," id='$lesson_id'");
}