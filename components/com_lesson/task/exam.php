<?php 
if(isLogin()){
	$json = array();
	$this_grade = getInfo('grade');
	$this_packet = getInfo('packet');
	$json['key'] = PIT_API_KEY;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_EXAM_GROUP,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arrItem = $rep['data'];
		?>
		
		<div class=" lession-page">
			<div class="row">
				<div class="col-md-9">
					<div class="card-block ">
						<h2 class="page-title"><b>Bộ đề thi, kiểm tra</b></h2>
						<p class="label-title">Chọn một bộ đề thi/kiểm tra để xem mình đang ở mức nào</p>
					</div>
					<div class=" row">

						<?php
						$i=0;
						foreach($arrItem as $r){
							$i++;
							$title = $r['name'];
							$url = ROOTHOST."exam-detail/".$r['id'];
							$icon='';
							switch ($r['id']) {
								case 'G01':
									$icon=ROOTHOST.'images/icons/15m.png';
									break;
								case 'G02':
									$icon=ROOTHOST.'images/icons/45m.png';
									break;
								case 'G03':
									$icon=ROOTHOST.'images/icons/HK1.png';
									break;
								case 'G04':
									$icon=ROOTHOST.'images/icons/HK2.png';
									break;
							}
							?>
							<div class="col-md-12">
								<a class="item-group-exam" href="<?php echo $url;?>">
									<span class="ic bg-gradient-<?php echo $i;?>">
										<!-- <i class="feather-align-right"></i> -->
										<img src="<?php echo $icon; ?>" alt="<?php echo $title;?>">
									</span>
									<?php echo $title;?>
									<p class="txt">Tổng hợp các đề thi thực tế, nội dung phong phú. Kiến thức từ cơ bản đến nâng cao, nhằm phát triển kỹ năng và bổ trợ kiến thức</p>
									<button class="btn btn-success">Chi tiết <i class="fa fa-arrow-right"></i></button>
								</a>
							</div>
							<?php 
						}
						?>
					</div>
					<div class="clearfix"></div>
				</div>

				<div class="col-md-3 bg-white">
					<?php include_once(COM_PATH."com_frontpage/hocsinh/canhan.php"); ?>
				</div>

			</div>
		</div>
		<?php
	} 
} 

?>


