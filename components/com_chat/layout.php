<?php
defined("ISHOME") or die("Can not acess this page, please come back!");
define("COMS","chat");
define('THIS_COM_PATH',COM_PATH.'com_'.COMS.'/');

if(isLogin()) {
	$task=isset($_GET['task'])?$_GET['task']:'list';
	if(is_file(THIS_COM_PATH.'task/'.$task.'.php')){
		include_once(THIS_COM_PATH.'task/'.$task.'.php');
	}
}
unset($obj); unset($task);	unset($ids);
?>
<script>
$(".select-lop .btn_prev").click(function(){
	var grade = $(this).attr('dataid');
	var grade_version = '';
	var url = "<?php echo ROOTHOST;?>ajaxs/grade/process_change.php";
	$.post(url,{grade, grade_version}, function(req) {
		console.log(req);
		if(req == "success") {
			location.reload(); // Tải lại trang
		}
	})
})
$(".select-lop .btn_next").click(function(){
	var grade = $(this).attr('dataid');
	var grade_version = '';
	var url = "<?php echo ROOTHOST;?>ajaxs/grade/process_change.php";
	$.post(url,{grade, grade_version}, function(req) {
		console.log(req);
		if(req == "success") {
			location.reload(); // Tải lại trang
		}
	})
})
</script>