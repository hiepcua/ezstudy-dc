<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

$_Subjects=api_get_subject();
$type=isset($_POST['type'])? (int)$_POST['type']:1;//type 1 là bài tập, 2 là lý thuyết
$bonus_configid=isset($_POST['bonus_configid'])? (int)$_POST['bonus_configid']:0;
?>
<form method="post" id="frm-work">
	<div class="form-group">
		<div class="text-left">
			<label>Chọn môn học</label>
			<select name="txt_subject[]" id="txt_subject" class="select form-control" data-placeholder="Chọn Môn">
			<option>Chọn môn</option>
				<?php
				foreach($_Subjects as $k=> $vl){
					echo '<option value="'.$k.'">'.$vl['subject_name'].'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group text-center nhiemvu_msg">
		<div class="clearfix" id="link_select">
		
		</div>
	</div>


</form>
<?php $subject=addslashes(json_encode($_Subjects));?>
<script>
$('#txt_subject').change(function (e) {
		var type='<?php echo $type;?>';
		id=$(this).val();
		str='lession/'+id;
		url='<?php echo ROOTHOST;?>'+str;
		var content='<a href="'+url+'"  class="view-all">Đi tới <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
		$("#link_select").html(content);
	});

</script>