<div class="text-center">
	<div class="form-group">Chưa có nhiệm vụ học tập.</div>
	<div class="form-group justify-content-center">
		<?php $cur_week = date("W") - 35; if($cur_week < 0) $cur_week = 1; ?>
		<div class="col-md-6 col-xs-12">
			<div class="col-md-8 col-xs-12"><label>Bạn đang học ở tuần số?</label></div>
			<div class="col-md-4 col-xs-12">
				<input type="number" name="nhiemvu_tuan" id="nhiemvu_tuan" min="1" max="37" value="<?php echo $cur_week;?>" class="form-control"/>
			</div>
		</div>
	</div>
	<div class="form-group text-center nhiemvu_msg"></div>
	<div class="clearfix"><a href="javascript:void(0)" class="view-all nhiemvu_add">Tạo nhiệm vụ học tập</a></div>
</div>
<script>
$(".nhiemvu_add").click(function(){
	var tuan = $("#nhiemvu_tuan").val();
	if(parseInt(tuan) < 0 && parseInt(tuan) > 37) {
		$(".nhiemvu_msg").html('<div class="text text-danger">Vui lòng nhập tuần học từ 1 đến 37.</div>');
		$("#nhiemvu_tuan").focus(); return false;
	}
	var url = "<?php echo ROOTHOST;?>ajaxs/nhiemvu/process_add.php";
	$.post(url,{tuan}, function(req){
		console.log(req);
		if(req == "success") {
			showMess("Đã thêm nhiệm vụ học tập cho bạn. Hệ thống tự động tải trang sau 3 giây");
			setTimeout(function(){window.location.reload();},3000);
		}else {
			showMess(req,"error");
		}
	})
})
</script>