<hr>
<div class="card" style="margin-top: 25px;">
	<h4 class="name name-report">Tiến độ hoàn thành</h4>
	<div class="row">
		<div class="col-sm-6 col-md-12">
			<div id="chart-work"></div>
		</div>
		<div class="col-sm-6 col-md-12">
			<div class="box-report-subject">
				<?php 
				global $strwhere_nv;
				global $type_tbl;
				global $arr_subject;
				global $_Nhiemvu;

				function get_report_subject($mon,$_Nhiemvu){
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
				$str='';
				$str_mon='';
				$i=0;
				$first_mon='';
				$first_work=0;
				$this_grade = getInfo('grade');
				$_Subjects = api_get_subject($this_grade);

				foreach($_Subjects as $key=> $val) {
					if(in_array($key,$arr_subject)){
						$i++;
						$k=$val['subject'];
						$arr=get_report_subject($k,$_Nhiemvu);
						$num_work=$arr['num_work'];
						$num_nowork=$arr['num_nowork'];
						$num_total=$arr['num_total'];
						if($num_total==0) $value=0;
						else $value=ceil(($num_work/$num_total)*100);
						$mon=$val['subject_name'];

						//if($num_work>0){
						$str.=$value.",";
						$str_mon.="'".$mon."',";
						//}
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
<?php if($str!=''){?>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
								// By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
								return <?php echo $i;?>
							}
						}
					}
				}
			},
			labels: [<?php echo $str_mon;?>],
		};

		var chart = new ApexCharts(document.querySelector("#chart-work"), options);
		chart.render();
	</script>
	<?php } ?>