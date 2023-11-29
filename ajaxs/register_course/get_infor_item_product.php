<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');

require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'config_sanpham.php');

if(isset($_POST['id_product'])) { 
	$id_product = antiData($_POST['id_product']);
	$infor_product=isset($_GLOBALS['LIST_SAN_PHAM'][$id_product])?$_GLOBALS['LIST_SAN_PHAM'][$id_product]:array();
	$price_item=isset($infor_product['price'])?$infor_product['price']:0;
	$from_date=isset($infor_product['from_date'])?$infor_product['from_date']:0;
	$to_date=isset($infor_product['to_date'])?$infor_product['to_date']:0;
	$day_of_week=isset($infor_product['day_of_week'])?json_decode($infor_product['day_of_week'],true):array();
	$time_of_day=isset($infor_product['time_of_day'])?json_decode($infor_product['time_of_day'],true):array();
	?>
	<hr>
	<div class="box_detail">
		<p>Giá: <span class="" style="font-size: 16px; font-weight: bold;color: red;"><?php echo number_format($price_item,0,".",","); ?>đ</span></p>
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
	</div>
<?php
}
 ?>