<?php
defined('ISHOME') or die('Can not acess this page, please come back!');
$msg='';
$username = getInfo('username');
$type_user = getInfo('utype');
$par_user = getInfo('par_user');
$status_link = getInfo('status_link');
if(isset($_POST['cmdsave'])){	
	$arr = array();
	$arr['fullname'] = antiData($_POST['txtname']);
	$arr['gender']   = isset($_POST['optgender']) ? antiData($_POST['optgender']) : 'nam';
	$arr['phone']    = antiData($_POST['txtphone']);
	$arr['email']    = antiData($_POST['txtemail']);
	$arr['address']  = antiData($_POST['txtaddress']);
	$arr['birthday'] = $_POST['txtbirthday']!="" ? strtotime($_POST['txtbirthday']):0;
	$result = SysEdit("ez_member",$arr," username='$username'");
	$msg = "<div class='alert alert-success'>Cập nhật thành công</div>";
	// update dữ liệu member về DC
	if($result) {
		// Cập nhật thông tin học sinh sang DC
		$json = array();
		$json['key'] = PIT_API_KEY;
		$json['username'] 	= $username;
		$json['partner_code'] = PARTNER_CODE;
		$json['arr']   		= $arr;
		$post_data['data'] 	= encrypt(json_encode($json),PIT_API_KEY);
		$url = API_MEMBER_UPDATE_INFO;
		$reponse_data = Curl_Post($url,json_encode($post_data));

		if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
			if(isset($reponse_data['data']) && $reponse_data['data']=="success"){
				// Thiết lập lại session
				foreach ($arr as $key => $value) {
					$_SESSION['USER_LOGIN'][$key] = $value;
				}
			}
		}
	}
}

$member_info = api_get_member_info($username);
if(count($member_info)<1) die('Không có dữ liệu');
$row = $member_info; 
?>
<script language='javascript'>
	function checkinput(){
		return true;
	}
</script>

<div class="card info-profile">
	<div class="card-block">
		<div class="row">
			<div class="col-md-12 col-xs-12"><?php include("user_menu.php");?></div>
			<div class="col-md-12 col-xs-12">

				<div class="box_user">
					<div class='header'>
						<h1 class='page-title'>Thông tin cá nhân</h1>
					</div>

					<div class="body box-white">
						<div class="col-md-12">
							<div class="col-xs-3 pull-left">
								<img src="<?php echo ROOTHOST;?>images/avatar/default-avatar.png" class="img-responsive"/>
							</div>
						
							<div class="col-xs-9 pull-left">
								<form id="frm_action" name="frm_action" method="post" action="">
									<div class="row form-group">
										<label class="col-md-2"></label>
										<div class="col-md-10"><span class='err_mess'><?php echo $msg;?></span></div>
									</div>

									<div class="row form-group">
										<label class="col-md-2 control-label">Họ tên <span class="star">*</span></label>
										<div class="col-md-4">
											<input class="form-control" id="txtname" name="txtname" value="<?php echo $row['fullname'];?>" type="text" required>
										</div>
									</div>

									<div class="row form-group">
										<label class="col-md-2 control-label">Điện thoại</label>
										<div class="col-md-4">
											<input class="form-control" name="txtphone" type="tel" id="txtphone" value="<?php echo $row['phone'];?>"/>
										</div>
										<label class="col-md-2 control-label ">Email</label>
										<div class="col-md-4">
											<input class="form-control" name="txtemail" type="email" id="txtemail" value="<?php echo $row['email'];?>"/>
										</div>
									</div>
									<div class="row form-group">
										<label class="col-md-2 control-label">Ngày sinh</label>
										<div class="col-md-4">
											<input class="form-control" name="txtbirthday" type="date" id="txtbirthday" value="<?php echo $row['birthday']!="" && $row['birthday']!=0 ? date('Y-m-d',$row['birthday']):null;?>"/>
										</div>
										<label class="col-md-2 control-label text-right">Giới tính</label>
										<div class="col-md-4">
											<label for="gender1" style="font-weight:500">
												<input name="optgender" type="radio" id="gender1" value="nam" <?php if($row['gender']=='nam') echo 'checked';?>/> Nam
											</label>
											<label for="gender2" style="font-weight:500">
												<input name="optgender" type="radio" id="gender2" value="nu" <?php if($row['gender']=='nu') echo 'checked';?>/> Nữ
											</label>
										</div>
									</div>

									<div class="row form-group">
										<label class="col-md-2 control-label">Địa chỉ</label>
										<div class="col-md-10">
											<input class="form-control" id="txtaddress" name="txtaddress" value="<?php echo $row['address'];?>" type="text">
										</div>
									</div>

									<div class="form-group text-center"  id='grade_histories'>
										<button type="submit" name="cmdsave" id="cmdsave" value="Lưu thông tin" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu lại</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<?php if($type_user=='chame'){ 
					$list_child = api_get_child_member($username);
					?> 
					
					<div class="box_user">
						<div class='header'>
							<h1 class="page-title">Tài khoản con 
								<button type="button" class="btn btn-primary ml-2" onclick="add_member_child('<?php echo $username;?>')">Liên kết tài khoản con</button>
								<button type="button" class="btn btn-primary ml-2" onclick="create_member_child('<?php echo $username;?>')">Tạo tài khoản con</button>
							</h1>
						</div>
						<div class="info-account">
							<table class="table">
								<thead>
									<th>Tài khoản</th>
									<th>Họ và tên</th>
									<th>Lớp</th>
									<th>Khóa học</th>
									<th class="text-center">Ngày đăng ký</th>
									<th class="text-center">Ngày hết hạn</th>
									<th class="text-center">Nâng cấp/gia hạn</th>
									<th class="text-center">Tình trạng khóa học</th>
								</thead>
								<tbody>
									<?php 
									foreach ($list_child as $key => $value) {
										$child_packet = $value['packet'];
										$grade_name = $_Grade[$value['grade']];
										$packet_status = $value['packet_status'];
										$packet_status_name = $packet_status!="" ? $_PACKET_STATUS[$packet_status]:"Đang hoạt động";

										switch ($child_packet) {
											case 'EZ1':
											$packet_name="Khóa học cơ bản";
											break;
											case 'EZ2':
											$packet_name="Khóa học nâng cao";
											break;
											default:
											$packet_name="Miễn phí";
											break;
										}

										$cdate = $edate = '';
										$e_date = $value['edate'];
										$c_date = $value['cdate'];
										if($c_date!='' && $c_date!='N/a'){
											$cdate = date('d-m-Y',$c_date);
										}
										if($e_date!='' && $e_date!='N/a'){
											$edate= date('d-m-Y',$e_date);
										}
										$diff="-";
										if($value['edate']!=''){
											$today=date('d-m-Y');
											$date1=date_create($today);
											$date2=date_create($edate);
											$diff=date_diff($date1,$date2);
										}
										echo '<tr>';
										echo '<td>'.$value['username'].'</td>';
										echo '<td>'.$value['fullname'].'</td>';
										echo '<td>'.$grade_name.'</td>';
										echo '<td>'.$packet_name.'</td>';
										echo '<td align="center">'.$cdate.'</td>';
										echo '<td align="center">'.$edate.'</td>';

										if($child_packet==""){
											echo '<td align="center"><button type="button" class="btn btn-success" onclick="frm_packet_upgrade(\''.$value['username'].'\')">Đăng ký</button></td>';
										}else{
											echo '<td align="center">';
											if($diff->days<=MIN_DAY && $packet_status=='running'){
												echo '<a class="btn btn-primary mb-1" href="javascript:void(0);" onclick="frm_packet_extend(\''.$value['username'].'\')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Gia hạn</a>';
											}

											if($child_packet=="EZ1"){
												echo '<a class="btn btn-success ml-1" href="javascript:void(0);" onclick="frm_packet_upgrade(\''.$value['username'].'\')">Nâng cấp</a>';
											}

											echo '</td>';
										}

										echo '<td align="center">'.$packet_status_name.'</td>';
										echo '</tr>';
									}
									$objHis=SysGetList('ez_members_grade',array()," AND username='$username' ORDER BY grade DESC",false);
									while($rHis=$objHis->Fetch_Assoc()){
										?>
										<tr>
											<td width='150'><?php echo date('d/m/Y',$rHis['cdate']);?></td>
											<td><?php echo $rHis['username'];?></td>
											<td><?php echo $rHis['status'];?></td>
										</tr>
									<?php }?>
								</tbody>
							</table>
							
						</div>
					</div>
				<?php } ?> 
				
				<?php if($type_user=='hocsinh'){ ?>
					
					<div class="clearfix"></div>
					<div class="box_user">
						<div class='header'>
							<h1 class='page-title'>Lịch sử học tập</h1>
						</div>
						<div class="info-account">
							<table class="table">
								<thead>
									<th>Ngày bắt đầu</th>
									<th>Khối lớp</th>
									<th>Trạng thái</th>
								</thead>
								<tbody>
									<?php 
									$obj_learning = api_get_member_learning_history($username);
									if(is_array($obj_learning)){
										foreach ($obj_learning as $key => $value) {
											$grade_name = $_Grade[$value['grade']];
											$status = $value['status']=="yes"?"Đang học":"";
											echo '<tr>
											<td width="150">'.date('d/m/Y',$value['cdate']).'</td>
											<td>'.$grade_name.'</td>
											<td width="150">'.$status.'</td>
											</tr>';
										}
									}?>
								</tbody>
							</table>
							<div id='khoa_hoc' style='clear:both;'>&nbsp;</div>
						</div>
					</div>
				<?php } ?> 

				<?php if($type_user=='hocsinh' && $par_user!=""){ 
					$member_parent = api_get_member_info($par_user);
					?>
					<div class="clearfix"></div>
					<div class="box_user">
						<div class='header'>
							<h1 class='page-title'>Liên kết phụ huynh</h1>
						</div>
						<div class="info-account">
							<table class="table table-bordered">
								<thead>
									<th>Tài khoản phụ huynh</th>
									<th>Họ tên</th>
									<th class="text-center">Trạng thái</th>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $member_parent['username'];?></td>
										<td><?php echo $member_parent['fullname'];?></td>
										<?php if($status_link==""){ ?>
											<td align="center">
												<button class="btn btn-success" onclick="active_link(1)">Xác nhận</button>
												<button class="btn btn-default" onclick="active_link(0)">Hủy bỏ</button>
											</td>
										<?php }else{ ?>
											<td align="center">Đã xác nhận</td>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				<?php } ?>

		
				<?php if($type_user=='hocsinh'){ ?>
					<div class="box_user" id="khoa_hoc">
						<div class='header'>
							<h1 class='page-title'>Thông tin khóa học</h1>
						</div>
						<div class="info-account">
							<table class="table">
								<?php 
								$packet_user=getInfo('packet');
								if($packet_user!=''){
									$e_date=getInfo('edate');
									$c_date=getInfo('cdate');
									$packet=getInfo('packet');
									$packet_status=getInfo('packet_status');
									switch ($packet) {
										case 'EZ1':
										$packet_name=" khóa học cơ bản";
										break;
										case 'EZ2':
										$packet_name=" khóa học nâng cao";
										break;
										default:
										$packet_name=" miễn phí";
										break;
									}
									?>
									<tr>
										<td>Khóa học</td>
										<td class="text-center">Trạng thái</td>
									</tr>
									<tr>
										<td>
											<h4>Bạn đang sử dụng gói dịch vụ<span class="color-label"><?php echo $packet_name;?></span> </h4>
											<?php
											if($packet_status=='runing'){
												if($packet=='EZ1'){
													echo '<a class="btn btn-success" href="javascript:void(0);" onclick="frm_packet_upgrade(\''.getInfo('username').'\')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Nâng cấp</a>';
												}
											}?>
											<p>Trạng thái: <?php echo $_PACKET_STATUS[$packet_status];?></p>
											<p class="time"> 
												<?php
												$cdate=$edate='';
												if($c_date!='' && $c_date!='N/a'){
													$cdate= date('d-m-Y',$c_date);
													echo '<div>Ngày đăng ký: '.$cdate.'</div>';
												}
												if($e_date!='' && $e_date!='N/a'){
													$edate= date('d-m-Y',$e_date);
													echo '<div>Ngày hết hạn: '.$edate.'</div>';
												}
												$diff="-";
												if($edate!=''){
													$today=date('d-m-Y');
													$date1=date_create($today);
													$date2=date_create($edate);
													$diff=date_diff($date1,$date2);
												}
												?>
											</p>
										</td>
										<td class="td-regis text-center">
											<?php if($diff->days<=MIN_DAY && $packet_status=='running'){?>
												<a class="btn btn-primary" href="javascript:void(0);" onclick="frm_packet_extend('<?php echo getInfo('username');?>')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Gia hạn</a>
											<?php }?>
											<p class="time color-red">(Còn <?php echo $diff->format("%R%a");?> ngày)</p>
										</td>
									</tr>
								<?php }else{ ?>
									<tr>
										<td>
											<h4>Khóa học miễn phí</h4>
											<p class="txt">Bạn chỉ xem và học được kiến thức cơ bản. Hãy <span class="color-label">nâng cấp khóa học</span> để được học theo các mục tiêu và nhiệm vụ học tập theo các lộ trình cụ thể mà đã được các giáo viên chuyên gia giảng dạy hàng đầu biên soạn</p>
										</td>
										<td class="td-regis">
											<a class="btn btn-success" href="javascript:void(0);" onclick="frm_packet_upgrade('<?php echo getInfo('username');?>')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Nâng cấp ngay</a>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>
					<div class="clearfix"></div>

					<div class="box_user">
						<div class='header'>
							<h1 class='page-title'>Thông tin bộ sách</h1>
						</div>

						<?php 
						$type_subject=1;
						include_once("components/com_frontpage/hocsinh/hocsinh_welcome.php");
						?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script>
	$("#cmdsave").click(function(){
		var name = $("#txtname").val();	
		var regName= /^[a-zA-Z ]{2,}$/g;
		var gender = '';
		if ( $('#gender1').is(":checked")) gender='nam';
		if ( $('#gender2').is(":checked")) gender='nu';
		var phone = $("#txtphone").val();
		var email = $("#txtemail").val();
		var birthday = $("#txtbirthday").val();

		if(name==''){
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng nhập họ tên</div>');
			$('#txtname').focus();
			return false;
		}else if(!regName.test( removeAscent(name) )) {
			$('.err_mess').html('<div class="alert alert-danger">Họ tên có ít nhất 2 ký tự, phải là kiểu chữ, không chứa chữ số hoặc các ký tự đặc biệt.</div>');
			$('#txtname').focus();
			return false;
		}else if(name.length < 2 || name.length > 50) {
			$('.err_mess').html('<div class="alert alert-danger">Họ tên phải từ 3 đến 50 ký tự</div>');
			$('#txtname').focus();
			return false;
		}

		if(gender == '') {
			$('.err_mess').html('<div class="alert alert-danger">Vui lòng chọn giới tính của bạn</div>');
			return false;
		}

		if(phone != '' && checkPhone(phone) == false) {
			$('.err_mess').html('<div class="alert alert-danger">Số điện thoại không đúng định dạng</div>');
			$('#txtphone').focus();
			return false;
		}
		if(email != '' && checkEmail(email) == false) {
			$('.err_mess').html('<div class="alert alert-danger">Email không đúng định dạng</div>');
			$('#txtemail').focus();
			return false;
		}
		return true;
	});

	function add_member_child(parent=""){
		$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
		$('#myModalPopup .modal-dialog').addClass('modal-md');
		$('#myModalPopup .modal-title').html("Liên kết tài khoản con");

		var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_add_member_child.php";
		$.post(url, {"parent": parent},function(req){
			$('#modal-content').html(req);
			$('#myModalPopup').modal('show');
		});
	}

	function create_member_child(parent=""){
		$('#myModalPopup .modal-dialog').removeClass('modal-xs modal-sm modal-md modal-lg modal-xl');
		$('#myModalPopup .modal-dialog').addClass('modal-md');
		$('#myModalPopup .modal-title').html("Tạo tài khoản con");

		var url = "<?php echo ROOTHOST;?>ajaxs/member/frm_create_member_child.php";
		$.post(url, {"parent": parent},function(req){
			$('#modal-content').html(req);
			$('#myModalPopup').modal('show');
		});
	}
</script>
<?php unset($obj); ?>