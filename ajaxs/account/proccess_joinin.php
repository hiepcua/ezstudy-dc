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

if(isset($_POST['id'])) {
	$username=isset($_POST['id'])? addslashes($_POST['id']):'';
	$type=isset($_POST['type'])? (int)$_POST['type']:0;
	if($type==0) $rs=joinInAccount($username);
	else{// back to account chame
		$item = sysGetList("ez_member",array('par_user'), " AND username='".$username."'"); 
		
		if(isset($item[0]['par_user'])){
			$par_user=$item[0]['par_user'];
			
			$rs=joinInAccount($par_user,1);
		}
	}
	if($rs==true) echo 'success';
}?>