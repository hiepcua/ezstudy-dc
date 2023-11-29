<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$err=$username=$password='';
?>
<div class="login-page vertical-center"></div>
<script type="text/javascript">
$(document).ready(function(){
	var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_login.php";
	$.get(url,function(req) {
		$(".login-page").html(req);
	})
})
</script>