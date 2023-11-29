<?php 
function content_comment($str,$content,$username){
	?>
<div class="item">
		<div class="avatar"><?php echo $str;?></div>
		<div class="content-comment">
			<h4 class="txt-user"><?php echo $username;?></span></h4>
			<p class="txt"><?php echo $content;?></p>
			<span class="txt-label">Vừa mới đăng</span>
			
		</div>
	</div>
	<?php 
}
function content_topic($topic_id,$intro,$comments,$i,$username){
	 $url=ROOTHOST."chat/".$topic_id;?>
	<a href="<?php echo $url;?>" class="box-card item-topic">
		<div class="txt-cm"><?php echo $intro;?></div>
		<div class="item-info-user">
			<ul class="list-inline">
				<li class="txt-username"><?php echo $username;?></span></li>
				<li class="txt-time"><?php echo date('d-m-Y H:i:s');?></li>
			</ul>
		</div>
		<i class="fas fa-angle-right"></i>
	</a>
<?php } 
function content_comment_($str,$content,$username){
	?>
<div class="item">
		<div class="avatar"><?php echo $str;?></div>
		<div class="content-comment">
			<h4 class="txt-user"><?php echo $username;?></span></h4>
			<p class="txt"><?php echo $content;?></p>
			<span class="txt-label">Vừa mới đăng</span>
			
		</div>
	</div>
	<?php 
}
function content_topic_($topic_id,$intro,$comments,$i,$username){?>
	<div class="box-card">
		<div class="txt-cm"><?php echo $intro;?></div>
		<div class="item-info-user">
			<ul class="list-inline">
				<li class="txt-username"><?php echo $username;?></span></li>
				<li class="txt-time"><?php echo date('d-m-Y H:i:s');?></li>
			</ul>
		</div>
		<div id="respon-content<?php echo $i;?>" class="list-comment">
		 <form method="post" id="frm-comment<?php echo $i;?>" class="frm-comment">
			<input name="txt_topicid"  value="<?php echo $topic_id;?>" type="hidden">
			<input name="txt_user" value="giaovien1" type="hidden">
			<?php
			//var_dump($comments);
			//$comments=json_decode($comments, true);
			
			if($comments!=''){
			foreach($comments as $item){
				$username=$item['by_member'];
				$content=$item['content'];
				$str=explode('-',un_unicode($username));
				$list='';
				foreach($str as $val){
					$list.=substr($val,0,1);
				}
				$leng=strlen($list);
				if($leng>=2) $str=substr($list,0,2);
				else $str=substr($list,0,2);
				content_comment($str,$content,$username);
			}
			}
			
			?>
			<div class="content-txt">
				<textarea name="txt_content" class="txt_content form-control" id="in-comment<?php echo $i;?>" required="true" placeholder="Nội dung trả lời"/></textarea>
				<span class="btn btn-success btn-send" id="submit_frm" onclick="send_comment(this,<?php echo $i;?>)">Gửi</span>
			</div>
		</form>

		</div>
	</div>
<?php } ?>