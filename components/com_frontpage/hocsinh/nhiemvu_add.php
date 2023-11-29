<div class="nv-add">
	<?php 
	$_Subjects=api_get_subject();
	$subject_list	= getInfo('subject_list');
	if($subject_list == 'N/a' || $subject_list == '') $arr_subject=array();
	else $arr_subject=explode(',',$subject_list);
	?>
	<h2 class="form-group text-center title">Bạn chưa có nhiệm vụ học tập. Vui lòng đăng ký Môn học:</h2>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 box-add-work">
			<form method="post" id="frm-work">
				<div class="form-group">
					<div class="text-left">
						<label>Bước 1: Chọn môn bạn muốn học</label>
						<select name="txt_subject[]" id="txt_subject" class="select form-control" multiple data-placeholder="Chọn Môn">
							<option>Chọn môn</option>
							<?php
							foreach($_Subjects as $k=> $vl){
								if(in_array($k,$arr_subject)){
									echo '<option value="'.$k.'">'.$vl['subject_name'].'</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="clearfix"></div>
					<div id="data_lestion"></div>
				</div>

				<div class="form-group text-center nhiemvu_msg">
					<div class="clearfix"><a href="javascript:void(0)" class="view-all nhiemvu_add">Tạo nhiệm vụ học tập</a></div>
				</div>
			</form>
		</div>
		<div class="col-md-2"></div>
	</div>
</div>
<?php $subject=addslashes(json_encode($_Subjects));?>
<script>
	$('.select').select2({
		allowClear: true
	});
	var subject='<?php echo $subject;?>';
	
	$("#txt_subject").on("select2:select select2:unselect", function (e) {
		var items= $(this).val();   
		var url = "<?php echo ROOTHOST;?>ajaxs/nhiemvu/list_lession.php";
		$.post(url,{items,subject}, function(req){
			$('#data_lestion').html(req);
		});

	})
	$(".nhiemvu_add").click(function(){
		var form = $('#frm-work');
		var postData = form.serializeArray();
		var url = "<?php echo ROOTHOST;?>ajaxs/nhiemvu/process_add.php";
		$.post(url,postData, function(req){
			// console.log(req);
			if(req == "success") {
				showMess("Đã thêm nhiệm vụ học tập cho bạn. Hệ thống tự động tải trang sau 3 giây");
				setTimeout(function(){window.location.reload();},2000);
			}else if(req == "null"){
				showMess('Dữ liệu tạm hết. Vui lòng quay lại sau',"error");
			}else {
				showMess(req,"error");
			}
		})
	})
</script>