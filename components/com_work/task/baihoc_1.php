<?php 
$lession_id = isset($_GET['id']) ? antiData($_GET['id']) : '';
$arr  = explode("_",$lession_id);
$gsub = $arr[0]."_".$arr[1];
$unit_id = explode(".",$arr[2]);
$unit_id = $unit_id[0];
$grade_version = getInfo('grade_version');
if($lession_id == "") 
	die('<div class="container lession-page"><div class="card"><div class="card-block">Không tìm thấy dữ liệu bài học</div></div></div>');

// get dữ liệu môn học
$arr_sub = $_Subjects[$gsub];
$subject = $arr_sub['subject'];
$subject_icon = $arr_sub['subject_icon'];
$subject_name = $arr_sub['subject_name'];
$units = json_decode($arr_sub['units'],true); 
$arr_units = $units[$grade_version];

//------------------------ get bài học -----------------------------
$json = array();
$json['key']   = PIT_API_KEY;
$json['id']  = $lession_id;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_LESSION,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arrLesson = $rep['data'];
}
$row = $arrLesson[0];
//echo "<pre>"; var_dump($arrLesson); echo "<pre>"; 
?>
<div class="container lession-page lession-detail-page">
	<h1 class="main-title text-left">
		<i class="fa <?php echo $subject_icon;?>"></i> 
		Môn <?php echo $subject_name;?>
	</h1>
	<div class="card">
		<div class="card-block">
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
	</div>
	<div class="clearfix"></div>
</div>

<script>
var acc = document.getElementsByClassName("unit");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>