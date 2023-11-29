<?php 
$this_grade 	= getInfo('grade');
$this_version 	= getInfo('grade_version'); 
$this_subject	= getInfo('subject_list');
$check_all=$active_all='';
if($this_subject == 'N/a' || $this_subject == ''){
	$arr_subject=array();
	$check_all='checked';
	$active_all='active';
}
else $arr_subject=explode(',',$this_subject);
$_Grade_version=api_get_version();
$_Subjects=api_get_subject($this_grade);
$count=0;
global $type_subject;
?>
<div class=" welcome-page">
	<div class="card">
		<div class="card-block">
			<?php if($type_subject==''){?>
				<h2 class="page-title text-center">Chào <?php echo getInfo("fullname");?>, </h2>
				<p class="text-center"  style="font-size: 16px">Cùng bắt đầu luyện tập với bộ sách và môn học phù hợp với bạn nhé</p>
			<?php } ?>

			<form method="post" id="frm-subject" class="box-subject">
				<?php
				if(is_array($_Grade_version) && count($_Grade_version)>0) {
					$count=count($_Grade_version);
					?>
					<p style="font-size: 16px">1 Chọn bộ sách</p>
					<div class="err-label" id="err_version"></div>
					<div class="grade_version text-center" id="">
						<?php 
						foreach($_Grade_version as $k=>$v) {
							$class=$checked='';
							if($this_version==$v['id']){
								$checked='checked';
								$class='active';
							}
							echo "<div class='box' >
							<span class='item_link item ".$class."' data-id='".$k."' data-name='".$v['title']."'><input ".$checked." class='icon-act' type='checkbox' name='txt_version[]' value='".$v['id']."' id='txt_version".$k."'>
							<i class='fa fa-3x fa-book'></i> <div>".$v['title']."</div>
							</span>
							</div>";
						}?>
					</div>
				<?php }
				else echo '<input type="hidden" name="txt_version[]" value="'.$this_version.'">';		
				?>
				<div class="grade_subject text-left" id="" style='padding:15px;'>
					<p  style="font-size: 16px"><?php echo $count>0? '2':'';?> Lựa chọn các môn học bạn muốn học</p>
					<div class="err-label" id="err_subject"></div>
					<div class="item item-subject subject-all <?php echo $active_all;?>" id="all_item">
						<div class="item-all-name">
							<h4 class="name">Chọn tất cả</h4>
						</div>
					</div>
					<?php 
					$i=0;
					if(count($_Subjects)>0){ 			
						foreach($_Subjects as $k=>$v) {
							$class=$checked='';
							if(in_array($k,$arr_subject)){

								$checked='checked';
								$class='active';
							}
							$i++; ?>
							<div class=" item item-subject box-item subject<?php echo $i;?> <?php echo $class;?> <?php echo $active_all;?>" data-id="<?php echo $k;?>">
								<input <?php echo $checked;?> <?php echo $check_all;?>  class="icon-act" type="checkbox" name="txt_subject[]" value="<?php echo $k;?>" id="txt_subject<?php echo $k;?>">
								<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
								<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
				<div class="form-group text-center nhiemvu_msg">
					<div class="clearfix"><a href="javascript:void(0)" class=" btn btn-primary" id="save_action"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu lại</a></div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	var count='<?php echo $count;?>'
	$(".grade_version .item").click(function(){
		$('#err_version').html('');
		$(".grade_version .item").removeClass('active');
		var id=$(this).attr('data-id');
		$(".grade_version .icon-act").prop("checked", false);
		document.getElementById('txt_version'+id).checked = true;
		$(this).addClass('active');
	})

	$("#all_item").click(function(){
		$(".grade_subject .item").addClass('active');
		$(".grade_subject .icon-act").prop("checked", true);
	})

	$('.box-item').click(function(){
		$('#err_subject').html('');
		$(this).addClass('active');
		var id=$(this).attr('data-id');
		var checked = $('#txt_subject'+id).is(':checked');
		if (checked===false){
			$('#txt_subject'+id).prop("checked", true); 
		}
		else{
			$('#txt_subject'+id).prop("checked", false); 
			$(this).removeClass('active');
		}
		$("#all_item").removeClass('active');
	})

	$("#save_action").click(function(){
		if(count>0){
			check_version = $('input[name="txt_version[]"]:checked').length;
			if(check_version==0){
				$('#err_version').html('Vui lòng chọn Version bộ sách');
				return false;
			}
		}
		var check_subject = $('input[name="txt_subject[]"]:checked').length;

		if(check_subject==0){
			$('#err_subject').html('Vui lòng chọn Các môn bạn muốn học');
			return false;
		}
		var form = $('#frm-subject');
		var postData = form.serializeArray();
		var url = "<?php echo ROOTHOST;?>ajaxs/grade_version/process_add.php";
		$.post(url,postData, function(req){
			if(req == "success") {
				var url = "<?php echo ROOTHOST;?>lession-list";
				showMess("Đã lưu thông tin thành công. Hệ thống tự động tải trang sau 3 giây");
				setTimeout(function(){window.location.reload();},2000);
			}else {
				showMess(req,"error");
			}
		})
	})
</script>