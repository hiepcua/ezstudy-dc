<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$username=getInfo('username');
	$item_id=isset($_POST['item_id'])? addslashes($_POST['item_id']):'';
	SysDel('ez_member', "username='$item_id' AND par_user='".$username."'");
}
?>