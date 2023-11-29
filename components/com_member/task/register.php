<?php
defined('ISHOME') or die("Can't acess this page, please come back!");
$err=$username=$password='';
$ref = isset($_GET['ref']) ? antiData($_GET['ref']) : '';
?>
<div class="login-page vertical-center"></div>
<script type="text/javascript">
$(document).ready(function(){
	var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_register.php";
	$.get(url,{'ref': "<?php echo $ref;?>"},function(req) {
		$(".login-page").html(req);
	})
})
</script>