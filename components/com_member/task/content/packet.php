<?php 
$this_username = getInfo('username');
global $type_user;
if($type_user=='chame'){
	// Lấy danh sách tài khoản học sinh con
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['par_user'] = $this_username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_MEMBER, json_encode($post_data)); 
	$acc_child = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$acc_child = $rep['data'];
	}

	$str_acc_child='';
	foreach($acc_child as $item) { 
		$str_acc_child.="'".$item['username']."',";
	}
	$str_acc_child = substr($str_acc_child,0,-1);
	$username = $str_acc_child;
}else{
	$username = $this_username;
}

$json = array();
$json['key']   = PIT_API_KEY;
$json['member'] = $username;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_SERVICE_LIST_ORDER, json_encode($post_data)); 
$service_history = array();
if(is_array($rep['data']) && count($rep['data']) > 0) 
	$service_history = $rep['data'];
			
if(count($service_history)>0) {?>
	<table class="table tbl-main">
		<thead>
			<tr>
				<th class="text-center mhide" width="50">STT</th>
				<th class="text-left mhide">Thông tin Gói</th>
				<?php if($type_user=='chame') echo '<th class="text-left mhide">Tài khoản</th>';?>
				<th class="text-right mhide">Đơn giá</th>
				<th class="text-center mhide">Trạng thái</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$stt=0;
			foreach($service_history as $item) { 
				$stt++;
				$username = $item['member'];
				$month = $item['month']; 
				$status = $item['status']; 
				if($item['packet']=='EZ1')
					$packet_name = "Gói tài khoản Standard";
				else if($item['packet']=='EZ2')
					$packet_name = "Gói tài khoản giáo viên hướng dẫn";

				if($status=='L1') $note='<span class="btn btn-large btn-success">Hoàn thành</span>';
				else $note='<div class="txt-notic">Chưa thanh toán</div><span class="btn btn-large btn-primary">Thanh toán ngay</span>';
				?>
				<tr>
					<td class="text-center mhide"><?php echo $stt;?></td>
					<td class="text-left mhide">
						<h4 class="name"><?php echo $packet_name;?></h4>
						<p class="month"><?php echo $month;?> Tháng</p>
						<p class="date">Ngày đăng ký: <?php echo date('d-m-Y',$item['cdate']);?></p>
					</td>
					<?php if($type_user=='chame') echo '<td class="text-left">'.$username.'</th>';?>
					<td class="text-right"><?php echo number_format($item['price']);?>đ</td>
					<td class="text-center td-status"><?php echo $note;?></td>

				</tr>       
			<?php } ?>
		</tbody>
	</table>
<?php }?>
