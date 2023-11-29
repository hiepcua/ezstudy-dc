<?php 
$type_user = getInfo("utype");
if($type_user=="hocsinh"){
	$username = getInfo("username");
}else{
	$username = $_CHILD_INFO["username"];
}
$_Notification = api_get_mesenger($username);
if(count($_Notification)>0){
	foreach($_Notification as $key=>$item) { 
		?>
		<div class="item-noti">
		<p><?php echo $item['content'];?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $item['by_member'];?></span></p>
		</div>
	<?php 
	}
}