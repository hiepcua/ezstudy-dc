<?php 
if(isLogin()){
	global $_Grade;
	$this_grade = getInfo('grade');
	$this_packet = getInfo('packet');
	$group_id = isset($_GET['id']) ? antiData($_GET['id']) : '';
	$cboSubject= isset($_GET['subject']) ? antiData($_GET['subject']) : 'all';
	$_Subjects=api_get_subject();
	$subject_list	= getInfo('subject_list');
	if($cboSubject == 'N/a' || $cboSubject == '' || $cboSubject == 'all') {
		$arr_subject=explode(',',$subject_list);
		$str_subject_list='';
		foreach($arr_subject as $vl){
			$list=explode('_',$vl);
			$name=isset($list[1])? $list[1]:$list;
			$str_subject_list.="'".$name."',";
		}
		$str_subject_list=$str_subject_list!=''? substr($str_subject_list,0,-1):'';
	}else{
		$str_subject_list="'".end(explode('_',$cboSubject))."'";
	}

	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['group_id']   = $group_id;
	$json['grade_id']   = $this_grade;
	$json['subject_list']   = $str_subject_list;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LIST_EXAM,json_encode($post_data));

	if(is_array($rep['data'])) {
		$arrItem = $rep['data'];
		$info_group_exam = $rep['data_group_exam'];
		$name_exam=$info_group_exam[0]['name'];
		?>
		<div class=" lession-page">
		<h3 class="label-title exam-title text-center"><?php echo $name_exam; ?> (<?php echo $_Grade[$this_grade]; ?>)</h3>
		<!-- <h5 class="label-title text-center"></h5> -->
		
		<div class='text-center'>
			<form method="get" action='' id='form_search_exam'>
				<label>
					<strong>Chọn môn:</strong> 
					<select class='subject cbo_subject form-control' id='cbo_subject' name='subject'>
						<option value='all'>Tất cả</option>
						<?php 
						$_Subjects = api_get_subject($this_grade);
						foreach($_Subjects as $k=>$v) {
							$select =$k==$cboSubject?"selected=true":"";
							echo "<option value='$k' $select>".$v['subject_name']."</option>";
						}
						?>
					</select>
				</label>
			</form>
			<script>
				$('#cbo_subject').change(function(){
					$('#form_search_exam').submit();
				});
			</script>
		</div>
		<?php
		echo '<div class="row">';
		echo '<div class="col-md-2"></div>';
		echo '<div class="col-md-8">';
		if(isset($arrItem[$group_id]) && count($arrItem)>0){
			echo "<div>Tổng số ".count($arrItem[$group_id])." đề thi.</div>";
			$i=0;
			foreach($arrItem[$group_id] as $r){
				$i++;
				$title=$r['title'];
				$grade=$r['grade'];
				$id_mon=$grade."_".$r['subject'];
				$mon=isset($_Subjects[$id_mon]) ? $_Subjects[$id_mon]['subject_name']:"";
				$url=ROOTHOST."tool-exam/".$r['id'];
				if($this_packet=="EZ1" || $this_packet=="EZ2"){
					?>
					<a class="item-group-exam group-exam-detail" href="<?php echo $url;?>" target="_brank">
						<span class="ic bg-gradient-<?php echo $i;?>"><i class="feather-align-right"></i></span>
						<?php echo $title;?>
						<ul class="list-inline">
							<li>Môn: <?php echo $mon;?></li>
						</ul>
						<button class="btn btn-success">Bắt đầu <i class="fa fa-arrow-right"></i></button>
					</a>
				<?php }
				else{ ?>
					<a class="item-group-exam group-exam-detail" href="javascript:void(0)" onclick="show_upgrade_notify_popup()">
						<span class="ic bg-gradient-<?php echo $i;?>"><i class="feather-align-right"></i></span>
						<?php echo $title;?>
						<ul class="list-inline">
							<li>Môn: <?php echo $mon;?></li>
						</ul>
						<button class="btn btn-success">Bắt đầu</button>
					</a>
					<?php 
				}
			}
		}else{
			echo "<div class='text-center' style='padding:21px 11px; border-radius:4px; border:#ffc107 1px solid; background:#ffedb6; color:#d00;'>Không có đề nào!</a>";
		}
		echo '<div class="clearfix"></div></div>
		</div>';
		echo '<div class="col-md-2"></div></div>';
	} 
} 

?>


