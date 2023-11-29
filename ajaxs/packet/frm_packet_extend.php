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

$username = isset($_POST['username']) ? antiData($_POST['username']):"";
$member_info = api_get_member_info($username);
$grade = $member_info['grade'];
$type_user = $member_info['utype'];
$cur_packet = $member_info['packet'];

// Get packet
$json = array();
$json['key'] = PIT_API_KEY;
$json['id'] = $cur_packet;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_PACKET, json_encode($post_data)); 
$arr_packet = array();
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arr_packet = $rep['data'];
}
$packetInfo = isset($arr_packet[$cur_packet]) ? $arr_packet[$cur_packet]:array();
if(count($packetInfo) < 1){
	echo '<div class="form-group">Không có dữ liệu gói dịch vụ.</div>'; die();
}

$packet_conf = $packetInfo['packet']!="" ? json_decode($packetInfo['packet'], true):array();
$packet_grade = $packet_conf[$grade];

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
	<h3 class='text-center'>Gia hạn gói <?php echo " <span class='color-label'>".$packetInfo['name'];?></span></h3>
	<div class="ajx_mess cred"></div>
	<input type="hidden" id="money_packet" name="money_packet">

	<div class="form-group">
		<?php if($type_user=='hocsinh'){
			echo '<label>Tài khoản</label>';
			echo '<input type="text" value="'.$username.'" name="txt_username" class="form-control" id="txt_username" readonly>';
		} else {
        		// Lấy danh sách tài khoản học sinh con
			$json = array();
			$json['key'] = PIT_API_KEY;
			$json['utype'] = 'hocsinh';
			$json['par_user'] = $username;
			$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
			$rep = Curl_Post(API_GET_MEMBER, json_encode($post_data)); 
			$arr_member = array();
			if(is_array($rep['data']) && count($rep['data']) > 0) {
				$arr_member = $rep['data'];
			}
			?>
			<label>Gia hạn sử dụng cho tài khoản</label><small class="cred">(*)</small>
			<select id="txt_username" class="form-control required" name="txt_username">
				<option value="">Chọn Tài khoản</option>
				<?php
				foreach ($arr_member as $key => $value) {
					echo '<option value="'.$value['username'].'">'.$value['fullname'].'</option>';
				}
				?>
			</select>
		<?php } ?>
	</div>

	<div class="form-group">
		<label>Gói dịch vụ:</label><small class="cred">(*)</small>
		<select id="ajax_cbo_packet" class="form-control required" name="cbo_packet">
			<?php
			foreach ($arr_packet as $key => $value) {
				echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
			}
			?>
		</select>
	</div>

	<div class="form-group">
		<label>Thời gian:</label><small class="cred">(*)</small>
		<select name="txt_packet" class="form-control select required" id="txt_packet" data-placeholder="Chọn">
			<option value="">-- Chọn thời gian --</option>
			<?php
			foreach($packet_grade as $key => $value) { 
				$id = $value['id'];
				$price = $value['money'];
				?>
				<option data-price="<?php echo $price;?>" value="<?php echo $value['id'];?>">Gói <?php echo $value['id'];?> tháng - Giá <?php echo number_format($price).'đ';?></option>
				<?php
			}
			?>
		</select>
	</div>

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
		<button type="submit" id="cmd_save" class="btn btn-success"><i class="fa fa-save"></i> Gia hạn</button>
		<button type="button" class="btn btn-default" data-dismiss="modal" value="Hủy bỏ">Hủy bỏ</button>
	</div>
</form>


<script type="text/javascript">
	$(document).ready(function(){
		$("form#ajxs_frm_action").submit(function(e) {
			if(validForm()) {
				e.preventDefault();
				var formData = new FormData(this);
				var _url = "<?php echo ROOTHOST;?>ajaxs/packet/proccess_packet_extend.php";
				$.ajax({
					url: _url,
					type: 'POST',
					data: formData,
					success: function (res) {
						console.log(res);
						var data_return=$.parseJSON(res);
                        if(data_return.status == "success") {
                        	var method_payment=$("#txt_method_payment").val();
                        	var str='';
                        	if(method_payment=='chuyen_khoan'){
                        		str+='<h1 class="text-center">Đăng ký thành công! </h1>';
                        		str+="<p> Vui lòng chuyển khoản với nội dung '<strong>EZ <?php echo $username; ?></strong>' vào một trong các tài khoản sau:</p>";
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
                        	// alert("exist");
                        	showMess("Bạn đã gửi yêu cầu này. Vui lòng đợi Admin xử lý!");
                        	setTimeout(function(){window.location.reload()},2000);
                        }else{
                        	 showMess('Gia hạn không thành công!','error');
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

		$("#txt_packet").change(function(){
			var price = $("#txt_packet option:selected").attr('data-price');
			$('#money_packet').val(price);
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
