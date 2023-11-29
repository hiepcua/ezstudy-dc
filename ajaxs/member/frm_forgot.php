<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
?>
<div class="container">
	<div class='col-md-8 hidden-xs'>
		<!--<img src="<?php echo ROOTHOST;?>images/login/login_slide1.jpg" class="img-responsive"/>-->
	</div>
	<div class='col-md-4 col-xs-12 login-box'>
		<form id="frmlogin" name="frmlogin" class="login-form" method="post" action="" autocomplete="off"><div class="inner">
			<h1 class="main-title">QUÊN MẬT KHẨU</h1>
			<div class='err_mess form-group'></div>
			<div class="form-group">
				<label>Tên đăng nhập</label>
				<div class="input-group"> 
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type='text' name='username' id='txt_user' class='username form-control' placeholder='Tên đăng nhập' value='' min="3" max="20" required autocomplete="off"/>
				</div>
			</div>
			<div class="form-group">
				<label>Email của bạn</label>
				<div class="input-group"> 
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type='email' name='email' id='txt_email' class='password form-control' placeholder='Hòm thư của bạn' value='' min="6" max="20" required autocomplete="off"/>
				</div>
			</div>
			<div class="form-group clearfix">
				<button type='button' id='btn_process' name='cmd_login' class='btn btn-primary form-control'>ĐỔI MẬT KHẨU</button>
			</div>
			<div class="form-group">
				<div class="text-right">
					<a href="javascript:void(0)" class="btn_login"><i class="fa fa-arrow-left"></i> Quay lại</a>
				</div>
			</div>
		</div></form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#txt_user').focus();

	$('#btn_process').click(function(){
		forgot();
	});
	$('#txt_user').keyup(function(e){
		var str  = $(this).val().toLowerCase();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('#txt_user,#txt_email').keyup(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code==13) {
			e.preventDefault();
			forgot();
		}
	});
	$(".btn_login").click(function(){
		var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_login.php";
		$.get(url,function(req) {
			$(".login-page").html(req);
		})
	})
})

function forgot(){
	var user = $('#txt_user').val();
	var email = $('#txt_email').val();
	var regUser= /^[a-z0-9_-]{6,20}$/;

	if(user==''){
		$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập tên đăng nhập</div>');
		$('#txt_user').focus();
		return false;
	}else if(!regUser.test(user)) {
		$('.err_mess').html('<div class="alert alert-danger">Tên đăng nhập không đúng định dạng</div>');
		$('#txt_user').focus();
		return false;
	}else if(user.length < 3 || user.length > 20) {
		$('.err_mess').html('<div class="alert alert-danger">Tên đăng nhập phải từ 3 đến 20 ký tự</div>');
		$('#txt_user').focus();
		return false;
	}
	if(email=='' ){
		$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập Email của bạn</div>');
		$('#txt_email').focus();
		return false;
	}else if(checkEmail(email) == false) {
		$('.err_mess').html('<div class="alert alert-danger">Email không đúng định dạng</div>');
		$('#txt_email').focus();
		return false;
	}
	
	$('.err_mess').html('');
	var _ischeck=$('#isConfirm').is(':checked')?'yes':'no';
	var _url='<?php echo ROOTHOST;?>ajaxs/member/process_forgot.php';
	var _data={
		'username':$('#txt_user').val(),
		'email':$('#txt_email').val()
	}
	$('.err_mess').html('<div class="alert alert-warning">Đang xử lý ...</div>');
	$.post(_url,_data,function(req){
		$('.err_mess').html('');
		if(req=='empty'){
			$('.err_mess').html('<div class="alert alert-danger">Tài khoản chưa có trong hệ thống</div>');
		}else if(req=='not_email'){
			$('.err_mess').html('<div class="alert alert-danger">Tài khoản chưa có thông tin email. Vui lòng liên hệ với quản trị viên</div>');
		}else if(req=='not_match'){
			$('.err_mess').html('<div class="alert alert-danger">Email của bạn không khớp.</div>');
		}else if(req=='error_send'){
			$('.err_mess').html('<div class="alert alert-danger">Quá trình gửi mail bị gián đoạn. Vui lòng liên hệ với quản trị viên hoặc thử lại sau</div>');
		}else if(req=='success'){
			$('.err_mess').html('<div class="alert alert-success">Yêu cầu đổi mật khẩu đã được gửi đến hòm thư của bạn. Vui lòng kiểm tra thư đến. Hệ thống tự động tải lại trang sau 3 giây.</div>');
			setTimeout( function(){ window.location.reload(); }, 3000);
		}else $('.err_mess').html('<div class="alert alert-danger">'+req+'</div>');
	});
}
</script>