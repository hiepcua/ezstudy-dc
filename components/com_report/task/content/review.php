<?php 
$type_user = getInfo("utype");
if($type_user=="hocsinh"){
	$username = getInfo("username");
}else{
	$username = $_CHILD_INFO["username"];
}

$json = array();
$json['key']   = PIT_API_KEY;
$json['username'] = $username;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_GET_REVIEW,json_encode($post_data)); 
if(isset($rep['data']) && is_array($rep['data']) && count($rep['data'])>0) {
	foreach($rep['data'] as $key=>$item) { 
		?>
		<div class="item-noti">
			<p><?php echo $item['content'];?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $item['info_tomember'];?></span></p>
		</div>
	<?php 
	}
}