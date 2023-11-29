<?php
$utype = getInfo("utype");
if($utype=="hocsinh"){
	$member_user = getInfo("username");
	$cur_packet = getInfo("packet");
}else if($utype=="chame"){
	$member_user = isset($_CHILD_INFO["username"]) ? $_CHILD_INFO["username"]:"";
	$cur_packet = isset($_CHILD_INFO["packet"]) ? $_CHILD_INFO["packet"]:"";
}
?>
<nav class="navigation scroll-bar">
	<div class=" pl-0 pr-0">
		<div class="nav-content">
			<div class="nav-top">
				<a href="<?php echo ROOTHOST;?>"><i class="feather-slack text-success display1-size mr-3 ml-3"></i><span class="d-inline-block fredoka-font ls-3 fw-600 text-current font-xl logo-text mb-0">EZ Study </span> </a>
				<a href="#" class="close-nav d-inline-block d-lg-none"><i class="ti-close bg-grey mb-4 btn-round-sm font-xssss fw-700 text-dark ml-auto mr-2 "></i></a>
			</div>
			<ul class="mb-3">
				<li class="logo d-none d-xl-block d-lg-block"></li>
				<?php if($type_user=='hocsinh'){?>
					<li><a href="<?php echo ROOTHOST;?>" class="<?php if($COM=='frontpage') echo 'active';?> nav-content-bttn open-font" data-tab="chats"><i class="feather-tv mr-3"></i><span>Trang chủ</span></a></li>
					<li>
						<a href="<?php echo ROOTHOST;?>lession-list" class="<?php $search_array =array('list','monhoc','baihoc'); if($COM=='lesson' && in_array($task, $search_array)) echo 'active';?> nav-content-bttn open-font" data-tab="friends">
							<i class="fa fa-book mr-3"></i><span>Tự học</span>
						</a>
					</li>
					<li>

						<a href="<?php echo ROOTHOST;?>lession-exam" class="<?php if($COM=='lesson' && $task=='exam') echo 'active';?> nav-content-bttn open-font" data-tab="favorites">
							<i class="fa fa-graduation-cap mr-3"></i><span>Luyện thi</span>
						</a>
					</li>
					<!--<li><a href="<?php echo ROOTHOST;?>product" class="<?php if($COM=='product') echo 'active';?> nav-content-bttn open-font" data-tab="favorites"><i class="feather-play-circle mr-3"></i><span>Khóa học</span></a></li>-->
				<?php }else{?>
					<li><a href="<?php echo ROOTHOST;?>" class="<?php if($COM=='frontpage') echo 'active';?> nav-content-bttn open-font" data-tab="chats"><i class="feather-tv mr-3"></i><span>Trang chủ</span></a></li>

					<li><a href="<?php echo ROOTHOST;?>lession-list" class="<?php $search_array =array('list','monhoc','baihoc'); if($COM=='lesson' && in_array($task, $search_array)) echo 'active';?> nav-content-bttn open-font" data-tab="friends"><i class="feather-shopping-bag mr-3"></i><span>Học cùng con</span></a></li>
					<li><a href="<?php echo ROOTHOST;?>report" class="<?php if($COM=='report') echo 'active';?> nav-content-bttn open-font" data-tab="favorites"><i class="feather-globe mr-3"></i><span>Báo cáo học tập</span></a></li>
					<!--<li><a href="<?php echo ROOTHOST;?>product" class="<?php if($COM=='product') echo 'active';?> nav-content-bttn open-font" data-tab="favorites"><i class="feather-play-circle mr-3"></i><span>Khóa học - Dịch vụ</span></a></li>-->
				<?php }?>

				<li><a href="<?php echo ROOTHOST;?>user-info/profile" class="<?php $search_array =array('profile','changepass'); if($COM=='member' && in_array($task, $search_array)) echo 'active';?> nav-content-bttn open-font" data-tab="friends"><i class="feather-user mr-3"></i><span>Tài khoản</span></a></li>
			</ul>

			<?php
			if($cur_packet=="" || $cur_packet=="EZ1"){
				?>
				<div class="update_now hidden-xs">
					<img src="<?php echo ROOTHOST; ?>images/icons/update_now.png" alt="Nâng cấp ngay">
					<p >Nâng cấp để học được nhiều hơn?</p>
					<p >
						<a href="#" class="btn btn-primary btn_update_now" onclick="frm_packet_upgrade('<?php echo $member_user;?>')">Nâng cấp gói</a>
					</p>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</nav>
