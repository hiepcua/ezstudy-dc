<?php if(is_array($_Subjects) && count($_Subjects)>1) { ?>
<div class="card subject_others"><div class="card-block">
	<div class="main-title">Môn học khác</div>
	<div class="row">
		<?php 
		foreach($_Subjects as $k=>$v) { 
		if($gsub == $k) continue; ?>
		<a href="<?php echo ROOTHOST;?>lession/<?php echo $k;?>" class="btn btn-info">
			<i class="fa <?php echo $v['subject_icon'];?>"></i> 
			<?php echo $v['subject_name'];?>
		</a>
		<?php } ?>
	</div>
</div></div>
<?php } ?>