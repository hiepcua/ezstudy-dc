<?php $content=isset($_GET['content'])? addslashes($_GET['content']):'packet';
if(!in_array($content, array('packet','service','product'))) die('Page not found');
?>
<div class="card">
	<div class="card-block">
	<div class="row">
		<div class="col-md-3 col-xs-12"><?php include("user_menu.php");?></div>
		<div class="col-md-9 col-xs-12">
			<div class='header'>
				<h1 class='page-title pull-left'>Lịch sử gần nhất</h1>
				<?php if($content=='packet'){?><span class="btn btn-success pull-right" onclick="frm_packet('EZ1',1)"><i class="fa fa-plus-square-o"></i> Gia hạn</span><?php }?>
				<?php if($content=='product'){?><a class="btn btn-success pull-right" href="<?php echo ROOTHOST;?>product"><i class="fa fa-plus-square-o"></i> Mua</a><?php }?>
				<?php if($content=='service'){?><a class="btn btn-success pull-right" href="<?php echo ROOTHOST;?>"><i class="fa fa-plus-square-o"></i> Mua</a><?php } ?>				
				<div class="clearfix"></div>
				<hr>
			</div>

			<ul class="list-inline list-tags">
				<li><a href="<?php echo ROOTHOST;?>histories/packet" class="<?php if($content=='packet') echo 'active'; ?>">Gia hạn tài khoản</a></li>
				<li><a href="<?php echo ROOTHOST;?>histories/service" class="<?php if($content=='service') echo 'active'; ?>">Khóa học giáo viên hướng dẫn</a></li>
				<li><a href="<?php echo ROOTHOST;?>histories/product" class="<?php if($content=='product') echo 'active'; ?>">Khóa học Live</a></li>
			</ul>
			
			<div class="body box-white">
				<div class="item-account">
				<?php 
				if($content!=''){
					include_once('content/'.$content.'.php');
				}
				?>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>