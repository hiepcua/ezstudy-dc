<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'Pusher.php');
require_once(libs_path.'cls.mysql.php');

$username = isset($_POST['username']) ? addslashes($_POST['username']):'';
if($username!=""){
	function get_report_subject($mon, $_Nhiemvu){
		$num_nowork=0;
		$num_total=$num_work=0;
		if(isset($_Nhiemvu[$mon])){
			foreach($_Nhiemvu[$mon] as $key=>$item) { 
				$status  = $item['status'];
				if($status=='') $num_nowork++;
				if($status>=1) $num_work++;
				$num_total++;
			}
		}
		
		return array('num_nowork'=>$num_nowork,'num_work'=>$num_work,'num_total'=>$num_total);
	}

	$infoUser = api_get_member_info($username);
	$fullname = $infoUser['fullname'];
	$grade = $infoUser['grade'];
	$version = $infoUser['version'];
	$subject_list = $infoUser['subject_list'];
	$cur_packet = $infoUser['packet'];
	$arr_subject = $subject_list !=''? explode(',',$subject_list):array();
	if($cur_packet=="EZ2") $type_tbl=1;
	else $type_tbl=0;
	$_Nhiemvu = getDataNhiemVuMember($username,$grade,$version,$type_tbl);

	$str='';
	$str_mon='';
	$i=0;
	$first_mon='';
	$first_work=0;
	$_Subjects = api_get_subject($grade);
	if(count($_Nhiemvu)>0 && count($_Subjects)>0){
		?>
		<div class="card " style="margin-top: 25px;">
			<div class="row">
				<div class="col-md-6">
					<div class="char-work">
						<div id="chart-work1"></div>
					</div>

					<!-- <div class="mt-2 text-center">
						<button type="button" onclick="frm_packet()" class="btn btn-primary">Đăng ký dịch vụ</button>
					</div> -->
				</div>
				<div class="col-md-6">
					<div class="box-report-subject item-report-subject scrollbar-stype">
						<?php 
						$str='';
						$str_mon='';
						$i=0;
						$first_mon='';
						$first_work=0;

						foreach($_Subjects as $key=> $val) {
							if(in_array($key,$arr_subject)){
								$i++;
								$k=$val['subject'];
								$arr = get_report_subject($k,$_Nhiemvu);
								$num_work=$arr['num_work'];
								$num_nowork=$arr['num_nowork'];
								$num_total=$arr['num_total'];
								if($num_total==0) $value=0;
								else $value=ceil(($num_work/$num_total)*100);
								$mon=$val['subject_name'];
								$str.=$value.",";
								$str_mon.="'".$mon."',";
								?>
								<div class="box-report-item">
									<h4 class="name"><span class="ic ic<?php echo $i;?>"></span>Môn <?php echo $mon;?></h4>
									<div class="progress">
										<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $value;?>%" aria-valuenow="<?php echo $value;?>" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<ul class="list-inline">
										<li>Hoàn thành: <?php echo $num_work;?></li>
										<li>Tổng số: <?php echo $num_total;?></li>
									</ul>
								</div>
								<?php 

							}
						}
						$str=substr($str,0,-1);
						$str_mon=substr($str_mon,0,-1);
						?>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}else echo "<h4 class='label-txt'>Biểu đồ học tập của <b>".$fullname."</b></h4>";
	?>
	<script>
		var options = {
			series: [<?php echo $str;?>],
			chart: {
				height: 260,
				type: 'radialBar',
			},
			plotOptions: {
				radialBar: {
					dataLabels: {
						name: {
							fontSize: '22px',
						},
						value: {
							fontSize: '16px',
						},
						total: {
							show: true,
							label: 'Total',
							formatter: function (w) {
								return <?php echo $i;?>
							}
						}
					}
				}
			},
			labels: [<?php echo $str_mon;?>],
		};
	</script>
<?php } else{echo '<h4>Biểu đồ học tập đang được cập nhật!</h4>';}?>
