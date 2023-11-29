
<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
define("COMS","member");
define('THIS_COM_PATH',COM_PATH.'com_'.COMS.'/');

if(isLogin()) {
	$task=isset($_GET['task'])?$_GET['task']:'list';
	if(is_file(THIS_COM_PATH.'task/'.$task.'.php')){
		include_once(THIS_COM_PATH.'task/'.$task.'.php');
	}
}
unset($obj); unset($task);	unset($ids);
?>
