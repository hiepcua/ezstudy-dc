<?php
global $type_user;
if($type_user == "hocsinh" OR isset($_SESSION['USER_JOININ'])){
	include_once("hocsinh.php");
}else include_once("chame.php");
?>