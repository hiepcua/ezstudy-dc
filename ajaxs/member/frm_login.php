<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
$time=time();
$username=$password='';
$ischeck='no';
if(isset($_COOKIE['LOGIN_USER'])){
	$arr_cookie=json_decode(decrypt($_COOKIE['LOGIN_USER']),true);
	$username=$arr_cookie['username'];
	$password=$arr_cookie['password'];
	$ischeck=$arr_cookie['ischeck'];	
}
?>

<div class="container box_login">
	<div class="row">
		<div class='col-md-6 hidden-xs'>
			<p class="txt_title"> <span >Ứng dụng EZStudy - </span> <span class="inner-text">Ứng dụng hỗ trợ học tập 4.0 </span> hàng đầu tại Việt Nam</p>
		</div>

		<div class='col-md-6 col-xs-12 login-box'>
			
			<div class="login-form card-body rounded-0 text-center p-3 box_right">
				<p>
					<img class="img_logo" src="<?php echo ROOTHOST; ?>images/logo/logo_ngang.png">
				</p>

				<h2 class="caption">
					Đăng nhập để học tập
				</h2>
				<form id="frmlogin" name="frmlogin" class="frmlogin"  method="post" action="" autocomplete="off">
					<div class="inner">
						<div class='err_mess form-group'></div>
						<div class="form-group icon-input mb-3">
							<i class="font-sm ti-user text-grey-500 pr-0"></i>
							<input name='username' id='txt_user' min="3" max="20" value="<?php echo $username;?>" required type="text" class="style2-input pl-5 form-control text-grey-900 font-xsss fw-600" placeholder="Tài khoản">                        
						</div>

						<div class="form-group icon-input mb-1">
							<input type='password' name='password' id='txt_pass' value="<?php echo $password;?>" class='password form-control style2-input pl-5 form-control text-grey-900 font-xss ls-3' placeholder='Mật khẩu' min="6" max="20" required>
							<i class="font-sm ti-lock text-grey-500 pr-0"></i>
						</div>

						<div class="form-group forget_pass">
							<label class="checkcontainer pull-left"> Lưu tài khoản
								<input type="checkbox" name="" id="isConfirm" <?php if($ischeck=='yes') echo 'checked';?> value=""/> 
								<span class="checkmark"></span>
							</label>
							<a href="javascript:void(0)" class="pull-right btn_forgot">Quên mật khẩu</a>
						</div>

						<div class="clearfix">
							<button type='button' id='btn-process-login' name='cmd_login' class='btn btn-primary  btn-login form-control'>ĐĂNG NHẬP</button>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<span>Bạn chưa có tài khoản?</span>
									<a href="javascript:void(0)" class="btn_regis">Đăng ký ngay!</a>
								</div>
								
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 

?>
<script type="text/javascript">
	$(document).ready(function(){
		var flag_eye = false;
		$(".icon-eye").click(function(){
			if(flag_eye == false) {
				flag_eye = true;
				$(".icon-eye").removeClass('fa-eye-slash');
				$(".icon-eye").addClass('fa-eye');
				$(this).parent().parent().find("input").attr('type','text');
			}else {
				flag_eye = false;
				$(".icon-eye").removeClass('fa-eye');
				$(".icon-eye").addClass('fa-eye-slash');
				$(this).parent().parent().find("input").attr('type','password');
			}
		})
		$('#txt_user').focus();

		$('#btn-process-login').click(function(){
			login();
		});

		$('#txt_user,#txt_pass').keyup(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			if (code==13) {
				e.preventDefault();
				login();
			}
		});

		$('#txt_user').keyup(function(){
			var str  = $(this).val();
			$(this).val($.trim(str));
		});

		$('#txt_pass').keyup(function(e){
			var str  = $(this).val();
			$(this).val($.trim(str));
		});

		$(".btn_regis").click(function(){
			var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_register.php";
			$.get(url,function(req) {
				$(".login-page").html(req);
			})
		});

		$(".btn_forgot").click(function(){
			var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_forgot.php";
			$.get(url,function(req) {
				$(".login-page").html(req);
			})
		});
	})

	function login(){
		var user = $('#txt_user').val();
		var pass = $('#txt_pass').val();
		var regUser= /^[a-z0-9_-]{6,20}$/;

		if(user==''){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập tên đăng nhập</div>');
			$('#txt_user').focus();
			return false;
		}else if(!regUser.test( removeAscent(user) )) {
			$('.err_mess').html('<div class="alert alert-danger">Tên đăng nhập không đúng định dạng</div>');
			$('#txt_user').focus();
			return false;
		}else if(user.length < 3 || user.length > 20) {
			$('.err_mess').html('<div class="alert alert-danger">Tên đăng nhập phải từ 3 đến 20 ký tự</div>');
			$('#txt_user').focus();
			return false;
		}
		if(pass=='' ){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập mật khẩu đăng nhập</div>');
			$('#txt_pass').focus();
			return false;
		}else if(pass.length < 6 || pass.length > 20) {
			$('.err_mess').html('<div class="alert alert-danger">Mật khẩu phải từ 6 đến 20 ký tự</div>');
			$('#txt_pass').focus();
			return false;
		}

		$('.err_mess').html('');
		var _ischeck=$('#isConfirm').is(':checked')?'yes':'no';
		var _url='<?php echo ROOTHOST;?>ajaxs/member/process_login.php';
		var _data={
			'username':$('#txt_user').val(),
			'password':$('#txt_pass').val(),
			'ischeck':_ischeck
		}
		$.post(_url,_data,function(req){
			// console.log(req);
			if(req=='success'){
				name_json='data3_'+user;
				var rs=JSON.parse(localStorage.getItem(name_json));
				if(!rs){
					var time = Date.parse(new Date()) / 1000;
					var arr={'last_time':time,'total_time':0, 'username':user,'today':time};
					var array=JSON.stringify(arr);
					localStorage.setItem(name_json, array);
				}
				window.location.reload();
			}else $('.err_mess').html('<div class="alert alert-danger">'+req+'</div>');
		});
	}
</script>