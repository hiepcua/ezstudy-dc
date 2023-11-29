<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(!isLogin()) die("E01");

if(isset($_POST['txt_username'])) {
	$user=addslashes($_POST['txt_username']);
	$fullname=isset($_POST['txtname'])? addslashes($_POST['txtname']):'';
	$phone=isset($_POST['txtphone'])? addslashes($_POST['txtphone']):'';
	$email=isset($_POST['txtemail'])? addslashes($_POST['txtemail']):'';
	$birthday=isset($_POST['txtbirthday'])? addslashes($_POST['txtbirthday']):0;
	$address=isset($_POST['txtaddress'])? addslashes($_POST['txtaddress']):'';
	$gender=isset($_POST['optgender'])? addslashes($_POST['optgender']):'';
	$arr=array();
	$arr['fullname']=$fullname;
	$arr['phone']=$phone;
	$arr['email']=$email;
	$arr['birthday']=strtotime($birthday);
	$arr['address']=$address;
	$arr['gender']=$gender;
	SysEdit('ez_member',$arr, " username='$user'");
	echo 'success';
}?>