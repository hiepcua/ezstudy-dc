<?php 
global $lession_id;
$username=getInfo('username');
$video_id = isset($_GET['video_id']) ? antiData($_GET['video_id']) : '';
$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = $this_grade;
	$json['subject'] = $subject;
	$json['lesson'] = $lession_id;
	$json['unit']  = $unit_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION_VIDEO,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		if(isset($rep['data'][$video_id])){
			$row=$rep['data'][$video_id];
			$title=stripslashes($row['title']);
			$content=stripslashes($row['content']);
            $id=$row['id'];
            $cdate=date('d-m-Y',$row['cdate']);
			$link = $row['link'];
            $size=isset($row['size'])? " (".getStringSize($row['size']).")":'';
			?>
			<video id="video" width="100%"  src="<?php echo URL_ROOTHOST_VIDEO.$link;?>" type="video/mp4" controls></video>
			<h4 class="name fw-700 font-xss mt-3 lh-28 mt-0"><span class="text-dark text-grey-900"><?php echo $row['title'];?></span></h4>
			<div class="text-content">
				<?php echo $content;?>
			</div>
						
               
            <?php
		}
        echo '</div>';

	}
		

?>