<?php
session_start();
ini_set("display_errors",1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(!isLogin()) die("E01");
if(isset($_POST['subject'])) {
	$items=isset($_POST['items'])? $_POST['items']:array();
	$type=isset($_POST['type'])? (int)$_POST['type']:1;
	$bonus_configid=isset($_POST['bonus_configid'])? (int)$_POST['bonus_configid']:0;
	$type_lesson=0;
	if(!is_array($items)){
		$type_lesson=1;
		$items=array($items);
	}
	$subject=json_decode($_POST['subject'],true);
	$units=$subject['units'];
	
	
	if(count($items)>0){
	echo '<table class="table tbl-main table-bordered">';
	if($type_lesson==1) echo '<label>Bước 2: Chọn bài học</label>';
	else echo '<label>Bước 2: Chọn bài học tương ứng bài đang học trên lớp</label>';
	
	foreach($items as $val){
		$str=explode('_',$val);
		$subject_id=isset($str[1]) ? $str[1]:'';
		$title_subject=$subject[$val]['subject_name'];
		$arr_lesson=getLessonSubject($subject_id);
		?>
		<tr>
		<td class="td-title">Môn <?php echo $title_subject;?></td>
		<td>
		
			<select class="form-control" name="txt_lesson<?php echo $val;?>" id="txt_lesson">
			<option value="">Chọn bài</option>
			<?php 
			foreach($arr_lesson as $key =>$item){
				echo '<optgroup '.$key.' label="'.$item['title'].'">';
				foreach($item['data'] as $r_lesson){
					$selected = $r_lesson['id']==$row['lesson']?"selected":"";
					echo '<option value="'.$r_lesson['id'].'" '.$selected.'>&nbsp;&nbsp;&nbsp;'.$r_lesson['title'].'</option>';
				}
				echo '</optgroup>';
			}
			
			?>
			</select>
		</td>
	</tr>
	<?php 	
	
	}
	echo '</table>';
} 

if($type_lesson==1){?>
	<div class="form-group text-center nhiemvu_msg">
		<div class="clearfix" id="link_select"></div>
	</div>
	<script>
	$('#txt_lesson').change(function (e) {
		var type='<?php echo $type;?>';
		id=$(this).val();
		if(type==1) str='lession-exercise/'+id;
		else str='lession-detail/'+id;
		url='<?php echo ROOTHOST;?>'+str;
		var content='<a href="'+url+'"  class="view-all">Đi tới <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
		$("#link_select").html(content);
	});
	function process_nv(_this, type){
		var url=$(_this).attr('data-url');
		bonus_configid='<?php echo $bonus_configid;?>';
		$.post("<?php echo ROOTHOST;?>ajaxs/nhiemvu/proccess_muctieungay.php",{type,bonus_configid},function(req){
			console.log(req);
			if(req=='success') window.location.href=url;
			else{}
		});
	}

</script>
<?php 
}
}
?>
