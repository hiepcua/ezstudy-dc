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
	$item_id=isset($_POST['item_id'])? addslashes($_POST['item_id']):'';
	$rs = sysGetList("ez_member",array(), " AND username='".$item_id."'"); 				
	if(count($rs)>0) {
		$row=$rs[0];
		?>
		<form id="frm_action" name="frm_action" method="post" action="">
		<span class="err" id="mes_err"></span>
		<input name="txt_username" value="<?php echo $row['username'];?>" type="hidden">
			<div class="row form-group">
				<label class="col-md-2"></label>
				<div class="col-md-10"><span class='err_mess'><?php echo $msg;?></span></div>
			</div>
			<div class="row form-group">
				<label class="col-md-2 control-label">Tài khoản<span class="star">*</span></label>
				<div class="col-md-4">
					<input disabled class="form-control" value="<?php echo $row['username'];?>" type="text" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-2 control-label">Họ tên <span class="star">*</span></label>
				<div class="col-md-4">
					<input class="form-control" id="txtname" name="txtname" value="<?php echo $row['fullname'];?>" type="text" required>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-2 control-label">Điện thoại</label>
				<div class="col-md-4">
					<input class="form-control" name="txtphone" type="tel" id="txtphone" value="<?php echo $row['phone'];?>"/>
				</div>
				<label class="col-md-2 control-label text-right">Email</label>
				<div class="col-md-4">
					<input class="form-control" name="txtemail" type="email" id="txtemail" value="<?php echo $row['email'];?>"/>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-2 control-label">Ngày sinh</label>
				<div class="col-md-4">
					<input class="form-control" name="txtbirthday" type="date" id="txtbirthday" value="<?php echo $row['birthday'];?>"/>
				</div>
				<label class="col-md-2 control-label text-right">Giới tính</label>
				<div class="col-md-4">
					<label for="gender1" style="font-weight:500">
						<input name="optgender" type="radio" id="gender1" value="nam" <?php if($row['gender']=='nam') echo 'checked';?>/> Nam
					</label>
					<label for="gender2" style="font-weight:500">
						<input name="optgender" type="radio" id="gender2" value="nu" <?php if($row['gender']=='nu') echo 'checked';?>/> Nữ
					</label>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-2 control-label">Địa chỉ</label>
				<div class="col-md-10">
					<input class="form-control" id="txtaddress" name="txtaddress" value="<?php echo $row['address'];?>" type="text">
				</div>
			</div>
			<div class="form-group text-center">
				<span type="submit" name="cmdsave" id="act-process"  class="btn btn-primary">Lưu lại</span>
			</div>
		</form>
	<?php 
	}
}
else{
    die('Vui lòng đăng nhập hệ thống');
}
?>
<script>

$('#txt_packet_group').change(function(){
	var dataid = $(this).val();
	
	$.post('<?php echo ROOTHOST;?>ajaxs/account/get_packet.php',{dataid}, function(response_data){
		$('#txt_packet').html(response_data);
	});
	
})
function checkinput(){
	if($("#txtname").val()==""){
		alert('Vui lòng nhập họ và tên');
		$("#txtname").focus();
		return false;
	}
	return true;
}
$('#act-process').click(function(){
		if(checkinput()==false) return false;
		var form = $('#frm_action');
		var postData = form.serializeArray();
		$.post('<?php echo ROOTHOST;?>ajaxs/account/process_save.php',postData, function(response_data){
			if(response_data=='success'){
				$('#myModalPopup').modal('hide');
				showMess('Giao dịch thành công!','');
			
				
			}
			else{
				$('#mes_err').html('Có lỗi trong quá trình xử lý!');
			}
			
		});
		
	})
	
</script>
