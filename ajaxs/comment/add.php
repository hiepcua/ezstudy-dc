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
$topic_id=isset($_POST['txt_topicid']) ? addslashes($_POST['txt_topicid']):'';
$to_user=isset($_POST['txt_user']) ? addslashes($_POST['txt_user']):'';
if(isset($_POST['txt_content'])){
	$username=getInfo('username');
	$fullname=getInfo('fullname');
    $content=addslashes($_POST['txt_content']);
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['by_member'] = $username;
	$json['to_member'] = $to_user;
	$json['content'] = $content;
	$json['topic_id'] = $topic_id;
	$json['type'] = 1;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_ADD_CHAT,json_encode($post_data)); 
	if(isset($rep['data']) && $rep['data']=='success') {
		$str=explode('-',un_unicode($username));
		$list='';
		foreach($str as $val){
			$list.=substr($val,0,1);
		}
		$leng=strlen($list);
		if($leng>=2) $str=substr($list,0,2);
		else $str=substr($list,0,2);
		?>
		<div class="item">
			<div class="avatar"><?php echo $str;?></div>
			<div class="content-comment">
				<h4 class="txt-user"><?php echo $username;?></span></h4>
				<p class="txt"><?php echo $content;?></p>
				<span class="txt-label">Vừa mới đăng</span>
				
			</div>
		</div>
		<?php
		$username=isset($_SESSION['name_user'])? $_SESSION['name_user']:'';	
		/*pus notice*/
		$data['message'] = $content;
		$data['fullname'] = $fullname;
		pushRealTime($data, 'RT_messenger');
	}
}
?>
<script>
   
</script>