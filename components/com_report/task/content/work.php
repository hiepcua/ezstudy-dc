<?php 

$subject_list=isset($_CHILD_INFO['subject_list'])?$_CHILD_INFO['subject_list']:"";
$get_grade=isset($_CHILD_INFO['grade'])?$_CHILD_INFO['grade']:"";
$member=isset($_CHILD_INFO['username'])?$_CHILD_INFO['username']:"";

$arr_subject_list=array();
if($subject_list!=''){
	$arr_subject_list=explode(",", $subject_list);
	$first_item=isset($arr_subject_list[0])?$arr_subject_list[0]:"";
	$arr_first_subject=explode("_", $first_item);
	$first_subject=isset($arr_first_subject[1])?$arr_first_subject[1]:"";


	

?>
<style type="text/css">
		.ul_list, .ul_list_report {list-style: none;padding: 0px;}
		.box_report .ul_list li {display: inline-block;margin-right: 10px; }
		.ul_list_report li {
			margin-bottom: 10px;
			width: 50%;
			float: left;
		}
		.ul_list_report .fa_user {
			float: left;
			font-size: 44px;
			margin-right: 5px;
			color: #90cef7;
			margin-bottom: 5px;
		}
		.ul_list_report p {margin-bottom: 0px;}
</style>
<div class="load_bao_cao_nhiem_vu"></div>
<script type="text/javascript">
	var member="<?php echo $member; ?>";
	var get_grade="<?php echo $get_grade; ?>";
	var first_subject="<?php echo $first_subject; ?>";

	$(document).ready(function(){
		
		$(".load_bao_cao_nhiem_vu").load("<?php echo ROOTHOST; ?>ajaxs/report_khung_nv/load_list_by_mon.php",{member,get_grade,first_subject},function(data){
			console.log(data);
		});

		$("#sl_mon").change(function(){
			var first_subject=$(this).val();
			$(".load_bao_cao_nhiem_vu").load("<?php echo ROOTHOST; ?>ajaxs/report_khung_nv/load_list_by_mon.php",{member,get_grade,first_subject},function(data){
			});
		})
	});
</script>


<?php }//end if ?>