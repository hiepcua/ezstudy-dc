<?php 
global $filter_username;
	$item_bonus = SysGetList("ez_bonus_config",array(), " AND type_nv='2'");
	$infoUser=getMember($filter_username);
	$grade 	= $infoUser['grade'];
	$version 	= $infoUser['version'];
	$subject_list= $infoUser['subject_list'];
	$arr_subject=$subject_list !=''? explode(',',$subject_list):array();
	$packet_service = api_get_member_service();
	if($packet_service==true) $type_tbl=1;
	else $type_tbl=0;
	$_Nhiemvu=getDataNhiemVuMember($filter_username,$grade,$version,$type_tbl);
	if(count($_Nhiemvu)>0){
		?>
		<table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width=40>STT</th>
                    <th class="text-left">Thông tin</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
		<?php 
		foreach($_Nhiemvu as $k=>$v) { 
		$id_mon=$this_grade."_".$k;
		if($type_tbl==1 && !in_array($id_mon,$arr_subject)) continue;
		$mon=isset($_Subjects[$id_mon]) ? $_Subjects[$id_mon]['subject_name']:"";
		$ic=isset($_Subjects[$id_mon]) ? $_Subjects[$id_mon]['subject_icon']:"";
			$arr_url=array();
				$i=0;
				foreach($v as $key=>$item) { 
					if($type_tbl==1) $contents=$item['contents'];
					else $contents=$item['content'];
					if($contents=='') continue;
					
					$i++;
					$name=$item['title']==''? 'Bài tập môn '.$mon:$item['title'];
					$number=$item['number_quiz'];
					$status=$item['status'];
					$lambai='Làm bài';
					
						$label="";
					
					if($type_tbl==1) $url=ROOTHOST."tool-work/".$item['id'];
					else $url=ROOTHOST."tool-work-config/".$item['id'];
					$arr_url[]=$url;
					if($cat=='live'){
						$contents=json_decode($item['contents'],true);
						$label_live='';
						if(count($contents)>0){
							foreach($contents as $vl){
								$url=$vl['link'];
								$name=$vl['title'];
								$label_live.='<a href="'.$url.'" class="btn btn-live text-danger alert-danger"><i class="ic feather-tv"></i> '.$name.'</a>';
							}
						}
						$url=$contents[0]['link'];
						$label=$label_live;
						$lambai='Đi tới';
						
					}
					?>
					<tr class="tr-item-nv <?php if($status==1) echo 'active';?>">
						<td><?php echo $i;?></td>
						<td class="text-left">
						<h4><?php echo $name;?></h4>
						<?php echo $label;?> <span class="btn btn-label">Môn <?php echo $mon;?></span>
						<?php if($number>0) echo '<span class="btn">Số câu '.$number.'</span>';?>
						</td>
						<td class="text-center">
						<?php 
						$class="btn-default";
						if($status==1) {
							$class="btn-primary";
							$label="Đã làm";
						}
						elseif($status==2) {
							$class="btn-danger";
							$label="Hoàn thành";
						}
						elseif($status==3) {
							$class="btn-success";
							$label="Hoàn thành xuất sắc";
						}
						else $label="Chưa làm";
						echo '<div class="box-btn-act">';
						echo '<button class="btn '.$class.'">'.$label.'</button>';
						echo '</div>';
						?>
						</td>
					</tr>

					
					<?php 
					
					//if($status==1) echo '<p class="notic">Bài đã làm nhưng chưa hoàn thành</p>';
					?>
						
				
				<?php } ?>
			</tbody>	
				</table>
		<?php } ?>
	<?php
	}
?>