<?php 
global $lession_id;
$username=getInfo('username');
$arr_date=getDateReport(1); 
$first_date=isset($arr_date['first'])? $arr_date['first']:'';
$last_date=isset($arr_date['last'])? $arr_date['last']:'';
$strwhere=" AND cdate > $first_date AND cdate<=$last_date AND bonus_configid='5'";
$count = SysCount("ez_wallet_histories"," AND username='$username' $strwhere");
if($count>0 )  AddBonus($username, 2, 3);
else  AddBonus($username, 1, 3);
$exercise_id=isset($_GET['exercise_id'])? addslashes($_GET['exercise_id']):'';
$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = $this_grade;
	$json['subject'] = $subject;
	$json['lesson'] = $lession_id;
	$json['unit']  = $unit_id;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION_EXERCISE,json_encode($post_data));
	
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$arrExercise = $rep['data'];
		
		if($exercise_id==''){
			$stt=0;
			foreach($arrExercise as $a=>$item) {
				$stt++;
				$title=stripslashes($item['content']);
				$guide=stripslashes($item['guide']);
				$id=$item['id'];
				$cls = ''; 
				?>
				<div class="item-exercise" data-id="<?php echo $id;?>">
					<div><span class="cau">Câu <?php echo $stt;?>:</span> <?php echo $title;?></div>
					<p class="box-guide">
					  <a class="btn btn-primary collapsed" data-toggle="collapse" href="#collapseExample<?php echo $stt;?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $stt;?>">
						Xem đáp án	<i class="fa fa-sort-desc" aria-hidden="true"></i> <i class="fa fa-sort-asc" aria-hidden="true"></i>
					  </a>
					</p>
					<div class="collapse" id="collapseExample<?php echo $stt;?>">
					  <div class="card card-body">
						<?php echo $guide;?>
					  </div>
					</div>
				</div>
				<?php 
			} 
		}
		else{
			if(isset($arrExercise[$exercise_id]['content'])){
				echo $arrExercise[$exercise_id]['content'];
			}
			
		}
	} 
?>