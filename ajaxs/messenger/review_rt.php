<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'Pusher.php');
 $data=isset($_POST['data'])? $_POST['data']:'';
 if($data!=''){ 
	$mes=$data['message'];
	$fullname=$data['fullname'];
	$username=$data['username'];
		 ?>
		 <div class="item-noti">
		<p><?php echo $mes;?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $username;?></span></p>
		</div>

 <?php }?>