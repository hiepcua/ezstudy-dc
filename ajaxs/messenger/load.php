<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'Pusher.php');
require_once(libs_path.'cls.mysql.php');
$username=isset($_POST['data']) ? addslashes($_POST['data']):'';
$this_user=getInfo('username');
$group_chat=$this_user."_".$username;
//var_dump($group_chat);
	$json=array();
	$json['key']   = PIT_API_KEY;
	$json['group_chat'] =$group_chat;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_MESSENGER,json_encode($post_data)); 
	if(is_array($rep['data']) && count($rep['data'])>0) {
		foreach($rep['data'] as $item){
		$username=$item['by_member'];
		$content=$item['content'];
		$type=$item['type'];
		$str=explode('-',un_unicode($username));
		$list='';
		foreach($str as $val){
			$list.=substr($val,0,1);
		}
		$leng=strlen($list);
		if($leng>=2) $str=substr($list,0,2);
		else $str=substr($list,0,2);
		?>
		<div class="item message-item <?php if($type==2) echo 'outgoing-message';?>">
			<div class="avatar"><?php echo $str;?></div>
			<div class="content-comment">
				<h4 class="txt-user"><?php echo $username;?></span></h4>
				<p class="txt"><?php echo $content;?></p>
				<span class="txt-label"><?php echo date('d-m-Y H:i:s');?></span>
				
			</div>
		</div>
		<?php
}
}
?>