<?php 
$sl_user_child=isset($_SESSION['sl_user_child'])? $_SESSION['sl_user_child']:'';// phụ huynh select xem bài
$video_id = isset($_GET['video_id']) ? antiData($_GET['video_id']) : '';
if($sl_user_child!=''){
	$item=SysGetList('ez_member', array(), " AND username='$sl_user_child'");
	$this_grade=isset($item[0]['grade']) ? $item[0]['grade']:'';
	$grade_version=isset($item[0]['grade_version']) ? $item[0]['grade_version']:'';
}
else {
	$this_grade    = getInfo('grade');
	$grade_version = getInfo('grade_version');
}

$_Subjects = api_get_subject($this_grade);
$lession_id = isset($_GET['id']) ? antiData($_GET['id']) : '';
$type = isset($_GET['type']) ? antiData($_GET['type']) : '';

//------------------------ get bài học -----------------------------
$lessonDetail = array();
$json = array();
$json['key'] = PIT_API_KEY;
$json['id'] = $lession_id;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_LESSION_DETAIL,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$lessonDetail = $rep['data'];
}

$row = isset($lessonDetail[0]) ? $lessonDetail[0] : array();
$intro = $row['intro'];
$guide = $row['guide'];
$gsub = $row['grade']."_".$row['subject'];
$unit_id = $row['unit'];
$this_subject = $row['subject'];

// chuyển đổi link khi chuyển lớp
if($gsub != $this_grade."_".$this_subject) { ?>
	<script> window.location ='<?php echo ROOTHOST;?>lession/<?php echo $this_grade."_".$this_subject;?>'</script>
<?php }

if($lession_id == "") 
	die('<div class="container lession-page"><div class="card"><div class="card-block">Không tìm thấy dữ liệu bài học</div></div></div>');


// get dữ liệu môn học
$arr_sub = $_Subjects[$gsub];
$subject = $arr_sub['subject'];
$subject_icon = $arr_sub['subject_icon'];
$subject_name = $arr_sub['subject_name'];
$units = json_decode($arr_sub['units'],true);

if(!isset($units[$grade_version]))  
	die('<div class="container lession-page"><div class="card"><div class="card-block">Bộ sách đang xây dựng</div></div></div>');

$arr_units = $units[$grade_version]; 
$unit_title_index = array_search($unit_id, array_column($arr_units, 'id'));
$unit_title = $arr_units[$unit_title_index]['title'];

//------------------------ get bài học -----------------------------
$arrLesson = array();
$json = array();
$json['key']   = PIT_API_KEY;
$json['grade'] = $this_grade;
$json['subject'] = $subject;
$json['unit']  = $unit_id;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_LESSION,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arrLesson = $rep['data'];
} 
// echo "<pre>"; var_dump($arrLesson); echo "</pre>"; die();
//SysCount('ez_grade_subjects_video'," AND lesson='lession_id'");
$count_video=0;
?>
<div class="container-fluid">
	<div class=" lession-page">
		<div class="">
			<?php 
			$lop = (int)str_replace("K","",$this_grade);
			$prev_grade = ($lop-1)>=10 ? "K".($lop-1) : "K0".($lop-1);
			$next_grade = ($lop+1)>=10 ? "K".($lop+1) : "K0".($lop+1);
			?>
			<div class="lession-menu non-printable">
				<div class="card"><div class="card-block units-list">
					<a href="<?php echo ROOTHOST;?>lession/<?php echo $this_grade.'_'.$subject;?>" class="btn btn-default btn_back">
						<i class='fa fa-arrow-left'></i> Quay lại danh mục</a>
						<div class="unit unit_title active">
							<a href="javascript:void(0)"><?php echo $unit_title;?></a>
						</div>
						<ul class="lession_list">
							<?php 
							$next_page = $prev_page = '';
							foreach($arrLesson as $a=>$b) {
								$cls = ''; 
								if($b['id'] == $lession_id) {
									$cls = 'active';
									$prev_page = isset($arrLesson[$a-1]) ? $arrLesson[$a-1]['id'] : $arrLesson[$a]['id'];
									$next_page = isset($arrLesson[$a+1]) ? $arrLesson[$a+1]['id'] : $arrLesson[$a]['id'];
								}
								$link = ROOTHOST.'lession-detail/'.$b['id'];
								echo "<li dataid='".$b['id']."' class='$cls'><a href='$link'>".$b['title']."</a></li>";
							} ?>
						</ul>
					</div></div>
				</div>

				<div class="lession-main">
					<div class="main-title text-left non-printable">
						<h1>
							<i class="fa <?php echo $subject_icon;?>"></i> 
							Môn <?php echo $subject_name;?>
						</h1>
						<a class="hide-on-mobile print" href="javascript:printPage();"><i class="fa fa-print"></i> In trang</a>
					</div>
					<div class="">
						<div>
							<div class="clearfix non-printable"><hr/></div>
							<?php 
							$title_label=$row['title'];
							include("navigation.php");
							?>
							<div class="clearfix non-printable"><hr/></div>
							<div class="tabs_menu justify-content-center non-printable">
								<a href="<?php echo ROOTHOST."lession-detail/".$lession_id;?>" class="btn <?php if($type=='') echo 'active';?>">
									
									Lý thuyết</a>
								<a href="<?php echo ROOTHOST."lession-exercise/".$lession_id;?>" class="btn <?php if($type==2) echo 'active';?>">
									
								Bài tập
							</a>
								<?php if($count_video>0){?><a href="<?php echo ROOTHOST."lession-video/".$lession_id;?>" class="btn <?php if($type==4) echo 'active';?>">

									Video
								</a>
								<?php }?>
							</div>
							<div class="lession-detail">
								<?php if($type==''){ include_once('content/list_baihoc.php');
							}
							else if($type=='2') include_once('content/list_baitap.php');

							else{
								if($video_id=='') include_once('content/list_video.php');
								else include_once('content/video_detail.php');
							}?>
							<div class="clearfix"><hr/>
							</div>
						</div>
						<?php $title_label='';
						include("navigation.php");?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<input type="hidden" id="cur_grade" value="<?php echo $this_grade;?>"/>
			<input type="hidden" id="cur_subject" value="<?php echo $subject;?>"/>
		</div>
	</div>
</div>
