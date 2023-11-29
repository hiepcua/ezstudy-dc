<?php 
$username = getInfo('username');
$type_user = getInfo('utype');
if($type_user=="hocsinh"){
	$member_user = getInfo('username');
}else{
	$member_user = isset($_CHILD_INFO['username']) ? $_CHILD_INFO['username']:"";
}
?>
<div class="middle-sidebar-header bg-white">
	<div class="container">
		<div class="content-mn">
			<a href="<?php echo ROOTHOST;?>"><img src="<?php echo ROOTHOST;?>images/logo.png" class="img-logo"></a>
			<button class="header-menu"></button>
			<form action="#" class="float-left header-search">
				<div class="form-group mb-0 icon-input">
					<i class="feather-search font-lg text-grey-400"></i>
					<input type="text" placeholder="Từ khóa tìm kiếm" class="bg-transparent border-0 lh-32 pt-2 pb-2 pl-5 pr-3 font-xsss fw-500 rounded-xl w350">
				</div>
			</form>

			<?php if($type_user=="chame" && count($_ARR_CHILDS)>0){
				echo '<select id="header_cbo_member" class="cbo-member form-control">';
				foreach ($_ARR_CHILDS as $key => $value) {
					$selected = ($key==$_SESSION['MEMBER_CHILD'])?'selected="true"':'';
					echo '<option value="'.$value['username'].'" '.$selected.'>'.$value['fullname'].'</option>';
				}
				echo '</select>';
			}?>

			<ul class="d-flex ml-auto right-menu-icon">
				<li>
					<div class="dropdown box-notifi">
						<span  class="btn-user dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<div class="show-box"><span class="dot-count bg-warning"></span><i class="feather-bell font-xl text-current"></i></div>
						</span>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
							<div class="head">
								<h3 class="name">Thông báo<span class="number" id="number-notifi"></span></h3>
							</div>

							<div class="rep_content_notifi" id="rep_content_notifi">
								<?php 
								$list_notifi = api_get_notifi($member_user,'',1, 5);
								$number_read = 0;
								$class='';
								if(count($list_notifi)>0){	
									foreach($list_notifi as $row){
										$is_read=$row['is_read'];
										$arr_isread=$is_read!=''? json_decode($is_read, true):array();
										if(!in_array($member_user, $arr_isread)){
											$class='active';
											$number_read++;
										}
										$url=ROOTHOST."notifi/".$row['id'];
										?>
										<div class="list-notifi process_notifi <?php echo $class;?>" data-url="<?php echo $url;?>" data-id="<?php echo $row['id'];?>">
											<div class="img-notifi"><img src="<?php echo ROOTHOST;?>images/logo2.png" class="thumb"></div>
											<h5 class="name"><?php echo $row['title'];?></h5>
											<p class="nt-link intro"><?php echo substr_replace($row['contents'], "...", 20);?></p>
											<small><?php echo ago($row['cdate']);?></small>
										</div>
										<?php 
									}
								}
								?>
							</div>
							<div class="box-readmore"><a href="<?php echo ROOTHOST;?>notifi" class="btn btn-success btn-block">Xem thêm</a></div>
						</div>
						<span class="number-count" id="count_notifi"><?php echo $number_read;?></span>
					</div>
				</li>

				<li>
					<div class="dropdown box-user-nav">
						<span class="btn-user dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php 
							global $type_user;
							$user_class = '';
							if($type_user == "hocsinh") {
								$user_class = getInfo('grade');
								$user_class = $_Grade[$user_class];
							}
							echo getInfo('fullname')."<div class='label'>".$_AccountType[$type_user].' '.$user_class."</div>";?> 
						</span>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="<?php echo ROOTHOST;?>user-info/profile"><i class="fa fa-info-circle"></i> Cá nhân</a>
							<a class="dropdown-item" href="<?php echo ROOTHOST;?>user-info/changepass"><i class="fa fa-key"></i> Đổi mật khẩu</a>
							<a class="dropdown-item logout" href="#" rel="nofollow,noindex"><i class="fa fa-power-off"></i> Đăng xuất</a>
						</div>
					</div>
				</li>
				
				<li><a href="message.html"><i class="feather-message-square font-xl text-current"></i></a></li>
			</ul>
		</div>
	</div>
</div>
<script>
	$('.process_notifi').click(function(){
		var url_direct=$(this).attr('data-url');
		var id=$(this).attr('data-id');
		var url = "<?php echo ROOTHOST;?>ajaxs/notifi/is_read.php";
		$.post(url,{url_direct, id},function(req){
			if(req=='success') window.location.href=url_direct;
			else alert('Not access');
		});
	});

	$("#header_cbo_member").change(function(){
		var mem = $(this).val();
		var url = "<?php echo ROOTHOST;?>ajaxs/member/change_member_child.php";
		$.post(url, {"username": mem}, function(res){
			window.location.reload();
		})
	});
</script>
