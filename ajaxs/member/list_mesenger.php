<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$username = isset($_POST['txt_user']) ? antiData($_POST['txt_user']):'';
if(isLogin()) {
	$_Notification = api_get_mesenger($username);
	if(count($_Notification)>0){
		foreach($_Notification as $key=>$item) { 
			?>
			<div class="item-noti">
			<p><?php echo $item['content'];?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $item['info_tomember'];?></span></p>
			</div>
		<?php 
		}
	}
}
?>