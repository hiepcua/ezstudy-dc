<link rel='stylesheet' href='<?php echo ROOTHOST;?>global/char/chart.css'/>
<?php 
$this_grade 	= getInfo('grade');
$this_version 	= getInfo('grade_version'); 
$username = getInfo('username');
$packet_user = getInfo('packet');
$packet_status = getInfo('packet_status');
$_Subjects = api_get_subject();
if(isset($_POST['gen_nvkhung'])){
	$_SESSION['show_nv_khung']=1;
}
global $packet_user;
?>
<div class="row1 <?php if($packet_user==false) echo 'packet-free';?>">
	<div class="main-left">
		<?php 
		// lấy ngày trong tuần của work1
		$arr_date=getDateReport(2); 
		$first_date=isset($arr_date['first'])? $arr_date['first']:'';
		$last_date=isset($arr_date['last'])? $arr_date['last']:'';
		$strwhere_nv='';
		//------------------------ Get nhiệm vụ học tập -----------------------------
		$_Nhiemvu=getDataNhiemVu($strwhere_nv,0);
		// var_dump($_Nhiemvu);
		include_once('hocsinh/nhiemvu.php');
		?>

		<?php if($packet_user==true){ ?>
			<div class="card">
				<div class="box-infomation">
					<h2 class="fw-400 font-lg d-block">Thông tin</h2>
					<div class="row row-box">
						<div class="col-md-3 col-box">
							<div class="card-item live">
								<?php
								$cur_time = time();
								$cur_day = strtotime(date("Y/m/d"));
								$day_name = conver_day_name(date('l')); 
								$live_free_inday = api_get_live_free($this_grade,'','','',$cur_day);
								if(count($live_free_inday)>0){
									$live_running = $live_upcoming = array();

									foreach ($live_free_inday as $key => $item) {
										$running = $item['running'];
										$stime = $item['start_time'];
										$etime = $item['end_time'];

										if($running=="yes") $live_running[] = $item;
										else if($stime>$cur_time) $live_upcoming[] = $item;
									}

									if(count($live_running)>0){
										$live = $live_running[0];
										$item_id = $live['id'];
										$item_stime = date("H:i", $live['start_time']);

										echo '<span class="ic" ></span>
										<span class="time">'.$item_stime.'</span>';
										// echo '<span class="day">'.$day_name.'</span>';
										echo '<a href="javascript:void(0)" class="join_room" onclick="join_room(\''.$item_id.'\')">Tham gia live</a>';
									}else{
										$live = $live_upcoming[0];
										$item_id = $live['id'];
										$item_stime = date("H:i", $live['start_time']);

										echo '<span class="ic" ></span>
										<span class="time">'.$item_stime.'</span>';
										// echo '<span class="day">'.$day_name.'</span>';
									}
								}else{
									echo 'Không có lịch live';
								}
								?>
							</div>

							<div class="card-item chat-teacher">
								<?php if($packet_user=="EZ2"){ ?>
									<a class="" href="<?php echo ROOTHOST."chat";?>">
										<span class="ic"></span>
										Hỏi đáp với giáo viên
									</a>
								<?php }else{ ?>
									<a class="" href="javascript:void(0)" onclick="show_upgrade_notify_popup()">
										<span class="ic"></span>
										Hỏi đáp với giáo viên
									</a>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-9 col-box">
							<div class="row row-box">
								<div class="col-md-6 col-box">
									<div class="item-infomation" >
										<h4 class="title">Lời nhắn từ giáo viên</h4>
										<div class="item-mesager scrollbar-stype" id="rep_notice">
											<?php 
											$_Notification=api_get_mesenger();
											if(count($_Notification)>0){
												foreach($_Notification as $key=>$item) { 
													?>
													<div class="item-noti">
														<p><?php echo $item['content'];?><span class="noti-user"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $item['info_tomember'];?></span></p>
													</div>
													<?php 
												}
											}else{
												echo "Chưa có lời nhắn";
											}
											?>
										</div>
									</div>
								</div>

								<div class="col-md-6 col-box">
									<div class="item-infomation" id="">
										<h4 class="title">Lịch giảng</h4>
										<div class="item-box-live scrollbar-stype" >
											<?php 
											$live_free = api_get_live_free($this_grade,'','','');
											if(count($live_free)>0){
												foreach($live_free as $key=>$item) { 
													$subject=$item['subject'];
													$mon=isset($_Conf_Subjects[$subject]) ? $_Conf_Subjects[$subject]['name']:"";
													$active = $item['running']=="yes"?"active":"";
													?>
													<div class="item-live-free <?php echo $active;?>">
														<div class="canader">
															<div class="head"></div>
															<span class="day"><?php echo date('d',$item['start_time']);?></span>
															<span class="month">Tháng <?php echo date('m',$item['start_time']);?></span>
														</div>
														<h4 class="name"><?php echo "Môn ".$mon;?></h4>
														<?php if($active=="active"){ ?>
															<p class="time">Đang live</p>
														<?php } else { ?>
															<p class="time"><span class="ic"></span><?php echo date('H:i',$item['start_time'])." - ".date('H:i',$item['end_time']);?></p>
														<?php } ?>
														<div class="clearfix"></div>
													</div>
													<?php 
												}
											}else{
												echo '<div>Hiện chưa có lịch giảng</div>';
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<!--Môn học-->
		<div class="home-monhoc home-item">
			<h2 class="fw-400 font-lg d-block">Môn học</h2>
			<div  class="owl-carousel category-card owl-theme overflow-hidden overflow-visible-xl nav-none">
				<?php $i=0;
				foreach($_Subjects as $k=>$v) {
					$i++; 
					?>
					<div class="item">
						<a href="<?php echo ROOTHOST;?>lession/<?php echo $k;?>" class="item item-subject subject<?php echo $i;?>">
							<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
							<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php if(!$packet_user){ ?>
			<!-- Khóa học -->
			<div class="home-box-course">
				<h2 class="fw-400 font-lg d-block">Nâng cấp khóa học</h2>
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
		<?php } ?>

		<?php $grade = getInfo('grade'); ?>

		<!--Báo cáo-->				
		<div class="home-baocao home-item">
			<h2 class="fw-400 font-lg d-block">Báo cáo học tập</h2>
			<?php include_once('hocsinh/char_week.php');?>
		</div>
	</div>

	<div class="main-right box-nav-right">
		<?php include_once('hocsinh/canhan.php');?>	
	</div>
</div>

<script>
	$(".nav-tabs-ctr a").click(function(){
		$(".nav-tabs-ctr a").removeClass('active');
		$(this).addClass('active');
	});

	// Đăng ký gói dịch vụ, nâng cấp gói dịch vụ
	function frm_packet(cur_packet=""){
		$('#myModalPopup .modal-dialog').removeClass('modal-lg');
		$('#myModalPopup .modal-title').html('Nâng cấp khóa học');
		
		var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_packet.php";
		$.post(url, {"cur_packet": cur_packet},function(req){
			$('#modal-content').html(req);
			$('#myModalPopup').modal('show');
		});
	}
</script>