<?php
session_start();
ini_set('display_errors',1);
define('ISHOME',true);
define('incl_path','global/libs/');
define('libs_path','libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once('includes/gfconfig.php');
require_once(incl_path.'gffunc_wallet.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'Pusher.php');
require_once(libs_path.'cls.mysql.php');
include_once(incl_path."config_api.php"); // get data api
//-------------- FULL URL --------------
global $_FULLURL;
if(!isset($_FULLURL)) $_FULLURL=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$COM = isset($_GET['com'])?strip_tags(htmlentities($_GET['com'])):'frontpage';
$task= isset($_GET['task'])?strip_tags(htmlentities($_GET['task'])):'';
$username = getInfo('username');
$type_user = getInfo('utype');
global $_CHILD_INFO;
global $_ARR_CHILDS;
if($type_user=="chame"){
	$_ARR_CHILDS = array();
	$childs = api_get_child_member($username);
	foreach ($childs as $key => $value) {
		$_ARR_CHILDS[$value['username']] = $value;
	}

	$child0 = isset($childs[0]['username']) ? $childs[0]['username']:"";
	$cookie_child = isset($_COOKIE['MEMBER_CHILD']) ? $_COOKIE['MEMBER_CHILD']:"";

	if(!isset($_SESSION['MEMBER_CHILD'])){
		if(isset($_COOKIE['MEMBER_CHILD']) && $_COOKIE['MEMBER_CHILD']!='' && array_key_exists($cookie_child, $_ARR_CHILDS)){
			$_SESSION['MEMBER_CHILD'] = $_COOKIE['MEMBER_CHILD'];
		}else{
			$_SESSION['MEMBER_CHILD'] = $child0;
		}
	}else{
		if(isset($_COOKIE['MEMBER_CHILD']) && $_COOKIE['MEMBER_CHILD']!=""){
			$_SESSION['MEMBER_CHILD'] = $_COOKIE['MEMBER_CHILD'];
		}

		if(!array_key_exists($cookie_child, $_ARR_CHILDS)){
			$_SESSION['MEMBER_CHILD'] = $child0;
		}
	}
	$_CHILD_INFO = $_ARR_CHILDS[$_SESSION['MEMBER_CHILD']];
}
?>
<!doctype html>
	<html lang='vi'>
	<head>
		<meta charset="utf-8"/>
		<title>Học tập - Ezstudy.vn</title>
		<meta name="robots" content="noindex, nofollow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Roboto:200,200i,400,400i,500,500i,700,700i,900,900i&amp;subset=vietnamese" rel="stylesheet"/>
		<link rel="stylesheet" href="<?php echo ROOTHOST;?>global/admin/css/themify-icons.css">
		<link rel="stylesheet" href="<?php echo ROOTHOST;?>global/admin/css/feather.css">
		<link rel="stylesheet" href="<?php echo ROOTHOST;?>global/admin/css/style.css">
		<link rel='stylesheet' href='<?php echo ROOTHOST;?>css/style.css?v=1'/>
		<link rel='stylesheet' href='<?php echo ROOTHOST;?>css/style_nxtuyen.css?v=2'/>
		<link rel='stylesheet' href='<?php echo ROOTHOST;?>css/style_vinh.css'/>
		<link rel="stylesheet" href="<?php echo ROOTHOST; ?>global/css/select2.min.css">
		<link rel='stylesheet' href='<?php echo ROOTHOST;?>global/css/font-awesome.min.css'/>
		<link rel="stylesheet" href="<?php echo ROOTHOST;?>css/style-responsive.css?v=2"/>
		<script>var ROOTHOST = '<?php echo ROOTHOST;?>';</script>
		<script src='<?php echo ROOTHOST;?>global/js/jquery-1.11.3.min.js'></script>
		<script src='<?php echo ROOTHOST;?>global/js/bootstrap.min.js'></script>
		<script src="<?php echo ROOTHOST;?>global/js/select2.min.js"></script>
		<script src="<?php echo ROOTHOST;?>js/func.js"></script>
		<script src="<?php echo ROOTHOST;?>js/main.js"></script>
		<script src="<?php echo ROOTHOST;?>js/script.min.js"></script>

		<script src="<?php echo ROOTHOST;?>extensions/editorScript.js"></script>
		<script type="text/x-mathjax-config">
			MathJax.Hub.Config({
				tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
			});
		</script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML"></script>
		<script src='<?php echo ROOTHOST;?>global/js/owl/owl.carousel.min.js'></script>
	</head>
	<?php	
	if(!isLogin()){
		echo "<link rel='stylesheet' href='".ROOTHOST."'global/css/bootstrap.min.css'/>";
		echo "<body>";
		if($COM == "member" && $task == "register")
			include_once(COM_PATH."com_member/task/register.php");
		else 
			include_once(COM_PATH."com_member/task/login.php");
	}else{
		?>
		<body class="color-theme-blue mont-font <?php if($COM=='frontpage' && $type_user=="hocsinh") echo 'dashboard';?>">
			<div class="main-wrapper">
				<?php include('modules/left_menu.php');?>
				<!-- main content -->
				<div class="main-content <?php if($COM=='chat' && $task=='list') echo 'bg';?>">
					<?php include('modules/top_menu.php');?>
					<div class="content-page">
						<?php
						$subject_list = getInfo('subject_list');
						if($subject_list == 'N/a' || $subject_list == '') $arr_subject=array();
						else $arr_subject = explode(',',$subject_list);
						$packet_user = checkPacketMember();
						$flag1 = $flag2 = false;

						if($subject_list == 'N/a' || $subject_list == '') $flag1=true;
						if($packet_user==true) $flag2=true;

						if($flag1==true && $flag2==true && $type_user=='hocsinh'){
							include_once("components/com_frontpage/hocsinh/hocsinh_welcome.php");
						}else{
							if($type_user=='hocsinh'){
								$par_user = getInfo('par_user'); 
								$status_link = getInfo('status_link'); 
								if($par_user!='' && $status_link==''){
									echo '<div class="box-active-link">';
									echo 'Bạn được lời mới xác nhận liên kết với tài khoản '.$par_user;
									echo '<button class="btn btn-success" onclick="active_link(1)">Xác nhận</button>';
									echo '<button class="btn btn-default" onclick="active_link(0)">Hủy bỏ</button>';
									echo '</div>';
								}
							}

							$path_com="components/com_$COM/layout.php";
							if(is_file($path_com)) include($path_com);
						}
						?>
					</div>
				</div>
			</div>

			<?php include_once('modules/footer.php');?>	

			<?php
			// Chỉ cộng thưởng cho hs
			$username = getInfo('username');
			$type_user = getInfo('utype');
			if($type_user=='hocsinh') AddBonus($username, '', 1);
			?>

			<div id="myModalPopup" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"></h4>
						</div>
						<div class="modal-body" id="modal-content"></div>
					</div>
				</div>
			</div>
			<div class="loading"></div>
			<div id="modal_mask"></div>

		<?php } 
		$time = time();
		$arr_date = getDateReport(1); 
		$first_date = isset($arr_date['first'])? $arr_date['first']:'';
		$last_date = isset($arr_date['last'])? $arr_date['last']:'';
		?>
		<script src="<?php echo ROOTHOST;?>global/admin/js/plugin.js"></script>
		<script src="<?php echo ROOTHOST;?>global/admin/js/scripts.js"></script>
		<script src="<?php echo ROOTHOST;?>js/pusher.min.js"></script>
		<script>
			Pusher.logToConsole = false;
			var pusher = new Pusher('e0810f7aa48b8400d8c6', {
				cluster: 'ap1',
				forceTLS: true
			});
			var channel = pusher.subscribe('RT_notifi');
			channel.bind('my-event', function(data) {
				$.post('<?php echo ROOTHOST;?>ajaxs/notifi/count_isread.php',{data},function(mes){
					$('#count_notifi').html(mes);
				})
			});
			var channel2 = pusher.subscribe('RT_messenger');
			channel2.bind('my-event', function(data) {
				$.post('<?php echo ROOTHOST;?>ajaxs/member/messenger.php',{data},function(mes){
					$('#rep_notice').prepend(mes);
				})
			});
			var channel4 = pusher.subscribe('RT_messenger_review');
			channel4.bind('my-event', function(data) {
				$.post('<?php echo ROOTHOST;?>ajaxs/messenger/review_rt.php',{data},function(mes){
					console.log(mes);
					$('#repon_review').prepend(mes);
				})
			});
			var channel3 = pusher.subscribe('RT_messenger_phuhuynh_giaovien');
			channel3.bind('my-event', function(data) {
				var type='';
				$.post('<?php echo ROOTHOST;?>ajaxs/messenger/load_rt.php',{data,type},function(mes){
					$('#respon-content-chat').append(mes);

				})
				var type=1;
				$.post('<?php echo ROOTHOST;?>ajaxs/messenger/load_rt.php',{data, type},function(mes){
					$('#repon_new_chat').prepend(mes);

				})
			});

			var username='<?php echo $username;?>';
			var name_json='data3_'+username;
			function active_link(value){
				var url = "<?php echo ROOTHOST;?>ajaxs/member/active_link_account.php";
				$.post(url,{value},function(req){
					// console.log(req);
					if(req=='success'){
						alert('Xác nhận thông tin thành công');
						window.location.reload();
					}
				});
			}

			$(document).ready(function(){
				function act_time_visit(){
					var munit=10*60; //10 phút
					var cur_time = Date.parse(new Date()) / 1000;
					var rs=JSON.parse(localStorage.getItem(name_json));
					if(rs){
						var today=rs['today'];
						var first_date='<?php echo $first_date;?>';
						var last_date='<?php echo $last_date;?>';
						var last_time=parseInt(rs['last_time']);
						var user=rs['username'];
						if(first_date<today && last_date>=today){ 
							num=cur_time-last_time;
							if(num<munit){
								var total_time=parseInt(rs['total_time'])+num;
								save_json(cur_time,total_time,user);	
							}
							else{
								var total_time=parseInt(rs['total_time']);
								save_json(cur_time,total_time,user);
							}

						}else{
							var type=0;
							var total_time=parseInt(rs['total_time']);
							var url = "<?php echo ROOTHOST;?>ajaxs/member/logout.php";
							$.post(url,{last_time,total_time,user,type},function(req){
								localStorage.removeItem(name_json);
								var time = Date.parse(new Date()) / 1000;
								save_json(time,0,user);	
							});
						}

					}
					else{
						var user='<?php echo $username;?>';
						save_json(cur_time,0,user);	
					}
					var rs=JSON.parse(localStorage.getItem(name_json));
				}

				function save_json(cur_time,total_time,user){
					var time = Date.parse(new Date()) / 1000;
					var arr={'last_time':cur_time,'total_time':total_time, 'username':user,'today':time};
					var array=JSON.stringify(arr);
					localStorage.setItem(name_json,array);
				}

				$( document.body ).click(function() {
					act_time_visit();
				});

				function push_data(type){
					var rs=JSON.parse(localStorage.getItem(name_json));
					if(rs){
						var last_time=parseInt(rs['last_time']);
						var total_time=parseInt(rs['total_time']);
						var user=rs['username'];
						var url = "<?php echo ROOTHOST;?>ajaxs/member/logout.php";
						$.post(url,{last_time,total_time,user,type},function(req){
							localStorage.removeItem(name_json);
							var time = Date.parse(new Date()) / 1000;
							if(type==1) window.location.reload();
							else save_json(time,0,user);	
						});
					}

					return null;
				}

				$('.logout').click(function(){
					push_data(1);	
				});

				window.onbeforeunload = function(){
					act_time_visit();
					push_data(0);	
				}

				$('#myModalPopup').on('show.bs.modal', function() {
					$('#modal_mask').addClass('active');
				});

				$('#myModalPopup').on('hidden.bs.modal', function() {
					$('#modal_mask').removeClass('active');
				});

				//prevent form resubmission when page is refreshed (F5 / CTRL+R)
				if ( window.history.replaceState ) {
					window.history.replaceState( null, null, window.location.href );
				}

				//input focus select all text
				$("input[type=text]").focus(function() {
					$(this).select();
				});

				$("input[type=number]").focus(function() {
					$(this).select();
				});

				$(".change-lop").click(function(){
					var grade = $(this).attr('dataid');
					var url = "<?php echo ROOTHOST;?>ajaxs/grade/frm_change.php?grade="+grade;
					showForm(url,"Đổi lớp","");
				})
			});

			//show/hide loading
			function showLoading() {
				$(".loading").show();
			}

			function hideLoading() {
				$(".loading").hide();
			}

			function callActionAccount(_this,act){
				var item_id=$(_this).attr('data-id');
				if(act==2){
					var url='<?php echo ROOTHOST;?>ajaxs/account/edit.php';
					var title='Sửa thông tin tài khoản';
				}
				else if(act==3) {
					url='<?php echo ROOTHOST;?>ajaxs/account/delete.php';
					title='Xóa tài khoản';
				}
				else{	
					url='<?php echo ROOTHOST;?>ajaxs/account/detail.php';
					title='Thông tin chi tiết';
				}
				$.post(url,{item_id}, function(response_data){
					$('#myModalPopup').modal('show');
					$('#myModalPopup .modal-body').html(response_data);
					$('#myModalPopup .modal-title').html(title);
				});
			}

			function buy_course(id, type){
				$('#myModalPopup').modal('show');
				if(type==2){
					$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
					$('#myModalPopup .modal-dialog').addClass('modal-lg');
					label='Mua dịch vụ';
				}else {
					$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
					$('#myModalPopup .modal-dialog').addClass('modal-md');
					label='Xác nhận đăng ký';
				}

				$('#myModalPopup .modal-title').html(label);
				if(type==1)  url = "<?php echo ROOTHOST;?>ajaxs/service/frm_service.php";
				else  url = "<?php echo ROOTHOST;?>ajaxs/register_course/frm_buycourse.php";
				$.post(url,{id},function(req){
					$('#modal-content').html(req);

				});
			}

			function join_in_acc(){
				$('#myModalPopup').modal('show');
				$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
				$('#myModalPopup .modal-dialog').addClass('modal-lg');
				var label='Join in tài khoản con';
				$('#myModalPopup .modal-title').html(label);
				var url = "<?php echo ROOTHOST;?>ajaxs/account/frm_joinin_acc.php";
				$.post(url,function(req){
					$('#modal-content').html(req);
				});
			}

			function back_to_account_par(id){
				var url = "<?php echo ROOTHOST;?>ajaxs/account/proccess_joinin.php";
				var type=1;
				$.post(url,{id,type},function(req){
					if(req=='success'){
						window.location.href="<?php echo ROOTHOST;?>";
					}
					else alert('Trở lại tài khoản chính không thành công!');
				});
			}

			function load_version_subject(){
				var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_version_subject.php";
				$.post(url,function(req){
					$('#myModalPopup').modal('show');
					$('#myModalPopup .modal-title').html('Chọn môn học');
					$('#myModalPopup .modal-body').html(req);
				});
			}

			function show_upgrade_notify_popup(packet=""){
				var url = "<?php echo ROOTHOST;?>ajaxs/member/upgrade_notification_popup.php";
				$.post(url,{'packet': packet},function(req){
					$('#myModalPopup').modal('show');
					$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
					$('#myModalPopup .modal-dialog').addClass('modal-md');
					$('#myModalPopup .modal-title').html('Nâng cấp tài khoản');
					$('#myModalPopup .modal-body').html(req);
				});
			}

			// Nâng cấp gói dịch vụ
			// function frm_packet(cur_packet=""){
			// 	$('#myModalPopup .modal-dialog').removeClass('modal-lg');
			// 	$('#myModalPopup .modal-title').html('Nâng cấp khóa học');
				
			// 	var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_packet.php";
			// 	$.post(url, {"cur_packet": cur_packet},function(req){
			// 		$('#modal-content').html(req);
			// 		$('#myModalPopup').modal('show');
			// 	});
			// }

			// Nâng cấp gói dịch vụ
			function frm_packet_upgrade(member){
				$('#myModalPopup .modal-dialog').removeClass('modal-lg');
				$('#myModalPopup .modal-title').html('Nâng cấp khóa học');
				
				var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_packet_upgrade.php";
				$.post(url, {"member": member},function(req){
					$('#modal-content').html(req);
					$('#myModalPopup').modal('show');
				});
			}

			// Gia hạn gói dịch vụ
			function frm_packet_extend(username){
				$('#myModalPopup .modal-dialog').removeClass('modal-lg');
				$('#myModalPopup .modal-title').html('Gia hạn khóa học');
				
				var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_packet_extend.php";
				$.post(url,{"username": username},function(req){
					$('#modal-content').html(req);
					$('#myModalPopup').modal('show');
				});
			}

			// Đăng ký gói dịch vụ
			function regis_packet(packet=""){
				$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
				$('#myModalPopup .modal-dialog').addClass('modal-md');
				$('#myModalPopup .modal-title').html('Đăng ký khóa học');
				
				var url = "<?php echo ROOTHOST;?>ajaxs/packet/frm_regis_packet.php";
				$.post(url, {"packet": packet},function(req){
					$('#modal-content').html(req);
					$('#myModalPopup').modal('show');
				});
			}

			function join_room(live_id=""){
				var url = "<?php echo ROOTHOST;?>ajaxs/bbb/student-join-room.php";
				$.post(url, {"live_id": live_id}, function(res){
					if(res!="error"){
						window.open(res, '_blank');
					}else{
						console.log("Lỗi!");
					}
				});
			}
		</script>
	</body>
	</html>