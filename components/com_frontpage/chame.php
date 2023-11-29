<?php 
$flag = true;
$str_acc_child 	= '';
$username = getInfo('username');

$flag_ez1 = $flag_ez2 = false;
if(is_array($_ARR_CHILDS) && count($_ARR_CHILDS)>0){
	foreach ($_ARR_CHILDS as $key => $value) {
		if($value['packet']=="EZ1") $flag_ez1 = true;
		if($value['packet']=="EZ2") $flag_ez2 = true;
	}
}
?>

<?php if($flag_ez1 == false && $flag_ez2 == false){ ?>
	<div class="card">
		<div class="box-infomation free">
			<h2 class="fw-400 font-lg d-block">Thông tin</h2>
			<div class="row row-box">
				<div class="col-md-4 col-box">
					<div class="info-card item-account">
						<div class="item-account-box scrollbar-stype">
							<?php			
							if(is_array($_ARR_CHILDS) && count($_ARR_CHILDS)>0) {?>
								<table class="table tbl-main">
									<thead>
										<tr>
											<th>Thông tin</th>
											<th width="120">Hạn sử dụng</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$stt=0;
										foreach($_ARR_CHILDS as $item) { 
											$stt++;
											$cur_packet = $item['packet'];
											$packet_status = $item['packet_status'];
											$str_acc_child.= $item['username'].",";
											$edate = date('d-m-Y',$item['edate']);
											$today = date('d-m-Y');
											$diff="-";
											if($edate!=''){
												$today=date('d-m-Y');
												$date1=date_create($today);
												$date2=date_create($edate);
												$diff=date_diff($date1,$date2);
											}
											?>
											<tr>
												<td>
													<?php echo $item['username'];?>
													<div class="fullname"><?php echo $item['fullname'];?></div>
												</td>
												<td class="text-center">
													<?php
													if($cur_packet==""){
														echo '<span class="label-txt1">Gói Free</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
													}else{
														if($cur_packet=="EZ1"){
															echo '<span class="label-txt1">Gói cơ bản</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
															if($diff->days<=MIN_DAY && $packet_status=='running'){
																echo '<div class="box-date">
																<span class="date-txt">'.$edate.'</span>
																<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
															}
														}else if($cur_packet=="EZ2"){
															echo '<span class="label-txt1">Gói nâng cao</span>';
															if($diff->days<=MIN_DAY && $packet_status=='running'){
																echo '<div class="box-date">
																<span class="date-txt">'.$edate.'</span>
																<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
															}
														}
													}
													?>
												</td>
											</tr>       
										<?php } ?>
									</tbody>
								</table>
							<?php } else{
								echo "Không có tài khoản con";
							} ?>
						</div>
					</div>
				</div>

				<div class="col-md-8 home-box-course chame">
					<div class="row">
						<div class="col-md-6">
							<div class="course-item">
								<h3 class="course-name ez1">Cơ bản</h3>
								<div class="content">
									<div class="box-price">
										<div class="left">
											<span class="price font-20">88.000đ</span>
											<span>/ tháng</span>
											<!-- <div class="month">x 12 tháng</div> -->
										</div>

									</div>

									<div class="desc">
										<ul>
											<li>
												<i class="fa fa-check-circle" aria-hidden="true"></i>Đăng ký tài khoản miễn phí</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho học liệu đa phương tiện theo chuẩn BGD & ĐT</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Bảng nhiệm vụ học tập hướng đối tượng</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho bài luyện tập đa dạng: 100.000+ câu hỏi trắc nghiệm, tự luận...</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>2000+ bài kiểm tra, đề luyện thi</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Báo cáo kết quả học tập cho phụ huynh</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Cố vấn học tập ảo</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Nhận điểm thưởng Sao & Kim cương với mỗi hoàn thành nhiệm vụ học tập</li>
											</ul>
										</div>

										<div class="wr-btn-regis text-center">
											<a href="javascript:void(0)" class="btn-regis" onclick="regis_packet('EZ1')">Đăng ký ngay</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="course-item">
									<h3 class="course-name ez2">Nâng cao</h3>
									<div class="content">
										<div class="box-price">
											<div class="left">
												<span class="price font-20">199.000đ</span>
												<span class="month">/ tháng</span>
											</div>

										</div>

										<div class="desc">
											<ul>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Đăng ký tài khoản miễn phí</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho học liệu đa phương tiện theo chuẩn BGD & ĐT</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Bảng nhiệm vụ học tập hướng đối tượng</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Kho bài luyện tập đa dạng: 100.000+ câu hỏi trắc nghiệm, tự luận...</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>2000+ bài kiểm tra, đề luyện thi</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Báo cáo kết quả học tập cho phụ huynh</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Cố vấn học tập ảo</li>
												<li><i class="fa fa-check-circle" aria-hidden="true"></i>Nhận điểm thưởng Sao & Kim cương với mỗi hoàn thành nhiệm vụ học tập</li>
												<li ><i class="fa fa-check-circle" aria-hidden="true"></i>Giáo viên/ gia sư hướng dẫn học, điều chỉnh lộ trình học tập thích hợp cho từng học sinh, giải đáp thắc mắc, chấm bài và nhận xét kết quả học tập</li>
											</ul>
										</div>

										<div class="wr-btn-regis text-center">
											<a href="javascript:void(0)" class="btn-regis" onclick="regis_packet('EZ2')">Đăng ký ngay</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	<?php } else if($flag_ez1 == true && $flag_ez2 == false){ ?>
		<div class="card">
			<div class="box-infomation ez1">
				<h2 class="fw-400 font-lg d-block">Thông tin</h2>
				<div class="row row-box">
					<div class="col-md-4 col-box">
						<div class="info-card item-account">
							<div class="item-account-box scrollbar-stype">
								<?php			
								if(count($_ARR_CHILDS)>0) {?>
									<table class="table tbl-main">
										<thead>
											<tr>
												<th>Thông tin</th>
												<th width="120">Hạn sử dụng</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$stt=0;
											foreach($_ARR_CHILDS as $item) { 
												$stt++;
												$cur_packet = $item['packet'];
												$packet_status = $item['packet_status'];
												$str_acc_child.= $item['username'].",";
												$edate = date('d-m-Y',$item['edate']);
												$today = date('d-m-Y');
												$diff="-";
												if($edate!=''){
													$today=date('d-m-Y');
													$date1=date_create($today);
													$date2=date_create($edate);
													$diff=date_diff($date1,$date2);
												}
												?>
												<tr>
													<td>
														<?php echo $item['username'];?>
														<div class="fullname"><?php echo $item['fullname'];?></div>
													</td>
													<td class="text-center">
														<?php
														if($cur_packet==""){
															echo '<span class="label-txt1">Gói Free</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
														}else{
															if($cur_packet=="EZ1"){
																echo '<span class="label-txt1">Gói cơ bản</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
																if($diff->days<=MIN_DAY && $packet_status=='running'){
																	echo '<div class="box-date">
																	<span class="date-txt">'.$edate.'</span>
																	<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
																}
															}else if($cur_packet=="EZ2"){
																echo '<span class="label-txt1">Gói nâng cao</span>';
																if($diff->days<=MIN_DAY && $packet_status=='running'){
																	echo '<div class="box-date">
																	<span class="date-txt">'.$edate.'</span>
																	<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
																}
															}
														}
														?>
													</td>
												</tr>       
											<?php } ?>
										</tbody>
									</table>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="col-md-8 home-box-course chame">
						<div class="row">
							<div class="col-md-6">
								<div class="course-item">
									<h3 class="course-name ez2">Khóa học nâng cao</h3>
									<div class="content">
										<div class="box-price">
											<div class="left">
												<div class="price font-20">175.000 VNĐ</div>
												<div class="month">x 12 tháng</div>
											</div>
											<div class="right">
												<div class="price">196.700 VNĐ x 3 tháng</div>
												<div class="price">183.300 VNĐ x 3 tháng</div>
											</div>
										</div>

										<div class="desc">
											<ul>
												<li><i class="fa fa-square" aria-hidden="true"></i>Đăng ký tài khoản miễn phí</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Kho học liệu đa phương tiện theo chuẩn BGD & ĐT</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Bảng nhiệm vụ học tập hướng đối tượng</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Kho bài luyện tập đa dạng: 100.000+ câu hỏi trắc nghiệm, tự luận...</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>2000+ bài kiểm tra, đề luyện thi</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Báo cáo kết quả học tập cho phụ huynh</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Cố vấn học tập ảo</li>
												<li><i class="fa fa-square" aria-hidden="true"></i>Nhận điểm thưởng Sao & Kim cương với mỗi hoàn thành nhiệm vụ học tập</li>
												<li class="active"><i class="fa fa-square" aria-hidden="true"></i>Giáo viên/ gia sư hướng dẫn học, điều chỉnh lộ trình học tập thích hợp cho từng học sinh, giải đáp thắc mắc, chấm bài và nhận xét kết quả học tập</li>
											</ul>
										</div>

										<div class="wr-btn-regis text-center">
											<a href="javascript:void(0)" class="btn-regis" onclick="regis_packet('EZ2')">Đăng ký ngay</a>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6 col-box">
								<div class="info-card" >
									<h4 class="title">Thông tin chung</h4>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	<?php } else if($flag_ez2 == true){ ?>
		<div class="card">
			<div class="box-infomation ez2">
				<h2 class="fw-400 font-lg d-block">Thông tin</h2>
				<div class="row row-box">
					<div class="col-md-4 col-box">
						<div class="info-card item-account">
							<div class="item-account-box scrollbar-stype">
								<?php			
								if(count($_ARR_CHILDS)>0) {?>
									<table class="table tbl-main">
										<thead>
											<tr>
												<th>Thông tin</th>
												<th width="120">Hạn sử dụng</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$stt=0;
											foreach($_ARR_CHILDS as $item) { 
												$stt++;
												$cur_packet = $item['packet'];
												$packet_status = $item['packet_status'];
												$str_acc_child.= $item['username'].",";
												$edate = date('d-m-Y',$item['edate']);
												$today = date('d-m-Y');
												$diff="-";
												if($edate!=''){
													$today=date('d-m-Y');
													$date1=date_create($today);
													$date2=date_create($edate);
													$diff=date_diff($date1,$date2);
												}
												?>
												<tr>
													<td>
														<?php echo $item['username'];?>
														<div class="fullname"><?php echo $item['fullname'];?></div>
													</td>
													<td class="text-center">
														<?php
														if($cur_packet==""){
															echo '<span class="label-txt1">Gói Free</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
														}else{
															if($cur_packet=="EZ1"){
																echo '<span class="label-txt1">Gói cơ bản</span><button class="btn btn-success" onclick="frm_packet_upgrade(\''.$item['username'].'\')">Nâng cấp</button>';
																if($diff->days<=MIN_DAY && $packet_status=='running'){
																	echo '<div class="box-date">
																	<span class="date-txt">'.$edate.'</span>
																	<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
																}
															}else if($cur_packet=="EZ2"){
																echo '<span class="label-txt1">Gói nâng cao</span>';
																if($diff->days<=MIN_DAY && $packet_status=='running'){
																	echo '<div class="box-date">
																	<span class="date-txt">'.$edate.'</span>
																	<button class="btn btn-primary" onclick="frm_packet_extend(\''.$item['username'].'\')">Gia hạn</button>';
																}
															}
														}
														?>
													</td>
												</tr>       
											<?php } ?>
										</tbody>
									</table>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="col-md-4 col-box">
						<div class="info-card" >
							<div class="header-title">
								<h4 class="title pull-left">Lời nhắn từ giáo viên</h4>
								<div class="clearfix"></div>
							</div>

							<div class="item-mesager scrollbar-stype" id="rep_notice">
								<?php 
								$str_acc_child = substr($str_acc_child,0,-1);
								$_Notification = api_get_mesenger($str_acc_child,1);
								if(count($_Notification)>0){
									foreach($_Notification as $key=>$item) { 
										?>
										<div class="item-noti">
											<p><?php echo $item['content'];?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $item['info_tomember'];?></span></p>
										</div>
										<?php 
									}
								}
								?>
							</div>
						</div>
					</div>

					<div class="col-md-4 col-box">
						<div class="info-card" >
							<h4 class="title">Thông tin chung</h4>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	<?php } ?>


	<div class="home-baocao home-item baocao-chame">
		<div class="header-title">
			<h2 class="fw-400 font-lg d-block pull-left">Báo cáo học tập</h2>
			<div class="clearfix"></div>
		</div>

		<div class="row row-box">
			<div class="col-md-6 col-box">
				<div class="card box-char-week d-block w-100 border-0 shadow-xss rounded-lg overflow-hidden mb-4">
					<div class="header-report bg1">Thời lượng truy cập</div>
					<div class="" id="rep_char"></div>
				</div>
			</div>

			<div class="col-md-6 col-box">
				<div class="card box-char-week d-block w-100 border-0 shadow-xss rounded-lg overflow-hidden mb-4">
					<div class="header-report bg2">Báo cáo luyện tập</div>
					<div class="" id="rep_char1"></div>
					<div id="chart-work1"></div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?php echo ROOTHOST;?>js/apexcharts.js"></script>
	<script>
		$('#txt_user').change(function(){
			var txt_user = $(this).val();
			var url = "<?php echo ROOTHOST;?>ajaxs/member/list_mesenger.php";
			$.post(url,{txt_user},function(req){
				$('#rep_notice').html(req);
			});
		});

		function load_char(username,type=''){
			if(type==1) url = "<?php echo ROOTHOST;?>ajaxs/chame/char_work.php";
			else url = "<?php echo ROOTHOST;?>ajaxs/chame/char_week.php";
			$.post(url,{username},function(req){
				$('#rep_char'+type).html(req);
				if(type==1){
				// console.log(req);
				var chart = new ApexCharts(document.querySelector("#chart-work1"), options);
				chart.render();
			}
		});
		}

		var username='<?php echo $_CHILD_INFO['username'];?>';
		load_char(username);
		load_char(username,1);
	</script>

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
