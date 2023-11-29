<!--Khóa học-->
<div class="home-khoahoc home-item box-service">
	<h2 class="fw-400 font-lg d-block">Khóa học giáo viên hướng dẫn</h2>
	<div class="owl-carousel category-card owl-theme overflow-hidden overflow-visible-xl nav-none">
		<?php
		$_Service = api_get_packet(2);
		$grade=getInfo('grade');
		if(isset($_Service[$grade])){
			$item=$_Service[$grade];
			$packet=json_decode($item['packet'],true);
			$info_packet=isset($_Service['info_packet'][0])? $_Service['info_packet'][0]:array();
			$name_packet=isset($info_packet['name'])? $info_packet['name']:'Đang cập nhật';
			$intro_packet=isset($info_packet['intro'])? $info_packet['intro']:'Đang cập nhật';
			$i=0;
			foreach ($packet as $key => $value) {
				$i++;
				$id=$value['id'];
				$price=$value['money'];
				$from_date=date('d-m-Y',$value['from_date']);
				?>
				<div class="item">
					<div onclick="buy_course(<?php echo $key;?>,1)" class="card course-card w235 p-0 shadow-xss border-0 rounded-lg overflow-hidden mr-1 mb-4">
						<div class="card-image w-100 mb-3">
							<img src="images/service<?php echo $i;?>.jpg" alt="image" class="w-100">
							<span class="font-xss gia fw-700 pl-3 pr-3 ls-2 lh-32 d-inline-block text-success float-right"><?php echo number_format($price); ?>đ</span>
						</div>
						<div class="card-body pt-0">
							<h4 class="name fw-700 font-xss mt-3 lh-28 mt-0"><a href="default-course-details.html" class="text-dark text-grey-900"><?php echo $name_packet; ?></a></h4>
							<div class="info-packet">Gói: <?php echo $id." Tháng";?></div>
							<p class="txt"><?php echo $intro_packet;?></p>
							<div class="text-center">
								<a href="javascript:void(0)" class="btn btn-primary btn-service">Đăng ký ngay</a>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>	
	</div>
</div>
