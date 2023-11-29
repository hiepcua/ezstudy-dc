<?php
session_start();
define('incl_path','../../global/libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gffunc.php');
$ref = isset($_GET['ref']) ? antiData($_GET['ref']) : '';
?>
<div class="container box_register">
	<div class="row">
	<div class='col-md-6 hidden-xs'>
		<p class="txt_title"> <span >Ứng dụng EZStudy - </span> <span class="inner-text">Ứng dụng hỗ trợ học tập 4.0 </span> hàng đầu tại Việt Nam</p>
	</div>
	<div class='col-md-6 col-xs-12 login-box'>
		<form id="frmregis" name="frmregis" class="login-form" method="post" action="" autocomplete="off"><div class="inner">
			<h1 class="caption">
			Đăng ký tài khoản
		</h1>
			<div class="form-group">
				<!-- <div>Vui lòng chọn loại tài khoản phù hợp với bạn</div> -->
				<div>
					<!-- <div class="box_type">
						<input type="radio" name="type" id="student" class="input-hidden" value=""/>
						<label for="student">
							<i class=" icon-student">
								<img src="<?php echo ROOTHOST; ?>images/icons/icon_hs.png" alt="icon_hs">
							<div>Học sinh</div>
						</i> 
						</label>
					</div>
					<div class="box_type">
						<input type="radio" name="type" id="parents" class="input-hidden" />
						<label for="parents">
							<i class="fa fa-3x fa-user-secret icon-parents"><div>Phụ huynh</div></i> 
						</label>
					</div> -->

					<div class="box_type">
						<input type="radio" name="type" id="student" class="input-hidden" value=""/>
						<label for="student" class="active">
							<span class="icon-student">
								<span class="left">
								<img src="<?php echo ROOTHOST; ?>images/icons/icon_hs.png" alt="icon_hs">
								</span>
							<span class="txt-text">Học sinh</span>
							</span>
						</label>
					</div>
					<div class="box_type">
						<input type="radio" name="type" id="parents" class="input-hidden" />
						<label for="parents" class="label_icon">
							<span class="icon-parents">
							<span class="left">
								<img src="<?php echo ROOTHOST; ?>images/icons/icon_ph.png" alt="icon_hs">
								</span>
							<span class="txt-text">Phụ huynh</span>
						</span>
							
						</label>
					</div>

					
				</div>
				<div class="clearfix"></div>
			</div>
			<div class='err_mess form-group'></div>
			<div class="form-group row">
				<div class="col-md-12 col-xs-12">
					<!-- 
					<div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-user"></i></span> -->
						<input type='text' name='fullname' id='txt_name' class='form-control' min="2" max="50" placeholder='Họ và tên*' value='' required autocomplete="off"/>
					<!-- </div> -->
				</div>
				
			</div>
			<div class="form-group row">
				<div class="col-md-12 col-xs-12">
					<!-- <label>Tên đăng nhập</label> -->
					<!-- <div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-user"></i></span> -->
						<input type='text' name='username' id='txt_user' class='username form-control' placeholder='Tên đăng nhập*' value='' min="3" max="20" required autocomplete="off"/>
					<!-- </div> -->
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12 col-xs-12">
					<!-- <label>Mật khẩu</label> -->
					<!-- <div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-lock"></i></span> -->
						<input type='password' name='password' id='txt_pass' class='password form-control' placeholder='Mật khẩu*' value='' min="6" max="20" required autocomplete="off"/>
						<!-- <button type='button' class="icon-eye fa fa-eye-slash"></button> -->
					<!-- </div> -->
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12 col-xs-12">
					<!-- <label>Nhập lại mật khẩu</label> -->
					<!-- <div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-lock"></i></span> -->
						<input type='password' name='repassword' id='txt_repass' class='password form-control' placeholder='Nhập lại mật khẩu*' value='' min="6" max="20" required autocomplete="off"/>
						<!-- <button type='button' class="icon-eye fa fa-eye-slash"></button>
					</div> -->
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12 col-xs-12">
					<!-- <label>Chọn khối lớp</label> -->
					<!-- <div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-book"></i></span> -->
						<select name='class' id='cbo_grade' class='form-control' required>
							<option value="">-- Chọn khối lớp* --</option>
							<?php if(isset($_Grade)) { 
							foreach($_Grade as $k=>$v) { 
								echo '<option value="'.$k.'">'.$v.'</option>';
							} } ?>
						</select>
					<!-- </div> -->
				</div>
			</div>
			<div class="form-group row grade-group">
				
				<div class="col-md-12 col-xs-12">
					<!-- <label>Mã giới thiệu</label> -->
					<!-- <div class="input-group"> 
						<span class="input-group-addon"><i class="fa fa-user"></i></span> -->
						<input type='text' name='ref_user' id='ref_user' class='username form-control' placeholder='Mã giới thiệu(không bắt buộc)' value='<?php echo $ref;?>' min="3" max="20" required autocomplete="off" <?php if($ref != "") echo "disabled";?>/>
					<!-- </div> -->
				</div>
			</div>

			<div class="form-group clearfix">
				<button type='button' id='btn-process-regis' name='cmd_login' class='btn btn-primary form-control'>ĐĂNG KÝ</button>
			</div>
			<div class="form-group text-center">
				Bạn đã có tài khoản? <a href="javascript:void(0)" class="btn_login"><b class="color-blue">Đăng nhập</b></a>
			</div>
		</div></form>
	</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#txt_name').focus();
	$('#student').prop('checked', true);
	
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
	$('#txt_user').keyup(function(e){
		var str  = $(this).val().toLowerCase();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('#txt_pass').keyup(function(e){
		var str  = $(this).val();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('#txt_repass').keyup(function(e){
		var str  = $(this).val();
		str = $.trim(str);
		str = removeAscent(str); 
		$(this).val(str);
	});
	$('.box_type label').click(function(){
		$(".box_type label").removeClass("active");
		$(this).addClass("active");
		if ( $(this).hasClass("label_icon")) {
			$('.grade-group > div:first-child').hide();
		} else {
			$('.grade-group > div:first-child').show();
		}
	})
	$('#btn-process-regis').click(function(){
		register();
	});
	$(".btn_login").click(function(){
		var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_login.php";
		$.get(url,function(req) {
			$(".login-page").html(req);
		})
	})
})

function register(){
	var type = 'student';
	if ( $('#parents').is(":checked")) 
		type = 'parents';
	var name = $('#txt_name').val();
	var lop  = $('#cbo_grade option:selected').val();
	var user = $('#txt_user').val();
	var pass = $('#txt_pass').val();
	var repass = $('#txt_repass').val();
	var ref_user = $('#ref_user').val();

	var regName= /^[a-zA-Z ]{2,}$/g;
	var regUser= /^[a-z0-9_-]{6,20}$/;
	
	
	if(name==''){
		$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập họ tên</div>');
		$('#txt_name').focus();
		return false;
	}else if(!regName.test( removeAscent(name) )) {
		$('.err_mess').html('<div class="alert alert-danger">Họ tên có ít nhất 2 ký tự, phải là kiểu chữ, không chứa chữ số hoặc các ký tự đặc biệt.</div>');
		$('#txt_name').focus();
		return false;
	}else if(name.length < 2 || name.length > 50) {
		$('.err_mess').html('<div class="alert alert-danger">Họ tên phải từ 3 đến 50 ký tự</div>');
		$('#txt_name').focus();
		return false;
	}
	if( type == "student") {
		if( lop == "") {
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng chọn khối lớp</div>');
			$('#cbo_grade').focus();
			return false;
		}
	}

	if(user==''){
		$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập tên đăng nhập</div>');
		$('#txt_user').focus();
		return false;
	}else if(!regUser.test( removeAscent(user) )) {
		$('.err_mess').html('<div class="alert alert-danger">Tên đăng nhập là: chữ thường, chữ số, dấu gạch dưới hoặc dấu gạch ngang và không chứa ký tự đặc biệt. Tên viết không dấu.</div>');
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
	}else if(repass=='' ){
		$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập lại mật khẩu</div>');
		$('#txt_repass').focus();
		return false;
	}else if(repass != pass) {
		$('.err_mess').html('<div class="alert alert-danger">Mật khẩu không khớp. Vui lòng nhập lại.</div>');
		$('#txt_repass').focus();
		return false;
	}
	if(ref_user!= "" && !regUser.test( removeAscent(ref_user) )) {
		$('.err_mess').html('<div class="alert alert-danger">Mã giới thiệu không đúng định dạng.</div>');
		$('#ref_user').focus();
		return false;
	}
	
	$('.err_mess').html('');
	var _url='<?php echo ROOTHOST;?>ajaxs/member/process_register.php';
	var _data={
		'type': type,
		'lop': lop,
		'fullname': name,
		'username': user,
		'password': pass,
		'ref_user': ref_user
	}
	$.post(_url,_data,function(req){
		console.log(req);
		if(req=='success'){
			$('.err_mess').html('<div class="alert alert-success">Bạn đã đăng ký thành công. Hệ thống sẽ tải lại trang sau 3 giây. Vui lòng đăng nhập tài khoản.</div>');
			setTimeout( function(){ window.location.reload(); }, 3000);
		}else $('.err_mess').html('<div class="alert alert-danger">'+req+'</div>');
	});
}
</script>