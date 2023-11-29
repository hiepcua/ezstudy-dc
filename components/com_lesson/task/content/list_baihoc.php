<div class="intro">
<?php 
$username=getInfo('username');
 AddBonus($username, 1, 4);
global $intro;
global $guide;
if($row['intro']!="") {
	echo "<h3>Mô tả:</h3>";
	echo stripslashes($intro);
} ?></div>
<div class="intro"><?php if($guide!="") {
	echo "<h3>Hướng dẫn học:</h3>";
	echo stripslashes($guide);
} ?>
</div>