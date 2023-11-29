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
if(isLogin()){
	$username = getInfo('username');
	$type_user = getInfo('utype');
	if(isset($_POST['id'])) { 
		$id = (int)$_POST['id'];
		$_Service =api_get_packet(2);
		$grade=getInfo('grade');
		if(isset($_Service[$grade])){
			$item=$_Service[$grade];
			$packet=json_decode($item['packet'],true);
			$info_packet=isset($_Service['info_packet'][0])? $_Service['info_packet'][0]:array();
			$name_packet=isset($info_packet['name'])? $info_packet['name']:'Đang cập nhật';
			$intro_packet=isset($info_packet['intro'])? $info_packet['intro']:'Đang cập nhật';

			if(isset($packet[$id])){
				$value=$packet[$id];
				$id=$value['id'];
				$price=$value['money'];
				?>
				<div class="text-center box-confirm">
					<p class="title">Xác nhận đăng ký dịch vụ <span class="color-label"><?php echo $name_packet; ?></span></p>
					<div class="info-packet-md">Gói: <?php echo $id." Tháng";?> - Giá: <span class="price"><?php echo number_format($price);?>đ</span></div>
					<div class="form-group input-select">
						<?php if($type_user=='hocsinh'){?>
							<!-- <label>Tài khoản</label> -->
							<input type="hidden" value="<?php echo $username;?>" name="txt_username" disabled class="form-control" id="txt_username" placeholder="">
						<?php }else {?>
							<label>Gia hạn cho tài khoản</label>
							<select name="txt_username" class="form-control" id="txt_username" data-placeholder="Chọn">
								<option value="">Chọn Tài khoản</option>
								<?php
								$obj = SysGetList('ez_member', array()," AND par_user='$username'", false);
								while ($row=$obj->Fetch_Assoc()) {
									$username=$row['username'];
									?>
									<option value="<?php echo $username; ?>"><?php echo $username ?></option>
									<?php
								}
								?>
							</select>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}
		?>	
		<div class="modal-footer">
			<span class="btn btn-success" id="save_action">Đăng ký ngay</span>
			<button type="button" class="btn btn-default" data-dismiss="modal">Hủy bỏ</button>
		</div>
		<?php
	}
}
?>
<script>
	$('#save_action').click(function(){
		var id='<?php echo $id;?>';
		var username=$('#txt_username').val();
		var price='<?php echo $price;?>';
		var url = "<?php echo ROOTHOST;?>ajaxs/service/proccess_service.php";
		$.post(url, {id,username,price}, function(req){
			var url2="<?php echo ROOTHOST;?>histories/service";
			if(req == "success") {
				showMess("Đăng ký thành công. Hệ thống tự động tải trang sau 3 giây");
				setTimeout(function(){window.location.href=url2},2000);
			}
			else if(req == "registed") {
				showMess('Dịch vụ này đã được đăng ký!');
				setTimeout(function(){window.location.href=url2},1000);
			}
			else showMess(req);
		});
	})
</script>

