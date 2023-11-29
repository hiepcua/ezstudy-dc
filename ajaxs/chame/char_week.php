<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'Pusher.php');
require_once(libs_path.'cls.mysql.php');
function convert_to_munit($second){
	return round($second/60);
}
$username=isset($_POST['username']) ? addslashes($_POST['username']):'';
$arr_date=getDateReport(2); 
$first_date=isset($arr_date['first'])? $arr_date['first']:'';
$last_date=isset($arr_date['last'])? $arr_date['last']:'';
$last_date=strtotime('+1 day', $last_date);
$strwhere=" AND today > $first_date AND today<=$last_date";
$item = SysGetList("ez_member_time_visit",array(), " AND member='$username' $strwhere",false);
$arr_value=$arr=array();
while($r=$item->Fetch_Assoc()) { 
	$val=$r['day'];
	$arr_value[]=convert_to_munit($r['total_time']);
	$arr[$val]=$r;
}
if(count($arr_value)>0){
	$max_total=max($arr_value); 
	if($max_total<=30) $max_value=30;
	else if($max_total>30 && $max_total<=50) $max_value=50;
	else if($max_total>50 && $max_total<=100) $max_value=100;
	else if($max_total>100 && $max_total<=200) $max_value=200;
	else if($max_total>200 && $max_total<=300) $max_value=300;
	else $max_value=300;
	$max_value=$max_total+5;
	$step=ceil($max_total/10);
	
	$str='';
	for($i=2; $i<=8; $i++){
		if(isset($arr[$i])) $vl=convert_to_munit($arr[$i]['total_time']);
		else $vl=0;
		$str.=$vl.",";
	}
	$str=substr($str,0,-1);
	if($type_user=='chame') echo "<h4 class='label-txt'>Biểu đồ học tập của <b>".$this_user_cur."</b></h4>";
	?>
	<canvas id="lineChart_2" width="452" height="226" style="display: block; width: 452px; height: 226px;" class="chartjs-render-monitor"></canvas>
	<script src="<?php echo ROOTHOST_PATH;?>char/Chart.bundle.min.js"></script>
	  <script>
	(function($) {
	 let draw = Chart.controllers.line.__super__.draw; //draw shadow
	//gradient line chart
	if(jQuery('#lineChart_2').length > 0 ){
		const lineChart_2 = document.getElementById("lineChart_2").getContext('2d');
		const lineChart_2gradientStroke = lineChart_2.createLinearGradient(500, 0, 100, 0);
		lineChart_2gradientStroke.addColorStop(0, "rgba(102, 115, 253, 1)");
		lineChart_2gradientStroke.addColorStop(1, "rgba(102, 115, 253, 0.5)");

		Chart.controllers.line = Chart.controllers.line.extend({
			draw: function () {
				draw.apply(this, arguments);
				let nk = this.chart.chart.ctx;
				let _stroke = nk.stroke;
				nk.stroke = function () {
					nk.save();
					nk.shadowColor = 'rgba(0, 0, 128, .2)';
					nk.shadowBlur = 10;
					nk.shadowOffsetX = 0;
					nk.shadowOffsetY = 10;
					_stroke.apply(this, arguments)
					nk.restore();
				}
			}
		});
		var max_value=<?php echo $max_value;?>;
		lineChart_2.height = max_value;

		new Chart(lineChart_2, {
			type: 'line',
			data: {
				defaultFontFamily: 'Poppins',
				labels: ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật"],
				datasets: [
					{
						label: "My First dataset",
						data: [<?php echo $str;?>],
						borderColor: lineChart_2gradientStroke,
						borderWidth: "2",
						backgroundColor: 'transparent', 
						pointBackgroundColor: 'rgba(102, 115, 253, 0.5)'
					}
				]
			},
			options: {
				legend: false, 
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true, 
							max: max_value, 
							min: 0, 
							stepSize: <?php echo $step;?>, 
							padding: 10
						}
					}],
					xAxes: [{ 
						ticks: {
							padding: 5
						}
					}]
				}
			}
		});
	}

	})(jQuery);
</script>
<?php }
else {
	if($type_user=='chame') echo "<h4>Biểu đồ học tập của <b>".$this_user_cur."</b> đang được cập nhật. Bạn có thể chọn học sinh khác tại cột bên phải!</h4>";
	else echo '<h4>Biểu đồ đang được cập nhật!</h4>';
}
 ?>