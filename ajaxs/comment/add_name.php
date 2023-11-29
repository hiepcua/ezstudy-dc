<?php
session_start();
include_once('../../includes/gfinnit.php');
include_once('../../includes/gfconfig.php');
include_once('../../includes/gffunction.php');
include_once('../../libs/cls.mysql.php');
if(isset($_POST['txt_name'])){
    $name=addslashes($_POST['txt_name']);
   echo $name;
    $_SESSION[ROOTHOST.'name_user']=$name;
}
?>
<input name="txt_username" value="<?php echo $name;?>" type="hidden">
