<?php 
include_once(CONFIG_PATH."config_sanpham.php");
include_once(CONFIG_PATH."config_get_list_register_product.php");
include_once(CONFIG_PATH."config_status_of_order.php");
if(isset($_GLOBALS['LIST_GOI_DANG_KY']) && count($_GLOBALS['LIST_GOI_DANG_KY'])>0){
	$list=array();
	$this_username=getInfo('username');
	$$type_user=getInfo('utype');
	if($type_user=='chame'){
		$arr = SysGetList("ez_member",array(), " AND par_user='".$this_username."' ORDER BY cdate DESC"); 	
		$arr_user=array();
		if(count($arr)>0){
			foreach($arr as $key=>$r){
				$arr_user[]=$r['username'];
			}
		}
	}
	else $arr_user=array($this_username);
	foreach ($_GLOBALS['LIST_GOI_DANG_KY'] as $key_goi => $value_goi) {
		$user_use=isset($value_goi['user_use'])?$value_goi['user_use']:"";
		if(in_array($user_use,$arr_user)){
			$list[]=$value_goi;
		}
	}
}
if(count($list)>0){
?>
	<table class="table tbl-main">
		<thead>
			<tr>
				<th>STT</th>
				<th>Gói sản phẩm</th>
				<th>Lịch học</th>
				<th class="text-center">Trạng thái</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$i=0;
			
				foreach ($list as $key_goi => $value_goi) {
					$i++;
					
					
					$name_item=isset($value_goi['name'])?$value_goi['name']:"";
					$price_item=isset($value_goi['price'])?$value_goi['price']:0;
					$from_date=isset($value_goi['from_date'])?$value_goi['from_date']:0;
					$to_date=isset($value_goi['to_date'])?$value_goi['to_date']:0;
					$day_of_week=isset($value_goi['day_of_week'])?json_decode($value_goi['day_of_week'],true):array();
					$time_of_day=isset($value_goi['time_of_day'])?json_decode($value_goi['time_of_day'],true):array();
					$status_item=isset($value_goi['status'])?$value_goi['status']:"";
					if($status_item=='L1') $note='<span class="btn btn-large btn-success">Hoàn thành</span>';
					else $note='<div class="txt-notic">Chưa thanh toán</div><span class="btn btn-large btn-primary">Thanh toán ngay</span>';
					?>
					<tr>
						<td>
							<?php echo $i; ?>
						</td>
						<td>
							<p><b><?php echo $name_item; ?></b></p>
							<p class="month">Giá: <span class="" style="font-size: 16px; font-weight: bold;color: red;"><?php echo number_format($price_item,0,".",","); ?>đ</span></p>
							<p class="date">Người học: <strong><?php echo $user_use; ?></strong></p>
						</td>
						<td>
							<p class="date">Từ ngày: <strong><?php if($from_date!=0) echo date("d/m/Y",$from_date); ?></strong></p>
							<p class="date">Đến ngày: <strong><?php if($to_date!=0) echo date("d/m/Y",$to_date); ?></strong>
							</p>
						</td>
						<td class="text-center td-status"><?php echo $note;?></td>
						
					</tr>
			<?php
				
				}
			
			?>
			
		</tbody>
	</table>
<?php 

}
?>
