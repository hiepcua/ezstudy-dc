<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'gffunc_comment.php');
require_once(libs_path.'cls.mysql.php');
$member=isset($_POST['member']) ? antiData($_POST['member']):'';
$get_grade=isset($_POST['get_grade']) ? antiData($_POST['get_grade']):'';
$first_subject=isset($_POST['first_subject']) ? antiData($_POST['first_subject']):'';
$status=isset($_POST['status']) ? antiData($_POST['status']):'all';
// Lấy danh sách report của học sinh
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['member_user'] = $member; // to-member
	$json['grade'] = $get_grade;
	$json['subject'] = $first_subject;
	if(isset($_POST['status'])){
		$json['status'] = antiData($_POST['status']);
	}
	// var_dump($json);
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE), PIT_API_KEY);
	$rep = Curl_Post(API_GET_LIST_REPORT_KHUNG_NV, json_encode($post_data)); 
	$arr_list_khung_nv=isset($rep['data'])?$rep['data']:array();
?>

<div class="box_report">
	<ul class="ul_list">
	<?php
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['member_user'] = $member; // to-member
	$json['grade'] = $get_grade;
	$json['subject'] = $first_subject;
	$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
	$url = API_COUNT_KHUNG_NV;
	$reponse_data = Curl_Post($url,json_encode($post_data));
	$arr_count_report=isset($reponse_data['data'])?$reponse_data['data']:array();
	$time_learn=isset($arr_count_report['time_learn'])?(int)$arr_count_report['time_learn']:0;
	$count_chua_lam=isset($arr_count_report['chua_lam'])?(int)$arr_count_report['chua_lam']:0;
	$count_da_lam_total=isset($arr_count_report['da_lam']['total'])?(int)$arr_count_report['da_lam']['total']:0;
	$count_chua_qua=isset($arr_count_report['da_lam']['1'])?(int)$arr_count_report['da_lam']['1']:0;
	$count_da_qua=isset($arr_count_report['da_lam']['2'])?(int)$arr_count_report['da_lam']['2']:0;
	// echo json_encode($post_data);
	// echo "reponse_data<pre>";
	// var_dump($reponse_data);
	// echo "</pre>";
	// status:"" chưa làm gì, 1 là đã làm chưa qua, 2 là đã qua
	// flag: "", chưa active, L1 là đang làm, đã active, L2 là đã làm
	//chưa làm: status='' AND flag=L1
	// đã làm: status IN (1,2) AND flag=L2
	?>
	<li>Tổng nhiệm vụ: 
		<a href="#" class="count_item" style="padding: 0px;" data-status='all'>
			<strong>
				<?php echo number_format($count_chua_lam + $count_da_lam_total,0,".",","); ?>
			</strong>
		</a>
	</li>
	<li>Chưa làm: 
		<a href="#" class="count_item" data-status='chua'>
			<strong >
				<?php echo number_format($count_chua_lam,0,".",","); ?>
			</strong>
		</a>
	</li>
	<li>
		Đã làm: 
		
		<strong class="">
			<?php echo number_format($count_da_lam_total,0,".",","); ?>
		</strong>

		(Chưa qua: 
			<a href="#" class="count_item" data-status='no'>
			<strong ><?php echo $count_chua_qua; ?></strong>
			</a>
		- Đã qua: 
		<a href="#" class="count_item" data-status='yes'>
		<strong ><?php echo $count_da_qua;?></strong>
		</a>
		)
		</li>
		<li>Tổng thời gian làm bài: 
			<strong><?php echo round($time_learn/60); ?>p</strong>

		</li>
	</ul>
	</div>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>STT</th>
			<th>Tên nhiệm vụ</th>
			<th>Kết quả</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i=0;
		if(!empty($arr_list_khung_nv)){
			foreach ($arr_list_khung_nv as $key_item => $value_item) {
				$i++;
				$id_khung_nv=isset($value_item['id'])?$value_item['id']:"";
				$name_khung_nv=isset($value_item['title'])?$value_item['title']:"";
				$flag_khung_nv=isset($value_item['flag'])?$value_item['flag']:"";
				$status_khung_nv=isset($value_item['status'])?(int)$value_item['status']:"";
				$time_learn_khung_nv=isset($value_item['time_learn'])?(int)$value_item['time_learn']:0;
				$today_khung_nv=isset($value_item['today'])?(int)$value_item['today']:0;
				$start_khung_nv=isset($value_item['start_time'])?(int)$value_item['start_time']:0;
				$mark_khung_nv=isset($value_item['mark'])?(int)$value_item['mark']:0;
				$number_quiz_khung_nv=isset($value_item['number_quiz'])?(int)$value_item['number_quiz']:0;
				$pass_percent_khung_nv=isset($value_item['pass_percent'])?(int)$value_item['pass_percent']:0;
				$results_khung_nv=isset($value_item['results'])&&$value_item['results']!=''?json_decode($value_item['results'],true):array();
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td>
						<a href="#" class="btn_view_detail_khung" data-id="<?php echo $id_khung_nv; ?>">
							<?php echo $name_khung_nv; ?>
						</a><br>
						<span class="btn btn-info" style="padding: 0px 3px;background: <?php if(isset($_KhungNhiemVuFlagStyle[$flag_khung_nv])) echo $_KhungNhiemVuFlagStyle[$flag_khung_nv] ?>">
							<?php 
							if(isset($_KhungNhiemVuFlag[$flag_khung_nv])) echo $_KhungNhiemVuFlag[$flag_khung_nv]; 
							?></span>  <?php if($start_khung_nv!=0) echo "lúc ".date("d/m/Y H:i",$start_khung_nv); ?>
						</td>

						<td>
							<ul class="ul_list_report">
								<li>
									<i class="fa fa-bullseye fa_user" aria-hidden="true"></i>
									<div class="pull-left">
										<p><?php if($number_quiz_khung_nv!=0) echo round($mark_khung_nv/$number_quiz_khung_nv*100); ?>%</p>
										<p>Chính xác</p>
									</div>
								</li>
								<li>
									<i class="fa fa-tachometer fa_user" aria-hidden="true"></i> 
									<div class="pull-left">
										<p><?php 
										$phut=round($time_learn_khung_nv/60);
										if(count($results_khung_nv)!=0) echo round($phut/count($results_khung_nv),2);else echo "0.00"; ?> phút/câu</p>
										<p>Tốc độ</p>
									</div>
								</li>
								<li>
									<i class="fa fa-question fa_user" aria-hidden="true"></i>
									<div class="pull-left">
										<p><?php echo count($results_khung_nv); ?> câu</p>
										<p>Đã làm</p>
									</div>
								</li>
								<li>
									<i class="fa fa-flag fa_user" aria-hidden="true"></i>
									<div class="pull-left">
										<p>Đúng: <?php echo $mark_khung_nv; ?> câu</p>
										<p>
											<strong><?php 
											if(isset($_KhungNhiemVuStatus[$status_khung_nv])) echo $_KhungNhiemVuStatus[$status_khung_nv]; 
											?>
										</strong>
									</p>
								</div>
							</li>

						</ul>


					</td>

				</tr>
				<?php
			}
		} ?>

	</tbody>
</table>

<script type="text/javascript">
	$(document).ready(function(){
		
			$(".btn_view_detail_khung").click(function(){
				var _this=$(this);
				var id_khung_nv=_this.attr("data-id");
				$("#myModalPopup .modal-title").html("Show chi tiết khung nhiệm vụ:");
				$("#myModalPopup .modal-dialog").addClass("modal-lg");
				$.post("<?php echo ROOTHOST; ?>ajaxs/report_khung_nv/view_detail_khung_nv.php",{id_khung_nv},function(data){
					$("#myModalPopup .modal-body").html(data);
					$("#myModalPopup").modal("show");
				})

				return false;
			});

			// count_chua_lam
			var member="<?php echo $member; ?>";
			var get_grade="<?php echo $get_grade; ?>";
			var first_subject="<?php echo $first_subject; ?>";
			$(".count_item").click(function(){
				var status=$(this).attr("data-status");
				$(".load_bao_cao_nhiem_vu").load("<?php echo ROOTHOST; ?>ajaxs/report_khung_nv/load_list_by_mon.php",{member,get_grade,first_subject,status},function(data){
				});
				return false;
			});


		});
</script>
