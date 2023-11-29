<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(!isLogin()) die("E01");

$member = isset($_POST['member']) ? antiData($_POST['member']):"";
$member_info = api_get_member_info($member);
if(count($member_info)<1){
    echo "Username not exist"; die();
}
$type_user = $member_info['utype'];
$cur_packet = $member_info['packet'];

// Get method payment
$json = array();
$json['key'] = PIT_API_KEY;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_GET_METHOD_PAYMENT, json_encode($post_data)); 
$arr_method_payment = array();
if(is_array($rep['data']) && count($rep['data']) > 0) {
    $arr_method_payment = $rep['data'];
}

?>
<form id="ajxs_frm_action" method="post" action="">
    <div class="ajx_mess cred"></div>
    <div class="form-group">
        <label>Tài khoản</label>
        <input type="text" value="<?php echo $member;?>" name="txt_username" id="txt_username" class="form-control required" readonly>
    </div>

    <?php
    if($cur_packet==""){ // Chưa đăng ký dịch vụ nào
        // Get packet
        $json = array();
        $json['key'] = PIT_API_KEY;
        $post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
        $rep = Curl_Post(API_PACKET, json_encode($post_data)); 
        $arr_packet = array();
        if(is_array($rep['data']) && count($rep['data']) > 0) {
            $arr_packet = $rep['data'];
        }
        ?>
        <div class="form-group">
            <label>Khóa học</label><small class="cred">(*)</small>
            <select id="ajax_cbo_packet" class="form-control required" name="cbo_packet">
                <option value="">-- Chọn khóa học --</option>
                <?php
                foreach ($arr_packet as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Thời gian:</label><small class="cred">(*)</small>
            <select name="txt_packet" class="form-control required" id="txt_packet" disabled></select>
        </div>
    <?php }else if($cur_packet=="EZ1"){ // Nâng cấp gói dịch vụ
        // Get packet
        $json = array();
        $json['key'] = PIT_API_KEY;
        $json['id'] = "EZ2";
        $post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
        $rep = Curl_Post(API_PACKET, json_encode($post_data)); 
        $arr_packet = array();
        if(is_array($rep['data']) && count($rep['data']) > 0) {
            $arr_packet = $rep['data'];
        }
        ?>
        <div class="form-group">
            <label>Gói dịch vụ</label>
            <select id="ajax_cbo_packet" class="form-control required" name="cbo_packet">
                <?php
                foreach ($arr_packet as $key => $value) {
                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
                ?>
            </select>
        </div>
    <?php } ?>

    <div class="form-group">
        <label>Phương thức thanh toán:</label><small class="cred">(*)</small>
        <select name="txt_method_payment" class="form-control txt_method_payment required" id="txt_method_payment" data-placeholder="Chọn">
            <option value="">-- Chọn phương thức thanh toán --</option>
            <?php
            if(!empty($arr_method_payment)){
            foreach($arr_method_payment as $key_payment => $value_payment) { 
                ?>
                <option  value="<?php echo $key_payment;?>"><?php echo $value_payment['text'];?></option>
                <?php
                }
            }
            ?>
        </select>
    </div>

    <div class="modal-footer">
        <button type="submit" id="cmd_save" class="btn btn-success"><i class="fa fa-save"></i> Đăng ký</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" value="Hủy bỏ">Hủy bỏ</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $("form#ajxs_frm_action").submit(function(e) {
            if(validForm()) {
                e.preventDefault();
                var formData = new FormData(this);
                var _url = "<?php echo ROOTHOST;?>ajaxs/packet/proccess_packet_upgrade.php";
                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: formData,
                    success: function (res) {
                        var data_return=$.parseJSON(res);
                        if(data_return.status == "success") {
                            var method_payment=$("#txt_method_payment").val();
                            var str='';
                            if(method_payment=='chuyen_khoan'){
                                str+='<h1 class="text-center">Đăng ký thành công! </h1>';
                                str+="<p> Vui lòng chuyển khoản với nội dung '<strong>EZ <?php echo $member; ?></strong>' vào một trong các tài khoản sau:</p>";
                                str+="<table class='table table-bordered'>";
                                str+="<thead>";
                                str+="<tr>";
                                str+="<td>Chủ tài khoản</td>";
                                str+="<td>Ngân hàng</td>";
                                str+="<td>Số tài khoản</td>";
                                str+="</tr>";
                                str+="</thead>";
                                str+="<tbody>";
                                <?php 
                                if(isset($arr_method_payment['chuyen_khoan']['list_bank'])){
                                    foreach ($arr_method_payment['chuyen_khoan']['list_bank'] as $key_bank => $value_bank) {

                                        ?>
                                        str+="<tr>";
                                        str+="<td><?php echo $value_bank['chutk']; ?></td>";
                                        str+="<td><?php echo $value_bank['bank']; ?></td>";
                                        str+="<td><?php echo $value_bank['sotk']; ?></td>";
                                        str+="</tr>";
                                        <?php
                                    }
                                } 
                                ?>
                                str+="</tbody>";
                                str+="</table>";
                            }else if(method_payment=='kich_hoat'){
                                str+='<h1 class="text-center">Đăng ký thành công! </h1>';
                                 str+="<p>Hệ thống đã nghi nhận thông tin, vui lòng liên hệ với đại lý để thanh toán, hoặc liên hệ với hỗ trợ (hotline: <?php echo HOTLINE; ?>)</p>";
                            }else if(method_payment=='khac'){
                                str+='<h1 class="text-center">Đăng ký thành công! </h1>';
                                 str+="<p>Liện hệ với hệ thống hô trợ (hotline: <?php echo HOTLINE; ?>)</p>";
                            }
                            
                            $("#myModalPopup .modal-body").html(str);
                            $("#myModalPopup").modal("show");
                        }else  if(data_return.status == "exist") {
                            showMess("Bạn đã gửi yêu cầu này. Vui lòng đợi Admin xử lý!");
                            setTimeout(function(){window.location.reload()},2000);
                        }else{
                             showMess('Gia hạn không thành công!','error');
                        }
                        // console.log(res);
                        // if(res == "success") {
                        //     showMess("Nâng cấp thành công. Hệ thống tự động tải trang sau 3 giây");
                        //     setTimeout(function(){window.location.reload()},2000);
                        // }else{
                        //     showMess('Nâng cấp không thành công!','error');
                        // }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }else{
                e.preventDefault();
            }
        });

        $('#ajax_cbo_packet').change(function(){
            change_packet();
        });
    });

    function change_packet(){
        var packet = $('#ajax_cbo_packet').val();
        if(packet.length>0){
            $('#txt_packet').prop("disabled", false);
            var username = $("#txt_username").val();
            if(username.length>0 && packet.length>0){
                var data = {
                    "packet": packet,
                    "username": username,
                };
                var url = "<?php echo ROOTHOST;?>ajaxs/packet/load_cbo_packet_item.php";
                $.post(url, data, function(res){
                    $("#txt_packet").html(res);
                });
            }
        }else{
            $('#txt_packet').prop("disabled", true);
            $("#txt_packet").html('');
        }
    }

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
