<div class="lession-page">
	<div class="row">
	<div class="col-md-9">
	<div class="card">
		<?php 
		if(isLogin()){
			$type_user = getInfo('utype');
			$username = getInfo('username');
			$subject_list = getInfo('subject_list');
			if($subject_list == 'N/a' || $subject_list == '') 
				$arr_subject = array();
			else 
				$arr_subject = explode(',',$subject_list);

			if($type_user=='chame'){
				$item = $_CHILD_INFO;
				$this_grade = isset($item['grade']) ? $item['grade']:'';
				$this_version = isset($item['grade_version']) ? $item['grade_version']:'';
				$this_fullname = $item['fullname'];	
			}else{
				$this_version 	= getInfo('grade_version');
				$this_grade 	= getInfo('grade');	
				$this_fullname 	= getInfo('fullname');	
			}				
			$_Subjects = api_get_subject($this_grade);
			?>
			<div class="card-block " style="margin-bottom: 15px; color:#232878;">
				<h2 class="page-title">Hi <b><?php echo getInfo("fullname");?></b>,</h2>
				<?php if($type_user=='chame'){
					$arr = api_get_child_member($username);
					echo '<ul>';
					echo '<li>Chọn một môn học để học cùng <strong class="name">'.$this_fullname.'<strong></li>';				
					echo '</ul>';
				} else{
					echo '<p class="label-title">Chọn một môn bạn muốn học hôm nay</p>';
				}?>
			</div>

			<?php 
			if($this_grade!=''){?>
				<div class="card-subject">
					<div class="">
						<?php $i=0;
						foreach($_Subjects as $k=>$v) {
							$i++; 
							?>
							<div class="item-box-subject">
								<a href="<?php echo ROOTHOST;?>lession/<?php echo $k;?>" class="item item-subject subject<?php echo $i;?>">
									<span class="icon <?php if(isset($v['subject_icon'])) echo $v['subject_icon'];?>"></span>
									<h4 class="name"><?php if(isset($v['subject_name'])) echo $v['subject_name'];?></h4>
								</a>
							</div>
						<?php } ?>
					</div>
				</div>

			<?php }?>	
		<?php }?>	
	</div>
	</div>
	<div class="col-md-3 bg-white">
		<?php 
		if($type_user == "hocsinh" OR isset($_SESSION['USER_JOININ'])){
		include_once(COM_PATH."com_frontpage/hocsinh/canhan.php"); 
		}
			?>
	</div>
	</div>
</div>
<script>
	$('#sl_grade').change(function(){
		$('#frm_search').submit();
	});
</script>