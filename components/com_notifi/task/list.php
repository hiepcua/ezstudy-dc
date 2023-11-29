<?php 
if(isLogin()){
	$max_row=30;
	$cur_page=isset($_POST['txtCurnpage'])? $_POST['txtCurnpage']:1;

	$type_user = getInfo('utype');
	if($type_user=="hocsinh"){
		$member_user = getInfo('username');
	}else{
		$member_user = isset($_CHILD_INFO['username']) ? $_CHILD_INFO['username']:"";
	}
?>
<div class="card mt-3">
<h2 class="fw-400 font-lg d-block">Thông báo</h2>
<div class="row">
	<div class="col-md-9 col-xs-12">
		<div class="box-notifi group-notifi">
			<?php
			$list_notifi=api_get_notifi();
		
			if(count($list_notifi)>0) {
				
				$total_items=api_get_notifi($member_user,'',$cur_page,$max_row,true);
				?>
				<div class="rep_content_notifi">
					<?php 
					foreach($list_notifi as $row){
						$url=ROOTHOST."notifi/".$row['id'];
						?>
						<a class="list-notifi" href="<?php echo $url;?>">
							<div class="img-notifi"><img src="<?php echo ROOTHOST;?>images/logo2.png" class="thumb"></div>
							<h5 class="name"><?php echo $row['title'];?></h5>
							<p class="nt-link intro"><?php echo substr_replace($row['contents'], "...", 150);?></p>
							<small><?php echo ago($row['cdate']);?></small>
							<span class="feather-chevron-right feather"></span>
						</a>
						<?php 
					}
					?>
				</div>
				</div>
				 <div class="box-pagination">
				<?php echo paging($total_items,$max_row,$cur_page); ?>
			</div>
			<?php }
			else echo '<p>Hiện chưa có thông báo</p>';?>

		
	</div>
	
	 <div class="col-md-3 col-xs-0"></div>
	  <div class="clearfix"><div>
	
</div>
<?php }?>
			