<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'gffunc_comment.php');
require_once(libs_path.'cls.mysql.php');
$subject=isset($_POST['txt_subject']) ? addslashes($_POST['txt_subject']):'';
$to_user=isset($_POST['txt_user']) ? addslashes($_POST['txt_user']):'';
echo "subject".$subject;
die();
if(isset($_POST['txt_content'])){
	$username=getInfo('username');
    $content=addslashes($_POST['txt_content']);
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['by_member'] = $username;
	$json['to_member'] = $to_user;// toi gv nào
	$json['content'] = $content;
	$json['subject'] = $subject;
	$json['grade'] = getInfo('grade');
	$json['type'] = 1;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_ADD_CHAT_TOPIC,json_encode($post_data)); 
	
	if(isset($rep['data']) && $rep['data']=='success') {
		$str=explode('-',un_unicode($username));
		$list='';
		foreach($str as $val){
			$list.=substr($val,0,1);
		}
		$leng=strlen($list);
		if($leng>=2) $str=substr($list,0,2);
		else $str=substr($list,0,2);
		$topic_id='';
		content_topic($topic_id,$content,'','',$username);
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