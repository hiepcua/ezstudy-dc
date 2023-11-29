<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(isLogin()) {
	$username = getInfo('username');	
	$rs = sysGetList("ez_member",array(), " AND par_user='".$username."'"); 
	if(count($rs)>0) {
		echo '<div class="row">';
		foreach($rs as $item) { 
			$avatar = ROOTHOST.'images/avatar/default-avatar.png';
			if($item['avatar'] != "") $avatar = $item['avatar']; 
			$status_link = $item['status_link']; 
			echo "<div class='child col-md-4 col-xs-12'>
				<div class='avatar pull-left'><img src='".$avatar."' height='80'/></div>
				<div class='pull-left'>
					<div>Tài khoản</div>
					<div class='name'><b>".$item['username']."</b></div>";
					if($status_link == "yes") 
						echo '<div class="label label-success">Đã xác nhận</div>';
					else 
						echo '<div class="label label-warning">Chưa xác nhận</div>';
			echo "</div>
			</div>";
		} 
		echo '</div>';
	} else 
		echo '<div>Chưa có tài khoản nào được liên kết</div>';
} 