<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$username = getInfo('username');	
	//$rs = sysGetList("ez_member",array(), " AND par_user='".$username."' AND status_link = 'yes'"); 
	$rs = sysGetList("ez_member",array(), " AND par_user='".$username."'"); 
	?>
	<form id="frm_action" name="frm_action" method="post" action="">
	<div class="text-center">
		<h4 class="name text-center">Chọn một trong các tài khoản con dưới đây</h4>
		<p>Lưu ý: Bạn chỉ xem được những tài khoản liên kết thành công</p>
	</div>
		<div class="account-list" style="margin-bottom:15px">
			<div class="row">
				<?php if(count($rs)>0) {
				foreach($rs as $item) { 
				$avatar = ROOTHOST.'images/avatar/default-avatar.png';
				if($item['avatar'] != "") $avatar = $item['avatar']; 
				$status_link = $item['status_link']; ?>
				<div class='child item-box item-packet col-md-4 col-xs-12' data-id="<?php echo $item['username'];?>" >
				<div class="icon"><i class="fa fa-check-circle"></i></div>
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
		<div class="form-group text-center">
			<span type="submit" name="cmdsave" id="act-process" class="btn btn-primary">Join ngay</span>
		</div>
	</form>
<?php 
}
else{
    die('Vui lòng đăng nhập hệ thống');
}
?>
<script>
$('#act-process').click(function(){
	var id=$('.item-box.active').attr('data-id');
	if(!id || id=='') alert('Bạn chưa tài khoản Join!');
	var url = "<?php echo ROOTHOST;?>ajaxs/account/proccess_joinin.php";
	$.post(url,{id},function(req){
		console.log(req);
		if(req=='success'){
			//alert('Join in thành công!');
			$('#myModalPopup').modal('hide');
			window.location.href="<?php echo ROOTHOST;?>";
		}
		else alert('Join in không thành công!');
	});
})
$('.item-packet').click(function(){
	$('.item-packet').removeClass('active');
	$(this).addClass('active');
})
</script>
