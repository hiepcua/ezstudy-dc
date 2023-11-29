<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
// get packet
$json = array();
$json['key']   = PIT_API_KEY;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rs_packet = Curl_Post(API_PACKET,json_encode($post_data));
?>
<div class="card">
	<div class="card-block">
	<div class="row">
		<div class="col-md-3 col-xs-12"><?php include("user_menu.php");?></div>
		<div class="col-md-9 col-xs-12">
			<div class='header'>
				<h1 class='page-title'>Đăng ký gói học phù hợp với bạn</h1><hr>
			</div>
			
			<div class="package_list row">
			<?php 
			if($rs_packet['status'] == "yes" && is_array($rs_packet["data"])) { 
				$data = $rs_packet["data"];
				foreach($data as $k=>$v) { 
				if($k=='EZ1') continue;
			?>
				<div class="col-md-6 col-xs-12">
					<div class="item">
						<h3 class="title"><?php echo $k;?></h3>
						<div class="name"><?php echo $v['name'];?></div>
						<div class="intro"><?php echo $v['intro'];?></div>
						<a href="#" onclick="frm_packet('<?php echo $k;?>')" class="view-all">Chọn gói <?php echo $k;?></a>
					</div>
				</div>
			<?php  } // end foreach 
			} else echo "<div class='form-group'>Không có dữ liệu gói dịch vụ.</div>" ?>
			</div>
		</div>
	</div>
	</div>
</div>
