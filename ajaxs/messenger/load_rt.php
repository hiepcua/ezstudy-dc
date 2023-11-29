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
 $type=isset($_POST['type'])? $_POST['type']:'';
 if($data!=''){
	 
	$mes=$data['message'];
	$fullname=$data['fullname'];
	$username=$data['username'];
	if($type==1){
		 ?>
		 <div class="item-noti">
		<p><?php echo $mes;?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $username;?></span></p>
		</div>
		 <?php 
	 }
	 else{
		$str=explode('-',un_unicode($username));
		$list='';
		foreach($str as $val){
			$list.=substr($val,0,1);
		}
		$leng=strlen($list);
		if($leng>=2) $str=substr($list,0,2);
		else $str=substr($list,0,2);
	   ?>
		<div class="item message-item outgoing-message">
			<div class="avatar"><?php echo $str;?></div>
			<div class="content-comment">
				<h4 class="txt-user"><?php echo $username;?></span></h4>
				<p class="txt"><?php echo $mes;?></p>
				<span class="txt-label"><?php echo date('d-m-Y H:i:s');?></span>
				
			</div>
		</div>
	<?php }?>
 <?php }?>