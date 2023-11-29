<?php 
global $lession_id;
$username=getInfo('username');
$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = $this_grade;
	$json['subject'] = $subject;
	$json['lesson'] = $lession_id;
	$json['unit']  = $unit_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION_VIDEO,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		echo ' <div class="row row-item">';
		$arrExercise = $rep['data'];
		
		$stt=0;
		foreach($arrExercise as $a=>$row) {
			$stt++;
			$title=stripslashes($row['title']);
			$content=stripslashes($row['content']);
            $id=$row['id'];
			$url_detail=ROOTHOST.'lession-video/'.$lession_id."/".$id;
            $cdate=date('d-m-Y',$row['cdate']);
			$img=$row['thumb']!=''? URL_ROOTHOST_VIDEO.$row['thumb']:URL_ROOTHOST_VIDEO."images/default-video.jpg";
            $size=isset($row['size'])? " (".getStringSize($row['size']).")":'';
			?>
				 <div class="col-md-6 col-xs-4 col-item video-list">
					<div class="item" >
						<div  class="card course-card  p-0 shadow-xss border-0 rounded-lg overflow-hidden mr-1 mb-4">
							<a href="<?php echo $url_detail;?>" class="card-image w-100 mb-3">
								<span class="video-bttn position-relative d-block">
								<?php echo '<img src="'.$img.'" class="img-thumbvideo img-responsive">';?>
								
								</span>
							</a>
							<div class="card-body pt-0">
								<h4 class="name fw-700 font-xss mt-3 lh-28 mt-0"><a href="<?php echo $url_detail;?>" class="text-dark text-grey-900"><?php echo $row['title'];?></a></h4>
								<div class="text-center">
									<a href="<?php echo $url_detail;?>" class="btn btn-primary">Chi tiết</a>
								</div>
							</div>
						</div>
					</div>
					</div>
               
            <?php
		}
        echo '</div>';

	}
		

?>