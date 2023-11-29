<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$msg='';
$username = getInfo('username');	
$rs = sysGetList("ez_member",array(), " AND username='".$username."'"); 
if(!isset($rs[0])) die('Không có dữ liệu');
$row = $rs[0]; 
?>
<script language='javascript'>
function checkinput(){
	 return true;
}
</script>

	<div class="card"><div class="card-block"><div class="row">
		<div class="col-md-3 col-xs-12"><?php include("user_menu.php");?></div>
		<div class="col-md-9 col-xs-12">
			<div class='header'>
				<h1 class='page-title'>Tạo tài khoản cho con</h1><hr>
			</div>
			<div class="form-group">
				<label>Lưu ý:</label>
				<ul><li>Tài khoản được tạo sẽ có chung email và số điện thoại với tài khoản của bạn</li>
					<li>Tài khoản được tạo sẽ được tự động kích hoạt</li>
					<li>Tài khoản được tạo sẽ được tự động liên kết với tài khoản của bạn</li>
				</ul>
			</div>
			<div class="body box-white">
				<form id="frm_action" name="frm_action" method="post" action="">
					<div class="row form-group">
						<label class="col-md-2"></label>
						<div class="col-md-10"><span class='err_mess'><?php echo $msg;?></span></div>
					</div>
					<div class="row form-group">
						<label class="col-md-2 control-label">Họ tên<span class="star">*</span></label>
						<div class="col-md-4">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input class="form-control" id="txt_name" name="txt_name" value="" type="text" required>
							</div>
						</div>
						<label class="col-md-2 control-label">Tên đăng nhập<span class="star">*</span></label>
						<div class="col-md-4">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input class="form-control" id="txt_user" name="txt_user" value="" type="text" required>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-2 control-label">Mật khẩu<span class="star">*</span></label>
						<div class="col-md-4">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type='password' name='password' id='txt_pass' class='password form-control' placeholder='Mật khẩu' value='' min="6" max="20" required autocomplete="off"/>
								<button type='button' class="icon-eye fa fa-eye-slash"></button>
							</div>
						</div>
						<label class="col-md-2 control-label">Nhập lại mật khẩu<span class="star">*</span></label>
						<div class="col-md-4">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type='password' name='repassword' id='txt_repass' class='password form-control' placeholder='Mật khẩu' value='' min="6" max="20" required autocomplete="off"/>
								<button type='button' class="icon-eye fa fa-eye-slash"></button>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-2 control-label">Khối lớp<span class="star">*</span></label>
						<div class="col-md-4">
							<div class="input-group"> 
								<span class="input-group-addon"><i class="fa fa-book"></i></span>
								<select name='class' id='cbo_grade' class='form-control' required>
									<option value="">-- Chọn khối lớp --</option>
									<?php if(isset($_Grade)) { 
									foreach($_Grade as $k=>$v) { 
										echo '<option value="'.$k.'">'.$v.'</option>';
									} } ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="form-group text-center">
						<input type="button" name="cmdsave" id="cmdsave" value="Tạo tài khoản" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
	</div>
	</div>
	</div>

<script>
$(document).ready(function(){
	$('#txt_name').focus();
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

	$("#cmdsave").click(function(){
		var name = $("#txt_name").val();	
		var user = $("#txt_user").val();
		var pass = $("#txt_pass").val();
		var repass = $("#txt_repass").val();
		var lop  = $("#cbo_grade option:selected").val();
		
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
		if(lop=='' ){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng chọn khối lớp</div>');
			$('#cbo_grade').focus();
			return false;
		}
		$('.err_mess').html('');
		var _url='<?php echo ROOTHOST;?>ajaxs/member/process_add_account.php';
		var _data={
			'lop': lop,
			'fullname': name,
			'username': user,
			'password': pass
		}
		$.post(_url,_data,function(req){
			if(req=='over'){
				$('.err_mess').html('<div class="alert alert-danger">Bạn không thể tạo thêm tài khoản cho con.</div>');
			}else if(req=='success'){
				$('.err_mess').html('<div class="alert alert-success">Bạn đã tạo thành công tài khoản cho con.</div>');
				setTimeout( function(){ window.location.reload(); }, 3000);
			}else 
				$('.err_mess').html('<div class="alert alert-danger">'+req+'</div>');
		});
	})
})
</script>
<?php unset($obj); ?>