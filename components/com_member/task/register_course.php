<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
include_once(CONFIG_PATH."config_sanpham.php");

$msg='';
$username = getInfo('username');	
$rs = sysGetList("ez_member",array(), " AND username='".$username."'"); 
if(!isset($rs[0])) die('Không có dữ liệu');
$row = $rs[0]; 

$sl_product=$sl_child=$txt_note="";
// if(isset($_POST['sl_product'])){
// 	$sl_product=isset($_POST['sl_product'])?antiData($_POST['sl_product']):"";
// 	$sl_child=isset($_POST['sl_child'])?antiData($_POST['sl_child']):"";
// 	$txt_note=isset($_POST['txt_note'])?antiData($_POST['txt_note']):"";
// }

if(isset($_POST['username'])){
	$sl_product=isset($_POST['sl_product'])?antiData($_POST['sl_product']):"";
	$sl_child=isset($_POST['sl_child'])?antiData($_POST['sl_child']):"";
	$txt_note=isset($_POST['txt_note'])?antiData($_POST['txt_note']):"";
	$json=array();
	$json['key']=PIT_API_KEY;
	$json['user_use']=$sl_child;
	$json['user_buy']=$username;
	$json['id_product']=$sl_product;
	$json['note']=$txt_note;
	$json['partner_code']=PARTNER_CODE;
	$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
	$url=API_PUSH_DON_HANG;
	$reponse_data=Curl_Post($url,json_encode($post_data));
	

	if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
		if(isset($reponse_data['data']) &&  $reponse_data['data']=="success"){
			echo "<script language='javascript'>alert('Đăng ký thành công');</script>";
		}
	}else if(isset($reponse_data['status']) && $reponse_data['status']=='no'){
		 if(isset($reponse_data['data']) &&  $reponse_data['data']=="exist"){
			echo "<script language='javascript'>alert('Bạn đã đăng ký gói này');</script>";
		}
	}
}

?>
<script language='javascript'>
function checkinput(){
	 return true;
}
</script>

	<div class="card">
		<div class="card-block">
			<div class="row">
				<div class="col-md-3 col-xs-12"><?php include("user_menu.php");?></div>
				<div class="col-md-8 col-xs-12">
					<div class='header'>
						<h1 class='page-title'>Đăng ký gói gia sư cho con:</h1><hr>
					</div>

					<div class="body box-white">
						<form id="frm_action" name="frm_action" class="frm_action" method="post" action="">
							<input type="hidden" name="username" value="<?php echo $username; ?>">
							<div class="row form-group">
								<label class="col-md-2"></label>
								<div class="col-md-10"><span class='err_mess'><?php echo $msg;?></span></div>
							</div>
							<div class="row form-group">
								<label class="col-md-3 control-label">Chọn gói sản phẩm<span class="star">*</span></label>
								<div class="col-md-6">
									<select name="sl_product" class="form-control sl_product">
										<option value="">--Chọn gói sản phẩm--</option>
										<?php
										if(!empty($_GLOBALS['LIST_SAN_PHAM'])){
											foreach ($_GLOBALS['LIST_SAN_PHAM'] as $key_sp => $value_sp) {
												?>
												<option value="<?php echo $key_sp; ?>" <?php if($key_sp==$sl_product) echo "selected"; ?>><?php echo $value_sp['name']; ?></option>
												<?php
											}
										}
										?>
									</select>

									<div class="box_detail"></div>
								</div>

							</div>
							<div class="row form-group">
								<label class="col-md-3 control-label">Đăng ký cho<span class="star">*</span></label>
								<div class="col-md-6">
									<select name="sl_child" class="form-control sl_child">
										<option value="">--Chọn người học--</option>
										<?php
										$obj = SysGetList('ez_member', array()," AND par_user='$username' ", false);
										while ($row=$obj->Fetch_Assoc()) {
											$username=$row['username'];
											$fullname=$row['fullname'];
											?>
											<option value="<?php echo $username; ?>" <?php if($username==$sl_child) echo "selected"; ?>><?php echo $fullname ?></option>
											<?php
										}
										?>
									</select>

								</div>

							</div>
							<div class="row form-group">
								<label class="col-md-3 control-label">Ghi chú (nếu có)<span class="star">*</span></label>
								<div class="col-md-6">
									<textarea name="txt_note" class="form-control txt_note" rows="3"><?php echo $txt_note; ?></textarea>
								</div>

							</div>

							<div class="form-group text-left">
								<input type="button" name="cmdsave" id="cmdsave" value="Đăng ký" class="btn btn-primary">
							</div>
						</form>
					</div>


					<div class="box_show">
						<div class='header'>
							<h1 class='page-title'>Danh sách đã đăng ký:</h1><hr>
						</div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>STT</th>
									<th>Gói sản phẩm</th>
									
									<th>Lịch học</th>
									
								</tr>
							</thead>
							<tbody>
								<?php
								$json=array();
								$json['user_buy']= getInfo('username');
								include_once(CONFIG_PATH."config_get_list_register_product.php");
								include_once(CONFIG_PATH."config_status_of_order.php");
								$i=0;
								if(!empty($_GLOBALS['LIST_GOI_DANG_KY'])){
									foreach ($_GLOBALS['LIST_GOI_DANG_KY'] as $key_goi => $value_goi) {
										$i++;
										$name_item=isset($value_goi['name'])?$value_goi['name']:"";
										$user_use=isset($value_goi['user_use'])?$value_goi['user_use']:"";
										$price_item=isset($value_goi['price'])?$value_goi['price']:0;
										$from_date=isset($value_goi['from_date'])?$value_goi['from_date']:0;
										$to_date=isset($value_goi['to_date'])?$value_goi['to_date']:0;
										$day_of_week=isset($value_goi['day_of_week'])?json_decode($value_goi['day_of_week'],true):array();
										$time_of_day=isset($value_goi['time_of_day'])?json_decode($value_goi['time_of_day'],true):array();
										$status_item=isset($value_goi['status'])?$value_goi['status']:"";
										?>
										<tr>
											<td>
												1
											</td>
											<td>
												<p><?php echo $name_item; ?></p>
												<p>Giá: <span class="" style="font-size: 16px; font-weight: bold;color: red;"><?php echo number_format($price_item,0,".",","); ?>đ</span></p>
												<p>Người học: <strong><?php echo $user_use; ?></strong></p>
												<p>Trạng thái: 
													<span class="btn btn-success" style="padding: 3px 5px;">
													<?php
														if(isset($_GLOBALS['LIST_STATUS_ORDER'][$status_item])) echo $_GLOBALS['LIST_STATUS_ORDER'][$status_item];
												 	?>
												 	</span>
												 	<?php if($status_item=='L0'){ ?>
												 	<span class="btn btn-primary btn_pay_now" style="padding: 3px 5px;">Thanh toán ngay
												 	</span>
												 <?php } ?>
												</p>
											</td>
										
											<td>
												<p>Từ ngày: <strong><?php if($from_date!=0) echo date("d/m/Y",$from_date); ?></strong> - Đến ngày: <strong><?php if($to_date!=0) echo date("d/m/Y",$to_date); ?></strong>
												</p>
												<p> Lịch dự kiến:</p>
												<table class="table table-bordered">
													<tbody>
														<?php
														if(!empty($day_of_week)){
															foreach ($day_of_week as $key_w => $value_w) {
																?>
																<tr>
																	<td>Thứ <?php echo $value_w; ?></td>
																	<td><?php if(isset($time_of_day[$value_w])) echo $time_of_day[$value_w][0]; ?></td>
																</tr>
																<?php
															}
														}
														?>
													</tbody>
												</table>

											</td>
											
										</tr>
								<?php
									}
								}
								?>
								
							</tbody>
						</table>
					</div>


				</div>
			</div>
		</div>

		
	</div>

<script>
$(document).ready(function(){
	$(".sl_product").change(function(){
		// $(".frm_action").submit();
		var _this=$(this);
		var id_product=_this.val();
		$.post("<?php echo ROOTHOST; ?>ajaxs/register_course/get_infor_item_product.php",{id_product},function(data){
			console.log(data);
			$(".box_detail").html(data);
		});
	});

	$("#cmdsave").click(function(){
		var _this=$(this);
		var str=""; var flag=true;
		var sl_product=$(".sl_product").val();
		var sl_child=$(".sl_child").val();
		var txt_note=$(".txt_note").val();
		if(sl_product==""){
			str+="Vui lòng chọn gói gia sư \n"; flag=false;
		}
		if(sl_child==""){
			str+="Vui lòng chọn người học \n"; flag=false;
		}
		if(flag){
			$(".frm_action").submit();
		}else alert(str);


	});
})
</script>
<?php unset($obj); ?>