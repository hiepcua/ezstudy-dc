<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$msg='';
$username = getInfo('username');
if(isset($_POST['cmdsave']))
{	
	$password = antiData($_POST['password']);
	$pass = hash('sha256', $username).'|'.hash('sha256', $password);
	if($pass != getInfo('password')) $msg = "<div class='alert alert-danger'>Mật khẩu hiện tại không đúng. Vui lòng nhập lại.</div>";
	else {
		$new_pass = antiData($_POST['new_pass']);
		$new_pass = hash('sha256', $username).'|'.hash('sha256', $new_pass);
		$arr = array();
		$arr['password']  = $new_pass;
		$result = SysEdit("ez_member",$arr," username='$username'");
		
		// update dữ liệu member về DC
		if($result) {
			$json = array();
			$json['key'] = PIT_API_KEY;
			$json['username'] = $username;
			$json['partner_code'] = PARTNER_CODE;
			$json['arr']   = $arr;
			$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$req = Curl_Post(API_MEMBER_EDIT,json_encode($post_data));
			//var_dump($post_data);
		}
		
		$msg="<div class='alert alert-success'>Đổi mật khẩu thành công. Hệ thống tự động thoát sau 3 giây. Vui lòng đăng nhập lại với mật khẩu mới.</div>";
	}
}
	
$rs = sysGetList("ez_member",array(), " AND username='".$username."'"); 
if(!isset($rs[0])) die('Không có dữ liệu');
$row = $rs[0]; 
?>
<script language='javascript'>
function checkinput(){
	 return true;
}
</script>
	<div class="card">
		<div class="card-block">
		<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class='header'>
				<h1 class='page-title'>Đổi mật khẩu</h1><hr>
			</div>
			<div class="body box-white">
				<form id="frm_action" name="frm_action" method="post" action="" class="change_pass">
					<div class="row form-group">
						<label class="col-md-3"></label>
						<div class="col-md-9"><span class='err_mess'>
						<?php if($msg!="") { echo $msg; ?>
						<script>setTimeout( function() { window.location='<?php echo ROOTHOST;?>logout'; }, 3000);</script>
						<?php }?>
						</span></div>
					</div>
					<div class="row form-group">
						<label class="col-md-3 control-label">Mật khẩu hiện tại<span class="star">*</span></label>
						<div class="col-md-5">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type='password' name='password' id='txt_pass' class='password form-control' placeholder='Mật khẩu' value='' min="6" max="20" required autocomplete="off"/>
								<button type='button' class="icon-eye fa fa-eye-slash"></button>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-3 control-label">Mật khẩu mới <span class="star">*</span></label>
						<div class="col-md-5">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type='password' name='new_pass' id='new_pass' class='password form-control' placeholder='Mật khẩu' value='' min="6" max="20" required autocomplete="off"/>
								<button type='button' class="icon-eye fa fa-eye-slash"></button>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-3 control-label">Nhập lại mật khẩu mới <span class="star">*</span></label>
						<div class="col-md-5">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type='password' name='new_pass2' id='new_pass2' class='password form-control' placeholder='Mật khẩu' value='' min="6" max="20" required autocomplete="off"/>
								<button type='button' class="icon-eye fa fa-eye-slash"></button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3"></label>
						<div class="col-md-9">
							<input type="submit" name="cmdsave" id="cmdsave" value="Đổi mật khẩu" class="btn btn-primary">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div></div>
	</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#txt_pass').focus();	
	var flag_eye = false;
	$(".icon-eye").click(function(){
		if(flag_eye == false) {
			flag_eye = true;
			$(".icon-eye").removeClass('fa-eye-slash');
			$(".icon-eye").addClass('fa-eye');
			$(this).parent().find("input").attr('type','text');
		}else {
			flag_eye = false;
			$(".icon-eye").removeClass('fa-eye');
			$(".icon-eye").addClass('fa-eye-slash');
			$(this).parent().find("input").attr('type','password');
		}
	})
	$('#txt_pass').keyup(function(e){
		var str  = $(this).val();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('#new_pass').keyup(function(e){
		var str  = $(this).val();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('#new_pass2').keyup(function(e){
		var str  = $(this).val();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	
	$("#cmdsave").click(function(){
		var pass = $("#txt_pass").val();	
		var new_pass = $("#new_pass").val();	
		var new_pass2 = $("#new_pass2").val();	
		
		if(pass=='' ){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập mật khẩu đăng nhập</div>');
			$('#txt_pass').focus();
			return false;
		}else if(pass.length < 6 || pass.length > 20) {
			$('.err_mess').html('<div class="alert alert-danger">Mật khẩu phải từ 6 đến 20 ký tự</div>');
			$('#txt_pass').focus();
			return false;
		}
		if(new_pass == '' ){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập mật khẩu mới</div>');
			$('#new_pass').focus();
			return false;
		}else if(new_pass.length < 6 || new_pass.length > 20) {
			$('.err_mess').html('<div class="alert alert-danger">Mật khẩu phải từ 6 đến 20 ký tự</div>');
			$('#new_pass').focus();
			return false;
		}else if(new_pass != new_pass2) {
			$('.err_mess').html('<div class="alert alert-danger">Mật khẩu mới không khớp. Vui lòng nhập lại.</div>');
			$('#new_pass2').focus();
			return false;
		}
		return true;
	})
})
</script>
<?php unset($obj); ?>