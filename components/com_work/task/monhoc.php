<?php
$error_smg = '';
$grade_version = getInfo('grade_version');
$this_grade    = getInfo('grade');
$username      = getInfo('username');
 
$gsub = isset($_GET['subject']) ? antiData($_GET['subject']) : '';
$arr = explode("_",$gsub);
$this_subject = $arr[1];

// chuyển đổi link khi chuyển lớp
if($gsub != $this_grade."_".$this_subject) { ?>
	<script> window.location ='<?php echo ROOTHOST;?>lession/<?php echo $this_grade."_".$this_subject;?>'</script>
<?php }

// Nếu lớp # lớp 1 & lớp 6 thì mặc định bộ sách cũ
if(!in_array($this_grade,array("K01","K06")) && ($grade_version == "" || $grade_version == "N/a") ) {
	//update grade version cho học sinh
	$arr = array();
	$arr['grade_version'] = $this_grade."_V01";
	$result1 = SysEdit("ez_member",$arr," username='$username'");
	setInfo('grade_version',$this_grade."_V01");
} 

if($gsub == "") 
	$error_smg = '<div class="alert alert-danger">Không tìm thấy dữ liệu chương trình học</div>';

// get dữ liệu môn học
$arr_sub = isset($_Subjects[$gsub]) ? $_Subjects[$gsub] : array();
$subject = isset($arr_sub['subject']) ? $arr_sub['subject'] : '';
$subject_icon = isset($arr_sub['subject_icon']) ? $arr_sub['subject_icon'] : '';
$subject_name = isset($arr_sub['subject_name']) ? $arr_sub['subject_name'] : '';
$units = isset($arr_sub['units']) ? json_decode($arr_sub['units'],true) : array();
 
if(!isset($units[$grade_version]))  
	$error_smg = '<div class="alert alert-danger">Không tìm thấy dữ liệu</div>';

$arr_units = isset($units[$grade_version]) ? $units[$grade_version] : array();

//echo "<pre>"; var_dump($arr_units); echo "<pre>"; 
?>
<div class="container lession-page">
	<div class="row">
		<div class="col-md-4 col-xs-12 lession-menu">
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
				<?php 
				$unit_active = $unit_title = '';
				if(count($arr_units) == 0) echo "<div class='alert alert-danger'>Không có dữ liệu</div>";
				else {
					$i = 0;
					foreach($arr_units as $k=>$v) { 
					$unit_id = $v['id']; 
					$cls = ''; 
					if($i==0) {
						$cls = 'active';
						$unit_active = $unit_id;
						$unit_title  = $v['title'];
					}
					$i++;?>
					<div class="unit <?php echo $cls;?>" dataid="<?php echo $unit_id;?>" datatitle="<?php echo $v['title'];?>">
						<a href="javascript:void(0)"><?php echo $v['title'];?></a>
					</div>
					<?php } // end foreach 
				} // end if ?>
			</div></div>
			<?php include_once("menu_monhoc.php"); ?>
		</div>
		<?php 
		//------------------------ get bài học -----------------------------
		$arrLesson = array();
		$json = array();
		$json['key']   = PIT_API_KEY;
		$json['grade'] = getInfo('grade');
		$json['subject'] = $subject;
		$json['version'] = $grade_version;
		$json['unit']    = $unit_active;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_LESSION,json_encode($post_data));
		if(is_array($rep['data']) && count($rep['data']) > 0) {
			$arrLesson = $rep['data'];
		}
		//echo "<pre>"; var_dump($post_data); echo "<pre>"; 
		?>
		<div class="col-md-8 col-xs-12 lession-main">
			<h1 class="main-title text-left">
				<i class="fa <?php echo $subject_icon;?>"></i> 
				Môn <?php echo $subject_name;?>
			</h1>
			<div class="card">
				<div class="card-block"> 
					<?php 
					if($error_smg != "" ) echo $error_smg;
					else { ?>
						<h2 class="title"><?php echo $unit_title;?></h2>
						<ul class="lession_list">
						<?php 
						if(is_array($arrLesson) && count($arrLesson)>0) {
							foreach($arrLesson as $a=>$b) { 
								$link = ROOTHOST."lession-detail/".$b['id'];
								echo "<li>
									<div class='col-md-8 col-xs-12 title'><a href='".$link."'>".$b['title']."</a></div>
									<div class='col-md-4 col-xs-12 func'>
										<a href='".$link."' class='btn'>Lý thuyết</a>
										<a href='#' class='btn'>Bài tập</a>
										<a href='#' class='btn'>Luyện tập</a>
									</div>
									</li>";
							} 
						}?>
						</ul>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<input type="hidden" id="cur_grade" value="<?php echo $this_grade;?>"/>
		<input type="hidden" id="cur_subject" value="<?php echo $subject;?>"/>
	</div>
</div>

<script>
$(".lession-menu .unit").click(function(){
	showLoading();
	var unit_id 	= $(this).attr('dataid');
	var unit_title 	= $(this).attr('datatitle');
	var cur_grade 	= $("#cur_grade").val();
	var cur_subject = $("#cur_subject").val();
	
	// change active
	$(".lession-menu .unit").removeClass('active');
	$(this).addClass('active');
	
	$(".lession-main .card-block").html("<div class='fa fa-spin'></div>");
	var url = "<?php echo ROOTHOST;?>ajaxs/lession/get_lession.php";
	$.post(url,{unit_id, unit_title, cur_grade, cur_subject},function(req) {
		hideLoading();
		console.log(req);
		$(".lession-main .card-block").html(req);
	})
})
</script>