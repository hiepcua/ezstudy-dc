<link rel='stylesheet' href='<?php echo ROOTHOST;?>global/char/chart.css'/>
<?php 
$this_grade 	= getInfo('grade');
$this_version 	= getInfo('grade_version'); 
$username=getInfo('username');
$_Subjects=api_get_subject();
if(isset($_POST['gen_nvkhung'])){
	$_SESSION['show_nv_khung']=1;
}
global $packet_user;
global $packet_service;
	?>
	<div class="row1 <?php if($packet_user==false) echo 'packet-free';?>">
		<div class="main-left">
			<?php 
			if($packet_user==true){
				if($packet_service==true) $type_tbl=1;
				else $type_tbl=0;
				// lấy ngày trong tuần của work1
				$arr_date=getDateReport(2); 
				$first_date=isset($arr_date['first'])? $arr_date['first']:'';
				$last_date=isset($arr_date['last'])? $arr_date['last']:'';
				
				$strwhere_nv='';
				//------------------------ Get nhiệm vụ học tập -----------------------------
				$_Nhiemvu=getDataNhiemVu($strwhere_nv,$type_tbl);
				if($type_tbl==1){// nhiệm vụ GVHD
					if(count($_Nhiemvu)<1){// không có dữ liệu thì show nv khung
						if(isset($_SESSION['show_nv_khung'])){
							$type_tbl=0;
							$_Nhiemvu=getDataNhiemVu($strwhere_nv,$type_tbl);
							include_once('hocsinh/nhiemvu.php');
						}
						else{// thông báo show nv khung
							echo '<div class="box-notic-nv text-center">';
							echo '<h1 class="">NHIỆM VỤ</h1>';
							echo '<h4 class="name">Hiện tại chưa có nhiệm vụ do giáo viên tạo. Vui lòng chờ trong ít phút để được giáo viên thêm nhiệm vụ</h4>';
							echo '<form method="POST"><button class="btn btn-success" name="gen_nvkhung">Xem nhiệm vụ khung</button></form>';
							echo '</div>';
						}
					}
					else{
						//echo 'show nv work1';
						include_once('hocsinh/nhiemvu.php');
						if(isset($_SESSION['show_nv_khung'])) unset($_SESSION['show_nv_khung']);
					}
				}
				else {
					if(count($_Nhiemvu)<1){
						include_once("hocsinh/nhiemvu_add.php");
					}
					else include_once('hocsinh/nhiemvu.php');
				}
				
				if(isset($_GET['is_control'])){
					foreach($_Nhiemvu as $k=>$v) { 
						if(isset($v[0])){
							$item=$v[0];
							if($type_tbl==1) $url=ROOTHOST."tool-work/".$item['id'];
							else $url=ROOTHOST."tool-work-config/".$item['id'];
						}
						
						$arr_url[]=$url;
					}
					
					$id=isset($_GET['id'])? (int)$_GET['id']:0;
				
					$rs=SysGetList('ez_bonus_config', array(), "AND id='$id'");
					$type=isset($rs[0]['type'])? $rs[0]['type']:0;
						echo $type;
					if($type==2) {
						$url=count($arr_url)>0 ? $arr_url[0]:'#';
						
						echo '<script>window.location.href="'.$url.'"</script>';
					}
				}
				?>
				<div class="card">
					<div class="box-infomation">
						<h2 class="fw-400 font-lg d-block">Thông tin</h2>
						<div class="row row-box">
							<div class="col-md-3 col-box">
								<div class=" card-item live">
								<span class="ic" ></span>
								<span class="time">20h:00</span>  
								<span class="day">Thứ 3</span>  
								Vào lớp học
								</div>
								<div class="card-item chat-teacher">
								<span class="ic"></span>
								Trao đổi với giáo viên</div>
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
										$live_free=api_get_live_free($this_grade);
										if(count($live_free)>0){
											foreach($live_free as $key=>$item) { 
											$subject=$item['subject'];
											$mon=isset($_Conf_Subjects[$subject]) ? $_Conf_Subjects[$subject]['name']:"";
											
												?>
												<div class="item-live-free ">
												<div class="canader">
													<div class="head"></div>
													<span class="day"><?php echo date('d',$item['start_time']);?></span>
													<span class="month">Tháng <?php echo date('m',$item['start_time']);?></span>
												</div>
													<h4 class="name"><?php echo "Môn ".$mon;?></h4>
													<p class="time"><span class="ic"></span><?php echo date('H:i',$item['start_time'])." - ".date('H:i',$item['end_time']);?></p>
													<div class="clearfix"></div>
												</div>
											<?php 
											}
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
					
			<?php }
			else{
				?>
				<div class="guide-nv">
					<div class="thumbnail">
					<img class="img-responsive" src="<?php echo ROOTHOST;?>images/img-nv2.png">
					<div class="feature tag_01"><span><i class="fa fa-star-o" aria-hidden="true"></i></span> Live cùng Giáo viên</div>
					<div class="feature tag_02"><span><i class="fa fa-sliders" aria-hidden="true"></i></span> Học tập theo lộ trình</div>
					
					<div class="feature tag_04"><span><i class="fa fa-check-square-o" aria-hidden="true"></i></span> Giải pháp học tập hiệu quả</div>
					</div>
					<div class="content-gui">
						<h1>Nhiệm vụ học tập</h1>
						<div class="txt-intro">
							Nhiệm vụ học tập là một giải pháp giúp học tập hiệu quả, tạo ra nhận thức là làm chủ một số kỹ năng nhất định của học sinh. Bao gồm các kỹ thuật phương pháp được giáo viên lựa chọn
						</div>
						<ul class="text-list mb-40 wow fadeInUp2  animated" data-wow-delay=".2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp2;">
							<li><i class="fa fa-check-circle" aria-hidden="true"></i> Học theo lộ trình, nhiệm vụ mà giáo viên biên soạn</li>
							<li><i class="fa fa-check-circle" aria-hidden="true"></i> Bổ xung kiến thức, đánh giá học tập từ đó điều chỉnh kiến thức với từng học sinh cho phù hợp</li>
							<li><i class="fa fa-check-circle" aria-hidden="true"></i> Khối lượng bài thi, bài tập chất lượng </li>
							<li><i class="fa fa-check-circle" aria-hidden="true"></i> Phát triển tư duy, nâng cao kiến thức - học tập tiến bộ</li>
						</ul>
						<a class="btn btn-success" href="#" onclick="frm_packet('EZ1',)"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Đăng ký ngay</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php 
			}
			?>
			<!--Lý Thuyết-->
			<div class="home-monhoc home-item">
				<h2 class="fw-400 font-lg d-block">Môn học</h2>
				<div  class="owl-carousel category-card owl-theme overflow-hidden overflow-visible-xl nav-none">
					<?php $i=0;
					foreach($_Subjects as $k=>$v) {
					
						$i++; 
					?>
					<div class="item ">
						<a href="<?php echo ROOTHOST;?>lession/<?php echo $k;?>" class="item item-subject subject<?php echo $i;?>">
							<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
							<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
						</a>
					</div>
					
					<?php } ?>
				</div>

			</div>
			<?php include_once('hocsinh/dichvu.php');?>
			<?php include_once('hocsinh/khoahoc.php');?>
			
			
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
</script>
