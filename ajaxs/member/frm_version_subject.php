<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'gffunc.php');
$this_grade 	= getInfo('grade');


$_Subjects=api_get_subject($this_grade);

if(count($_Subjects)>0){ 
?>
<form method="post" id="frm-subject" class="box-subject">
<p class="text-center" style="font-size: 16px">Lựa chọn môn học bạn muốn học</p>
	<?php 
	$i=0;				
	foreach($_Subjects as $k=>$v) {
	$i++; ?>
	<div class="item item-subject subject<?php echo $i;?>" data-id="<?php echo $k;?>" data-packet="<?php echo $packet;?>">
		<div class="icon-act"><i class="fa fa-check-circle"></i></div>
		<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
		<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
	</div>
	<?php } ?>
	<div class="form-group text-center nhiemvu_msg">
		<div class="clearfix"><a href="javascript:void(0)" class="view-all" id="save_action"> <i class="fa fa-floppy-o" aria-hidden="true"></i>Lưu lại</a></div>
	</div>
</form>
<?php 
} else echo "<div class='form-group'>Không có dữ liệu gói dịch vụ.</div>" ?>
<script>
$('.item-subject').click(function(){
	//$('.item-subject').removeClass('active');
	$(this).addClass('active');
})
</script>