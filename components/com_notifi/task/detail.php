<?php 
$this_grade 	= getInfo('grade');
$this_version 	= getInfo('grade_version'); 
$type_user 		= getInfo('utype');
if($type_user=="hocsinh"){
	$member_user = getInfo('username');
}else{
	$member_user = isset($_CHILD_INFO['username']) ? $_CHILD_INFO['username']:"";
}
$id=isset($_GET['id']) ? addslashes($_GET['id']):'';
$flag=true;

// Cập nhật is_read
if($member_user!="" && $id!=""){
	api_update_is_read_notifi($member_user, $id);
}
?>
<div class="card mt-3">
	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="box-notifi detail-notifi">
				<?php
				$list_notifi=api_get_notifi($member_user,$id);
				$row=isset($list_notifi[0])? $list_notifi[0]: array();
				?>

				<h5 class="name" style="font-size: 20px"><?php echo $row['title'];?></h5>
				Lúc: <?php echo ago($row['cdate']);?>&nbsp;&nbsp; Gửi bởi: <?php echo $row['from_send'];?>
				<hr>
				<p class="nt-link intro"><?php echo strip_tags($row['contents']);?></p>
			</div>
		</div>
	</div>
</div>
