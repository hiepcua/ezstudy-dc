<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'config_api.php');

require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
$type_user = getInfo('utype');
$username = getInfo('username');
if(isset($_POST['id'])) { 
	$id_product = antiData($_POST['id']);
	$info=api_get_product();
	if(!isset($info[$id_product])){ die('ID không tồn tại. Vui lòng thử lại');}
	$infor_product=$info[$id_product];
	$name=isset($infor_product['name'])?$infor_product['name']:'';
	$price_item=isset($infor_product['price'])?$infor_product['price']:0;
	$from_date=isset($infor_product['from_date'])?$infor_product['from_date']:0;
	$to_date=isset($infor_product['to_date'])?$infor_product['to_date']:0;
	$day_of_week=isset($infor_product['day_of_week'])?json_decode($infor_product['day_of_week'],true):array();
	$time_of_day=isset($infor_product['time_of_day'])?json_decode($infor_product['time_of_day'],true):array();
	?>
	<h3><?php echo $name;?></h3>
	<hr>
	<form id="frm_action" name="frm_action" class="frm_action" method="post" action="">
		<input type="hidden" name="username" value="<?php echo $username; ?>">
		<input type="hidden" name="sl_product" value="<?php echo $id_product; ?>">
		<div class="row">
			<div class="col-md-5">
				<div class="box_detail">
					<p>Giá: <span class="" style="font-size: 16px; font-weight: bold;color: red;"><?php echo number_format($price_item,0,".",","); ?>đ</span></p>
					<p>Từ ngày: <strong><?php if($from_date!=0) echo date("d/m/Y",$from_date); ?></strong> - Đến ngày: <strong><?php if($to_date!=0) echo date("d/m/Y",$to_date); ?></strong>
					</p>

					<div class="row form-group">
						<label class="col-md-2"></label>
						<div class="col-md-10"><span class='err_mess'><?php echo $msg;?></span></div>
					</div>
					<?php if($type_user=='chame'){?>
						<div class="row form-group">
							<label class="col-md-3 control-label">Đăng ký cho<span class="star">*</span></label>
							<div class="col-md-9">
								<select name="sl_child" class="form-control sl_child">
									<option value="">--Chọn người học--</option>
									<?php
									$obj = SysGetList('ez_member', array()," AND par_user='$username' ", false);
									while ($row=$obj->Fetch_Assoc()) {
										$username=$row['username'];
										$fullname=$row['fullname'];
										?>
										<option value="<?php echo $username; ?>" <?php if($username==$sl_child) echo "selected"; ?>><?php echo $username ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<?php 
					}else{
						echo '<input name="sl_child" type="hidden" value="'.$this_username.'">';
					}
					?>
					<div class=" form-group">
						<label class="control-label">Ghi chú (nếu có)<span class="star">*</span></label>
						<textarea name="txt_note" class="form-control txt_note" rows="3"><?php echo $txt_note; ?></textarea>
					</div>
				</div>
			</div>

			<div class="col-md-7">
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
			</div>
		</div>
		<div class="modal-footer">
			<span class="btn btn-success" id="save_action">Đăng ký</span>
			<button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
		</div>
	</form>
	<?php
}
?>
<script>
	$('#save_action').click(function(){
		var id='<?php echo $id_product;?>';
		var form = $('#frm_action');
		var url= '<?php echo ROOTHOST."histories/product";?>';
		var postData = form.serializeArray();
		$.post('<?php echo ROOTHOST;?>ajaxs/register_course/proccess_buycourse.php',postData,function(req){
		//console.log(req);
		if(req == "success") {
			showMess("Đăng ký khóa học thành công. Hệ thống tự động tải trang sau 3 giây");
			setTimeout(function(){window.location.href=url},3000);
		}
		else{
			showMess('Khóa học này đã được đăng ký!');
			setTimeout(function(){window.location.href=url},1000);
		}
		
		
	});
	})
</script>
