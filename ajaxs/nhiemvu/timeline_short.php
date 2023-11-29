<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path."config_api.php");

$today = date("w") +1;
$time_mon = array(
	"M01"=>array("2/3","","","1/3","",""),
	"M02"=>array("","3/3","","","","0/3"),
	"M03"=>array("","3/3","","","","0/3"),
	"M04"=>array("","","2/3","","","0/3"),
	"M05"=>array("3/3","","","2/3","",""),
	"M06"=>array("","","","2/3","",""),
	"M07"=>array("","","","","3/3","")	
);
$time_hoc = array("19:30 – 21:00","19:30 – 21:00","","19:30 – 21:00","","19:30 – 21:00");
?>
<div class="table-header">
	<div class="track"><div class="heading">#</div></div>
	<?php for($i=2;$i<8;$i++) { 
	$cls = ''; if($today == $i) $cls = 'active';?>
	<div class="track">
	  <div class="heading <?php echo $cls;?>">Thứ <?php echo $i;?></div>
	</div>
	<?php } ?>
</div>
<?php foreach($_Subjects as $k=>$v) { 
$sub = $v['subject']; ?>
<div class="table-item">
	<div class="entry text-left"><div class="done">
		<?php echo $v['subject_name'];?>
	</div></div>
	<?php for($i=2;$i<8;$i++) { 
	$cls = ''; if($today == $i) $cls = 'active'; ?>
	<div class="entry">
	  <div class="done <?php echo $cls;?>"><?php echo $time_mon[$sub][$i-2];?></div>
	</div>
	<?php } ?>
</div>
<?php } ?>
<div class="table-item">
	<div class="entry text-left"><div class="done">
		<span class="label label-success">Lịch học</span>
	</div></div>
	<?php for($i=2;$i<8;$i++) { 
	$cls = ''; if($today == $i) $cls = 'active';?>
	<div class="entry">
	  <div class="done <?php echo $cls;?>">
	  <?php 
	  if($time_hoc[$i-2] != "") {
		  echo $time_hoc[$i-2];
		  if($today == $i) echo "<div><a href='#' class='readmore'>Vào lớp học</a></div>";
	  } ?>
	  </div>
	</div>
	<?php } ?>
</div>