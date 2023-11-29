
<?php 
$this_username=getInfo('username');
global $type_user;
if($type_user=='chame'){
	$acc_child = sysGetList("ez_member",array(), " AND par_user='".$this_username."' ORDER BY cdate DESC"); 	
	$str_acc_child='';
	foreach($acc_child as $item) { 
		$str_acc_child.="'".$item['username']."',";
	}
	$str_acc_child=substr($str_acc_child,0,-1);
	$username=$str_acc_child;
}
else $username=$this_username;
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_SERVICE_ORDER,json_encode($post_data)); 	
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$_Dichvu = $rep['data'];
	?>
	<table class="table tbl-main">
		<thead>
			<tr>
				<th class="text-center mhide" width="50">STT</th>
				<th class="text-left mhide">Thông tin</th>
				<?php if($type_user=='chame') echo '<th class="text-left mhide">Tài khoản</th>';?>
				<th class="text-right mhide">Đơn giá</th>
				<th class="text-center mhide">Trạng thái</th>
				
			</tr>
		</thead>
		<tbody>
		<?php 
		$stt=0;
		foreach($_Dichvu as $item) { 
		$stt++;
		$username=$item['member'];
		$month = $item['month']; 
		$status = $item['status']; 
		if($status=='L1') $note='<span class="btn btn-large btn-success">Hoàn thành</span>';
			else $note='<div class="txt-notic">Chưa thanh toán</div><span class="btn btn-large btn-primary">Thanh toán ngay</span>';
		?>
		<tr>
			<td class="text-center mhide"><?php echo $stt;?></td>
			<!--<td><b><?php echo $username;?></b></td>-->
			<td class="text-left mhide">
			<h4 class="name">Khóa học giáo viên hướng dẫn</h4>
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
				