<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
include_once(incl_path."config_api.php"); // get data api
$username = getInfo('username');
?>
<form id="ajxs_frm_action" method="post" action="">
    <div class='ajx_mess err_mess'></div>
    <input type="hidden" id="txt_par_user" name="txt_par_user" value="<?php echo $username;?>">

    <div class="form-group row">
        <div class="col-md-6 col-xs-12">
            <label>Họ tên</label>
            <div class="input-group"> 
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type='text' name='fullname' id='txt_name' class='form-control' min="2" max="50" placeholder='Họ tên' required/>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <label>Tên đăng nhập</label>
            <div class="input-group"> 
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type='text' name='username' id='txt_user' class='username form-control' placeholder='Tên đăng nhập' min="3" max="20" required/>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 col-xs-12">
            <label>Mật khẩu</label>
            <div class="input-group"> 
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type='password' name='password' id='txt_pass' class='password form-control' placeholder='Mật khẩu' min="6" max="20" required/>
                <button type='button' class="icon-eye fa fa-eye-slash"></button>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <label>Nhập lại mật khẩu</label>
            <div class="input-group"> 
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type='password' name='repassword' id='txt_repass' class='password form-control' placeholder='Mật khẩu' min="6" max="20" required/>
                <button type='button' class="icon-eye fa fa-eye-slash"></button>
            </div>
        </div>
    </div>

    <div class="form-group row grade-group">
        <div class="col-md-6 col-xs-12">
            <label>Chọn khối lớp</label>
            <div class="input-group"> 
                <span class="input-group-addon"><i class="fa fa-book"></i></span>
                <select name='class' id='cbo_grade' class='form-control' required>
                    <option value="">-- Chọn khối lớp --</option>
                    <?php 
                    if(isset($_Grade)) { 
                        foreach($_Grade as $k=>$v) { 
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        } 
                    }?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group clearfix">
        <button type="button" id="btn-process-regis" class='btn btn-primary form-control'>TẠO TÀI KHOẢN</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
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
        });

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

        $('#btn-process-regis').click(function(){
            register();
        });
    });

    function register(){
        var type = 'student';
        var name = $('#txt_name').val();
        var lop  = $('#cbo_grade option:selected').val();
        var user = $('#txt_user').val();
        var pass = $('#txt_pass').val();
        var repass = $('#txt_repass').val();
        var par_user = $('#txt_par_user').val();
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
        if(lop == "") {
            $('.err_mess').html('<div class="alert alert-danger">Vui lòng chọn khối lớp</div>');
            $('#cbo_grade').focus();
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
        
        $('.err_mess').html('');
        var _url='<?php echo ROOTHOST;?>ajaxs/member/process_create_member_child.php';
        var _data={
            'type': type,
            'lop': lop,
            'fullname': name,
            'username': user,
            'password': pass,
            'par_user': par_user
        }
        $.post(_url,_data,function(req){
            if(req=='success'){
                $('.err_mess').html('<div class="alert alert-success">Tạo tài khoản thành công.</div>');
                setTimeout( function(){ window.location.reload(); }, 2000);
            }else $('.err_mess').html('<div class="alert alert-danger">'+req+'</div>');
        });
    }
</script>