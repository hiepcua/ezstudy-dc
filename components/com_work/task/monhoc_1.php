<?php 
$gsub = isset($_GET['subject']) ? antiData($_GET['subject']) : '';
$grade_version = getInfo('grade_version');
if($gsub == "") 
	die('<div class="container lession-page"><div class="card"><div class="card-block">Không tìm thấy dữ liệu chương trình học</div></div></div>');

// get dữ liệu môn học
$arr_sub = $_Subjects[$gsub];
$subject = $arr_sub['subject'];
$subject_icon = $arr_sub['subject_icon'];
$subject_name = $arr_sub['subject_name'];
$units = json_decode($arr_sub['units'],true);
 
if(!isset($units[$grade_version]))  
	die('<div class="container lession-page"><div class="card"><div class="card-block">Bộ sách đang xây dựng</div></div></div>');

$arr_units = $units[$grade_version];

//echo "<pre>"; var_dump($arr_units); echo "<pre>"; 

//------------------------ get bài học -----------------------------
$json = array();
$json['key']   = PIT_API_KEY;
$json['grade'] = getInfo('grade');
$json['subject'] = $subject;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_LESSION,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	$arrLesson = $rep['data'];
}

// sắp xếp 
$sort_unit = array_column($arrLesson, 'unit');
array_multisort($sort_unit, SORT_ASC, $arrLesson);

//echo "<pre>"; var_dump($arrLesson); echo "<pre>"; 
?>
<div class="container lession-page">
	<h1 class="main-title text-left">
		<i class="fa <?php echo $subject_icon;?>"></i> 
		Môn <?php echo $subject_name;?>
	</h1>
	<div class="card">
		<div class="card-block">
			<div class="units-list">
				<?php 
				foreach($arr_units as $k=>$v) { 
				$unit_id = $v['id']; ?>
				<div class="unit">
					<a href="#"><?php echo $v['title'];?></a>
				</div>
				<div class="panel"><ul class="sub">
				<?php 
				foreach($arrLesson as $a=>$b) { 
					if($b['unit'] != $unit_id) continue; 
					echo "<li><a href='".ROOTHOST."lession-detail/".$b['id']."'>".$b['title']."</a></li>";
				} ?>
				</ul></div>
				<?php } // end foreach?>
			</div>
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