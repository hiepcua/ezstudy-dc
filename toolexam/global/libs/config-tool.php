<?php
function isSSL(){
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return true;
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') return true;
	else return false;
}
$REQUEST_PROTOCOL = isSSL()? 'https://' : 'http://';
define('ROOTHOST_PATH',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/toolexam/');
define('ROOTHOST',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('PIT_API_KEY','6b73412dd2037b6d2ae3b2881b5073bc');
define('ROOT_PATH',''); 
define('DC_API','https://dc.ezstudy.vn/api/'); 

?>