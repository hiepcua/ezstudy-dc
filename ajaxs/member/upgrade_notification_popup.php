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

$packet = isset($_POST['packet'])? antiData($_POST['packet']):'EZ2';
?>
<style type="text/css">
	.modal-body{
		padding-bottom: 0px;
	}
	.upgrade_notification .bottom.flex {
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		align-content: center;
		justify-content: space-around;
		align-items: center;
		border-top: 1px solid #ccc;
		margin-left: -15px;
		margin-right: -15px;
	}
	.upgrade_notification .bottom.flex .item {
		width: 50%;
		font-size: 16px;
		text-align: center;
		padding: 15px;
		border-right: 1px solid #ccc;
	}
	.upgrade_notification .bottom.flex .item:last-child{border-right: 0px;}
	.wr-img{text-align: center;}
	.wr-img img{
		width: 200px;
	}
</style>
<form id="ajax_action" class="upgrade_notification" method="post">
	<div class="form-group text-center">
		<div class="wr-img">
			<img src="<?php echo ROOTHOST;?>images/khoa.jpg">
		</div>
		<h4>Chức năng đang tạm khóa</h4>
		<div>Bạn đang ở khóa học miễn phí. Hãy nâng cấp khóa học để sử dụng.</div>
	</div>

	<div class="bottom flex">
		<div class="item">
			<a href="javascript:void(0)" data-dismiss="modal">Đóng</a>
		</div>
		<div class="item">
			<a href="javascript:void(0)" id="btn_upgrade_packet">Nâng cấp</a>
		</div>
	</div>
</form>
<script type="text/javascript">
	$("#btn_upgrade_packet").click(function(){
		frm_packet_upgrade('<?php echo getInfo('username');?>');
	});
</script>