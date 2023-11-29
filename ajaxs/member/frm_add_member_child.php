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
$parent = isset($_POST['parent']) ? antiData($_POST['parent']):"";
if($parent==""){
	echo "Empty parent"; die();
}
?>
<form id="ajxs_frm_action" method="post" action="">
    <div class="ajx_mess cred"></div>
    <div class="form-group">
    	<label>Tài khoản phụ huynh</label>
    	<input type="text" value="<?php echo $username;?>" name="txt_username_chame" class="form-control required" readonly>
    </div>

    <div class="form-group">
    	<label>Tài khoản học sinh</label>
    	<input type="text" name="txt_username_hocsinh" class="form-control required">
    </div>

    <div class="modal-footer">
        <button type="submit" id="cmd_save" class="btn btn-success"><i class="fa fa-save"></i> Thêm tài khoản</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" value="Hủy bỏ">Hủy bỏ</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $("form#ajxs_frm_action").submit(function(e) {
            if(validForm()) {
                e.preventDefault();
                var formData = new FormData(this);
                var _url = "<?php echo ROOTHOST;?>ajaxs/member/proccess_add_member_child.php";
                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: formData,
                    success: function (res) {
                        // console.log(res);
                        if(res == "success") {
                            showMess("Thêm tài khoản con thành công");
                            setTimeout(function(){window.location.reload()},2000);
                        }else if(res=="Missing param"){
                            showMess('Thiếu dữ liệu đầu vào','error');
                        }else if(res=="member not exist"){
                            showMess('Tài khoản con không tồn tại','error');
                        }else if(res=="parent are true"){
                            showMess('Tài khoản đã có liên kết với tài khoản phụ huynh','error');
                        }else{
                            showMess('Lỗi!','error');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }else{
                e.preventDefault();
            }
        });
    });

    function validForm(){
        var flag = true;
        $('#myModalPopup .required').each(function(){
            var val = $(this).val();
            if(!val || val=='' || val=='0') {
                $(this).parents('.form-group').addClass('error');
                flag = false;
            }

            if(flag==false) {
                $('.ajx_mess').html('Những mục có đánh dấu * là những thông tin bắt buộc.');
            }
        });
        return flag;
    }
</script>