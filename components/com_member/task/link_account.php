<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$msg='';
$username = getInfo('username');	
$rs = sysGetList("ez_member",array(), " AND par_user='".$username."'"); 
?>
<div class="card">
	<div class="card-block">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class='header'>
				<h1 class='page-title'>Liên kết tài khoản</h1><hr>
			</div>
			<div class="form-group">
				<label>Lưu ý:</label>
				<ul><li>Khi bạn liên kết tài khoản với học sinh, bạn sẽ xem được kết quả/ tiến trình học của học sinh đó.</li>
					<li>Bạn có thể liên kết với nhiều tài khoản học sinh, nhưng chỉ xem được kết quả/ tiến trình học sau khi học sinh đó xác nhận liên kết.</li>
					<li>Trong trường hợp tài khoản học sinh mà bạn muốn liên kết có cùng email hoặc số điện thoại với bạn thì liên kết đó sẽ tự động được xác nhận.</li>
					<li>Khi một tài khoản học sinh muốn liên kết với bạn, bạn sẽ xem được kết quả/ tiến trình học của học sinh đó.</li>
				</ul>
			</div>
			<div class="form-group row">
				<div class="col-md-4 col-xs-12 box_search_link">
					<input type="text" name="link_user" id="link_user" placeholder="Nhập tài khoản cần liên kết" class="form-control"/>
				</div>
				<div class="col-md-3 col-xs-12">
					<button id="btn_link_user" type="button" class="btn btn-primary">Liên kết tài khoản</button>
				</div>
				<div class="col-md-5 col-xs-12 link_msg"></div>
			</div>
			<div class="account-list">
			<div class="row">
				<?php if(count($rs)>0) {
				foreach($rs as $item) { 
				$avatar = ROOTHOST.'images/avatar/default-avatar.png';
				if($item['avatar'] != "") $avatar = $item['avatar']; 
				$status_link = $item['status_link']; ?>
				<div class='child col-md-4 col-xs-12'>
					<div class='avatar pull-left'><img src="<?php echo $avatar;?>" height="80"/></div>
					<div class="pull-left">
						<div>Tài khoản</div>
						<div class='name'><b><?php echo $item['username'];?></b></div>
						<?php if($status_link == "yes") { ?>
						<div class="label label-success">Đã xác nhận</div>
						<?php } else { ?>
						<div class="label label-warning">Chưa xác nhận</div>
						<?php } ?>
					</div>
				</div>
				<?php } 
				} else { ?>
				<div>Chưa có tài khoản nào được liên kết</div>
				<?php } ?>
			</div>
			</div>
		</div>
	</div>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#btn_link_user").click(function(){
		var link_user = $("#link_user").val();
		if(link_user == "") {
			$("#link_user").focus();
			return false;
		}
		$(".link_msg").html("<i class='fa fa-spin'></i>");
		var url = "<?php echo ROOTHOST;?>ajaxs/member/process_link_user.php";
		$.post(url,{link_user},function(req){
			console.log(req);
			$(".link_msg").html("");
			if(req == "not_found") {
				$(".link_msg").html("<div class='alert alert-danger'>Không tìm thấy tài khoản này.</div>");
			}else if(req == "not_allow") {
				$(".link_msg").html("<div class='alert alert-danger'>Tài khoản đã được liên kết với tài khoản phụ huynh.</div>");
			}else if(req == "over") {
				$(".link_msg").html("<div class='alert alert-danger'>Bạn không thể tạo thêm tài khoản cho con.</div>");
			}else if(req == "success") {
				$(".link_msg").html("<div class='alert alert-success'>Đã liên kết tài khoản thành công.</div>");
				// load lại ds tài khoản
				var url = "<?php echo ROOTHOST;?>ajaxs/member/get_link_account.php";
				$.get(url,function(req){
					$(".account-list").html(req);
				})
			}else{
				$(".link_msg").html("<div class='alert alert-danger'>"+req+"</div>");
			}
		})
	})
})
</script>