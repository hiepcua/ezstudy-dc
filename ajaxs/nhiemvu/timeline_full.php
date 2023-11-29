<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path."config_api.php");

$today = date("w") +1;
$time_mon = array(
	"M01"=>array(
		array(
			"0"=>"Bài 1: Sự đồng biến nghịch biến của hàm số",
			"1"=>"<div>Xét sự đồng biến nghịch biến của hàm số</div>
				<div>Tìm khoảng cách đồng biến nghịch biến của các hàm số</div>"
		),
		"","",
		array(
			"0"=>"Bài 2: Cực trị của hàm số",
			"1"=>"<div>Tìm cực trị của hàm số</div>
				<div>Tìm tham số m để hàm số đạt cực trị tại 1 điểm</div>"
		),
		"",""),
	"M02"=>array("",
		array(
			"0"=>"Nghị luận về một tư tưởng đạo lý",
			"1"=>"<div>Anh chị nghĩ thế nào về câu nói</div>
				<div>Khái niệm nghị luận</div>"
		),
		"","","",
		array(
			"0"=>"Khái quát văn học Việt Nam từ 1945",
			"1"=>"<div>Phân tích và chứng minh rằng</div>
				<div>Những đặc điểm cơ bản</div>"
		)
	),
	"M03"=>array("",
		array(
			"0"=>"Nghị luận về một tư tưởng đạo lý",
			"1"=>"<div>Anh chị nghĩ thế nào về câu nói</div>
				<div>Khái niệm nghị luận</div>"
		),
		"","","",
		array(
			"0"=>"Khái quát văn học Việt Nam từ 1945",
			"1"=>"<div>Phân tích và chứng minh rằng</div>
				<div>Những đặc điểm cơ bản</div>"
		)
	),
	"M04"=>array("","",
		array(
			"0"=>"Dao động điều hòa",
			"1"=>"<div>Một dao động điều hòa trên đường thẳng dài 4cm</div>
				<div>Phương trình của một vật dao động điều hòa</div>"
		),
		"","",
		array(
			"0"=>"Con lắc lò xo",
			"1"=>"<div>Một con lắc lò xo dao động điều hòa</div>
				<div>Một con lắc lò xo gồm 1 vật có khối lượng</div>"
		)
	),
	"M05"=>array(
		array(
			"0"=>"Con lắc lò xo",
			"1"=>"<div>Một con lắc lò xo dao động điều hòa</div>
				<div>Một con lắc lò xo gồm 1 vật có khối lượng</div>"
		),
		"","",
		array(
			"0"=>"Con lắc lò xo",
			"1"=>"<div>Một con lắc lò xo dao động điều hòa</div>
				<div>Một con lắc lò xo gồm 1 vật có khối lượng</div>"
		),
		"",""),
	"M06"=>array("","","",
		array(
			"0"=>"Con lắc lò xo",
			"1"=>"<div>Một con lắc lò xo dao động điều hòa</div>
				<div>Một con lắc lò xo gồm 1 vật có khối lượng</div>"
		),
		"",""),
	"M07"=>array("","","","",
		array(
			"0"=>"Con lắc lò xo",
			"1"=>"<div>Một con lắc lò xo dao động điều hòa</div>
				<div>Một con lắc lò xo gồm 1 vật có khối lượng</div>"
		),
		"")	
);
$time_hoc = array("19:30 – 21:00","19:30 – 21:00","","19:30 – 21:00","","19:30 – 21:00");
?>
<link rel='stylesheet' href='<?php echo ROOTHOST;?>css/main.css'/>
<div class="card-block card-task"><div class="timeline text-center">
	<table class="table table-bordered table_fix">
		<thead class="table-header"><tr>
			<th width="8%"></th>
			<th width="8%"></th>
			<?php for($i=2;$i<8;$i++) { 
			$cls = ''; if($today == $i) $cls = 'active'; ?>
			<th width="14%" class="<?php echo $cls;?>">Thứ <?php echo $i;?></th>
			<?php } ?>
		</tr></thead>
		<tbody>
		<?php foreach($_Subjects as $k=>$v) { 
		$sub = $v['subject']; ?>
			<tr class="table-item">
				<td rowspan="2"><?php echo $v['subject_name'];?></td>
				<td class="text-left">Bài học</td>
				<?php for($i=2;$i<8;$i++) { 
				$cls = ''; if($today == $i) $cls = 'active'; ?>
				<td class="text-left <?php echo $cls;?>">
					<?php 
					if(isset($time_mon[$sub][$i-2][0])) {
						echo $time_mon[$sub][$i-2][0]; 
					} ?>
				</td>
				<?php } ?>
			</tr>
			<tr class="table-item">
				<td class="text-left">Nhiệm vụ</td>
				<?php for($i=2;$i<8;$i++) { 
				$cls = ''; if($today == $i) $cls = 'active'; ?>
				<td class="text-left <?php echo $cls;?>">
					<?php 
					if(isset($time_mon[$sub][$i-2][1])) {
						echo $time_mon[$sub][$i-2][1];
					} ?>
				</td>
				<?php } ?>
			</tr>
		<?php } ?>
		<tr class="table-item">
			<td colspan="2"><span class="label label-success">Lịch học gia sư</span></td>
			<?php for($i=2;$i<8;$i++) { 
			$cls = ''; if($today == $i) $cls = 'active';?>
			<td class="text-center <?php echo $cls;?>">
			  <?php 
			  if($time_hoc[$i-2] != "") {
				  echo $time_hoc[$i-2];
				  if($today == $i) echo "<div><a href='#' class='readmore'>Vào lớp học</a></div>";
			  } ?>
			</td>
			<?php } ?>
		</tr>
		</tbody>
	</table>
	<!--<div class="table-header">
		<div class="track"><div class="heading">#</div></div>
		<div class="track"><div class="heading">#</div></div>
		<?php for($i=2;$i<8;$i++) { 
		$cls = ''; if($today == $i) $cls = 'active';?>
		<div class="track">
		  <div class="heading <?php echo $cls;?>">Thứ <?php echo $i;?></div>
		</div>
		<?php } ?>
	</div>
	<div class="table-body scrollbar" id="scrollbar-1">
		<?php foreach($_Subjects as $k=>$v) { 
		$sub = $v['subject']; ?>
		<div class="table-item">
			<?php for($k=0;$k<2;$k++) { ?>
				<div class="entry text-left"><div class="done">
					<?php echo $v['subject_name'];?>
				</div></div>
				<div class="entry text-left"><div class="done">
					<?php if($k==0) echo "Bài học"; else echo "Nhiệm vụ";?>
				</div></div>
				<?php for($i=2;$i<8;$i++) { 
				$cls = ''; if($today == $i) $cls = 'active'; ?>
				<div class="entry text-left">
				  <div class="done <?php echo $cls;?>">
					<?php $arr = $time_mon[$sub][$k];
					echo "<div>".$time_mon[$sub][$k]."</div>"; ?>
				  </div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="table-item">
			<?php for($k=0;$k<2;$k++) { ?>
				<div class="entry text-left"><div class="done">
					<span class="label label-success">Lịch học gia sư</span>
				</div></div>
				<div class="entry text-left"><div class="done">
					<?php if($k==0) echo "Bài học"; else echo "Nhiệm vụ";?>
				</div></div>
				<?php for($i=2;$i<8;$i++) { 
				$cls = ''; if($today == $i) $cls = 'active';?>
				<div class="entry text-left">
				  <div class="done <?php echo $cls;?>">
				  <?php 
				  if($time_hoc[$k] != "") {
					  echo $time_hoc[$k];
					  if($today == $i) echo "<div><a href='#' class='readmore'>Vào lớp học</a></div>";
				  } ?>
				  </div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>-->
</div></div>