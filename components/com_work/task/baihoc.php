<?php 
$grade_version = getInfo('grade_version');
$this_grade    = getInfo('grade');

$lession_id = isset($_GET['id']) ? antiData($_GET['id']) : '';
$arr  = explode("_",$lession_id);
$gsub = $arr[0]."_".$arr[1];
$arr_id  = explode(".",$arr[2]);
$unit_id = $arr_id[0];
$this_subject = $arr[1];

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

//------------------------ get bài học -----------------------------
$json = array();
$json['key']   = PIT_API_KEY;
$json['id']  = $lession_id;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_LESSION_DETAIL,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arrLesson = $rep['data'];
}
$row = isset($arrLesson[0]) ? $arrLesson[0] : array();

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
//echo "<pre>"; var_dump($arrLesson); echo "</pre>";
?>
<div class="container lession-page">
	<div class="row">
		<div class="col-md-4 col-xs-12 lession-menu non-printable">
			<div class="card select-lop"><div class="card-block">
				<?php 
				$lop = (int)str_replace("K","",$this_grade);
				$prev_grade = ($lop-1)>=10 ? "K".($lop-1) : "K0".($lop-1);
				$next_grade = ($lop+1)>=10 ? "K".($lop+1) : "K0".($lop+1);
				if($lop > 1) { ?>
				<i class="fa fa-chevron-circle-left btn_prev" dataid="<?php echo $prev_grade;?>"></i>
				<?php } else echo '<i class="fa fa-chevron-circle-left icon_disable"></i>';?>
				<div class="change-lop" dataid="<?php echo $this_grade;?>"><?php echo $_Grade[$this_grade];?></div>
				<?php if($lop < 12) { ?>
				<i class="fa fa-chevron-circle-right btn_next" dataid="<?php echo $next_grade;?>"></i>
				<?php } else echo '<i class="fa fa-chevron-circle-right icon_disable"></i>';?>
				<input type="hidden" id="grade_version" value="<?php echo $grade_version;?>"/>
			</div></div>
			<div class="card"><div class="card-block units-list">
				<a href="<?php echo ROOTHOST;?>lession/<?php echo $this_grade.'_'.$subject;?>" class="btn btn-default btn_back">
				<i class='fa fa-arrow-left'></i> Quay lại danh mục</a>
				<div class="unit unit_title active">
					<a href="javascript:void(0)"><?php echo $arr_units["$unit_id"]['title'];?></a>
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
			<?php include_once("menu_monhoc.php"); ?>
		</div>
		<div class="col-md-8 col-xs-12 lession-main">
			<h1 class="main-title text-left non-printable">
				<i class="fa <?php echo $subject_icon;?>"></i> 
				Môn <?php echo $subject_name;?>
			</h1>
			<div class="card">
				<div class="card-block ">
					<div class="tabs_menu justify-content-center non-printable">
						<a href="javascript:void(0)" class="btn active">Lý thuyết</a>
						<a href="javascript:void(0)" class="btn">Bài tập</a>
						<a href="javascript:void(0)" class="btn">Luyện tập</a>
					</div>
					<div class="clearfix non-printable"><hr/></div>
					<?php include("navigation.php");?>
					<div class="clearfix non-printable"><hr/></div>
					<div class="lession-detail">
						<h2 class="title"><?php echo stripslashes($row['title']);?></h2>
						<div class="intro"><?php if($row['intro']!="") {
							echo "<h3>Mô tả:</h3>";
							echo stripslashes($row['intro']);
						} ?></div>
						<div class="intro"><?php if($row['guide']!="") {
							echo "<h3>Hướng dẫn học:</h3>";
							echo stripslashes($row['guide']);
						} ?></div>
					</div>
					<div class="clearfix"><hr/></div>
					<?php include("navigation.php");?>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<input type="hidden" id="cur_grade" value="<?php echo $this_grade;?>"/>
		<input type="hidden" id="cur_subject" value="<?php echo $subject;?>"/>
	</div>
</div>