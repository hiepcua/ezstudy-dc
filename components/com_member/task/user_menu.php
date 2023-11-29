<?php 
$task= isset($_GET['task'])?strip_tags(htmlentities($_GET['task'])):''; 
global $type_user;
?>
<ul class="user_menu">
	
	<li <?php if($task == "profile") echo 'class="active"';?>>
		<a href="<?php echo ROOTHOST;?>user-info/profile"> Thông tin cá nhân</a>
	</li>
	<?php if($type_user == "hocsinh" OR isset($_SESSION['USER_JOININ'])){ ?>
	<li <?php if($task == "histories") echo 'class="active"';?>>
		<a class="" href="#grade_histories"> Lịch sử học tập</a>
	</li>
	<?php } ?>
	<?php if($type_user == "hocsinh" OR isset($_SESSION['USER_JOININ'])){ ?>
	<li <?php if($task == "course") echo 'class="active"';?>>
		<a class="" href="#khoa_hoc"> Khóa học của bạn</a>
	</li>
	<?php } ?>
	<?php if($type_user=='chame'){?>
	<li <?php if($task == "link_account") echo 'class="active"';?>>
		<a class="" href="<?php echo ROOTHOST;?>user-info/link-account"> Liên kết tài khoản</a>
	</li>
	<?php }?>
	<li <?php if($task == "changepass") echo 'class="active"';?>>
		<a href="<?php echo ROOTHOST;?>user-info/changepass/"> Đổi mật khẩu</a>
	</li>
	<li><a href="<?php echo ROOTHOST;?>logout"><i class="fa fa-power-off"></i> Đăng xuất</a></li>
</ul>
<script>
$('ul.user_menu li').click(function(){
	$('ul.user_menu li').removeClass('active');
	$(this).addClass('active');
});
</script>
<!--
<li <?php if($task == "create_account") echo 'class="active"';?>>
	<a href="<?php echo ROOTHOST;?>user-info/create-account"> Tạo tài khoản cho con</a>
</li>
-->