<?php 
$page='report';
$_SESSION['username_select'.$page]=isset($_GET['sl_username']) ? addslashes($_GET['sl_username']):'';
$filter_username = isset($_SESSION['username_select'.$page])? $_SESSION['username_select'.$page]:'';
$acc_child = api_get_child_member($username); 	
?>
<div class="card">
	<div class="header-title box-header-title">
		<h4 class="title pull-left">Báo cáo học tập của học sinh: <strong><?php echo $_CHILD_INFO['fullname'];?></strong></h4>
		<div class="clearfix"></div>
	</div>

	<div class="box-infomation">
		
		<div class="row row-box">
			<h2 class="fw-400 font-lg d-block">Báo cáo nhiệm vụ học sinh
					<style type="text/css">
						.header_cbo_member {
							margin-left: 20px;
							width: 150px;
							border-radius: 30px;
						}
					</style>
					<?php
					$subject_list=isset($_CHILD_INFO['subject_list'])?$_CHILD_INFO['subject_list']:"";
					$arr_subject_list=array();
					 ?>
					<select id="sl_mon" class="sl_mon form-control header_cbo_member pull-right">
						<?php
						if($subject_list!=''){
							$arr_subject_list=explode(",", $subject_list);
							foreach ($arr_subject_list as $key_subject => $value_subject) {
								$tmp = explode('_', $value_subject);
								$sub_code= isset($tmp[1])?$tmp[1]:"";
								$sub_name = isset($_Conf_Subjects[$sub_code]) ? $_Conf_Subjects[$sub_code]['name']:"";

								?>
								<option value="<?php echo $sub_code; ?>"><?php echo $sub_name; ?></option>
						<?php
							}
						}
						 ?>
					</select>
				</h2>

			<div class="col-md-8 col-box">
				
				<div class="box-report-work scrollbar-stype">
					<?php include_once('content/work.php');?>
				</div>
			</div>

			<div class="col-md-4 col-box">
				<div class="item-infomation">
					<h4 class="title">Lời nhắn từ giáo viên</h4>
					<div class="item-mesager scrollbar-stype" id="">
						<div id="repon_new_chat">
							<?php include_once('content/new_chat.php');?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="home-baocao home-item baocao-chame">
		<div class="header-title">
			<h2 class="fw-400 font-lg d-block pull-left">Đánh giá nhận xét</h2>
			<div class="clearfix"></div>
		</div>

		<div class="row row-box">
			<div class="col-md-4 col-box">
				<div class="info-card scrollbar-stype info-review">
					<div id="repon_review">
						<?php include_once('content/review.php');?>
					</div>
				</div>
			</div>

			<!-- <div class="col-md-8 col-box">
				<div class="info-card info-chat">
					<?php //include_once('content/chat.php');?>
				</div>
			</div> -->
		</div>
	</div>

	<div class="home-baocao home-item baocao-chame">
		<div class="header-title">
			<h2 class="fw-400 font-lg d-block pull-left" >Báo cáo học tập</h2>
			<div class="clearfix"></div>
		</div>

		<div class="row row-box">
			<div class="col-md-6 col-box">
				<div class="clearfix"></div>
				<div class="card box-char-week d-block w-100 border-0 shadow-xss rounded-lg overflow-hidden mb-4">
					<div class="" id="rep_char"></div>
				</div>
			</div>
			<div class="col-md-6 col-box">
				<div class="card box-char-week d-block w-100 border-0 shadow-xss rounded-lg overflow-hidden mb-4">
					<div class="" id="rep_char1"></div>
					<div id="chart-work1"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo ROOTHOST;?>js/apexcharts.js"></script>
<script>
	// $('#sl_username').change(function(){
	// 	$('#frm_search').submit();
	// });

	function load_char(username,type=''){
		if(type==1) url = "<?php echo ROOTHOST;?>ajaxs/chame/char_work.php";
		else url = "<?php echo ROOTHOST;?>ajaxs/chame/char_week.php";
		$.post(url,{username},function(req){
			$('#rep_char'+type).html(req);
			if(type==1){
				var chart = new ApexCharts(document.querySelector("#chart-work1"), options);
				chart.render();
			}
		});
	}

	// $('#sl_username').change(function(){
	// 	var username=$(this).val();
	// 	load_char(username);
	// 	load_char(username,1);
	// });
	
	var username='<?php echo $_CHILD_INFO['username'];?>';
	load_char(username);
	load_char(username,1);
</script>