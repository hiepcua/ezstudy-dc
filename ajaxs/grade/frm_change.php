<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');

//------------------------ GET VERSION MÔN HỌC -----------------------------
$_Grade_version = array();
$json = array();
$json['key']   = PIT_API_KEY;
$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
$rep = Curl_Post(API_GRADE_VERSION,json_encode($post_data));
if(is_array($rep['data']) && count($rep['data']) > 0) {
	// Chỉ lấy ra khối lớp có nhiều bộ sách. Nếu có 1 bộ thì mặc định chọn bộ đó
	foreach($rep['data'] as $k=>$v) {
		$_Grade_version[$v['grade']][] = $v;
	}
}
//echo "<pre>"; var_dump($_Grade_version); echo "</pre>";

$cur_grade = isset($_GET['grade']) ? antiData($_GET['grade']) : '';
$cur_version = getInfo('grade_version');
echo "<div class='frm_change_grade'>";
	echo "<div class='form-group grade_msg'></div>";
	echo "<div class='form-group'>";
	foreach($_Grade as $k=>$v) { 
		$cls = '';
		if($k == $cur_grade) $cls = 'btn-primary';
		echo "<div class='item btn btn-default $cls' dataid='$k'>$v</div>";
	}
	echo "</div>"; 
	echo "<div class='form-group choose_version'>
			<label>Vui lòng chọn bộ sách</label>
			<div class='grade_version'>";
			if(is_array($_Grade_version) && count($_Grade_version)>0) {
				foreach($_Grade_version as $k=>$v) {
					if(count($v) == 1) continue;
					foreach($v as $r) {
						echo "<div class='name' grade='".$r['grade']."'dataid='".$r['id']."'>
								<a href='javascript:void(0)' class='item_link' dataid='".$r['id']."' dataname='".$r['title']."'>
								<i class='fa fa-3x fa-book'></i> <div>".$r['title']."</div>
								<div class='icon'></div>
							</a></div>";
					}
				}
			}
		echo "</div>
		</div>";
echo "</div>"; 
echo "<input type='hidden' id='cur_grade' value='$cur_grade'/>";
echo "<input type='hidden' id='cur_version' value='$cur_version'/>";
echo "<input type='hidden' id='grade_change' value=''/>";
echo "<input type='hidden' id='grade_version' value=''/>";
echo "<hr><div class='text-center'><button type='button' class='btn btn-info btn_change_grade'> <i class='fa fa-exchange'></i> Đổi lớp </button></div>";
?>
<script>
	$(".frm_change_grade .grade_msg").hide();
	$(".frm_change_grade .choose_version").hide();
	
	$(".frm_change_grade .item").click(function(){
		var grade = $(this).attr('dataid');
		$(".frm_change_grade .item").removeClass('btn-primary');
		$(this).addClass('btn-primary');
		$("#grade_change").val(grade);
		if(grade != "") {
			var dem = 0; var flag = false;
			$(".grade_version .name").each(function(){
				if($(this).attr('grade') == grade) {
					var ver = $(this).attr('dataid');
					$(this).show(); dem ++;
					if(flag == false) {
						$(this).addClass('active');
						$(this).find('.icon').html('<i class="fa fa-check-circle"></i>');
						$("#grade_version").val(ver);
						flag = true;
					}
				}else{
					$(this).hide();
				}
			})
			if(dem > 0)
				$(".frm_change_grade .choose_version").show();
			else {
				$("#grade_version").val('');
				$(".grade_version .name").removeClass('active');
				$(".grade_version .icon").html('');
				$(".frm_change_grade .choose_version").hide();
			}
		}
	})
	$(".grade_version .name").click(function(){
		$(".grade_version .name").removeClass('active');
		$(this).addClass('active');
		
		var ver = $(this).attr('dataid');
		$(".grade_version .icon").html('');
		$(this).find('.icon').html('<i class="fa fa-check-circle"></i>');
		$("#grade_version").val(ver);
	})
	$(".btn_change_grade").click(function(){
		var cur_grade = $("#cur_grade").val();
		var cur_version = $("#cur_version").val();
		var grade = $("#grade_change").val();
		var grade_version = $("#grade_version").val();
		if(grade == "") {
			$(".frm_change_grade .grade_msg").show();
			$(".frm_change_grade .grade_msg").html("Vui lòng chọn lớp cần đổi");
			return false;
		} else if(grade == cur_grade && cur_version == grade_version) {
			$(".frm_change_grade .grade_msg").show();
			$(".frm_change_grade .grade_msg").html("Vui lòng chọn bộ sách khác");
			return false;
		} else 
			$(".frm_change_grade .grade_msg").hide();	
		
		var url = "<?php echo ROOTHOST;?>ajaxs/grade/process_change.php";
		$.post(url,{grade, grade_version}, function(req) {
			console.log(req);
			if(req == "success") {
				parent.location.reload(); // Tải lại trang chính
			}
		})
	})
</script>