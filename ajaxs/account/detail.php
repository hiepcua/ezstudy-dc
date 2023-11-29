<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'config_api.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');
if(isLogin()){
	$item_id=isset($_POST['item_id'])? addslashes($_POST['item_id']):'';
	$rs = sysGetList("ez_member",array(), " AND username='".$item_id."'"); 				
	if(count($rs)>0) {
		$row=$rs[0];
		$label=$row['utype']=='chame'? 'Phụ huynh':'Học sinh';
		$class_notic='';
		$status='Chưa active';
		$username=$row['username'];
		$packet=api_get_service($username);				
		$end_time=(int)$row['edate'];
		
		if($end_time==''){
			$label_end_time='N/A';
			$status='Chưa kích hoạt';
		}
		else {
			$label_end_time=date('d-m-Y', $end_time);
			$today=time();
			$notic_endtime=$today+DATE_EXTEND*24*60*60; // ngày dự kiến hết hạn +10
			$class_notic='';
			if($end_time <= $notic_endtime){
				$n=date('d', $notic_endtime-$end_time)+1;
				$status='<p>Sắp hết hạn</p> (Còn '.$n.' ngày)';
				$class_notic='notic';
			}
			elseif($today > $end_time){
				$status='Đã hết hạn';
				$class_notic='notic';
			}
			else $status='Đang sử dụng';
		}
		?>
		<div class="detail-account">
		<div class="box">
		<h4 class="name">Thông tin tài khoản</h4>
			<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					Username: <strong class="pull-right"><?= $row['username'];?></strong>
				</div>
				
				<div class="form-group">
					Loại TK: <strong class="pull-right"><?= $label;?></strong>
				</div> 
			</div> 
			<div class="col-md-6">
			<div class="form-group">
				Join date: <strong class="pull-right"><?php echo date('d-m-Y',$row['cdate']);?></strong>
			</div>
			<div class="form-group">
				Ngày hết hạn: <strong class="pull-right"><?php echo $label_end_time;?></strong>
			</div>
			<div class="form-group">
				 Trạng thái:
				<label class="switch text-right pull-right"><?php echo $status;?></label>
			</div>
			</div>
			</div> 
			
		 <hr>
		 <div class="box">

			 <h4 class="name">Thông tin Cá nhân</h4>
			 <div class="row">
			<div class="col-md-6">
			 <div class="form-group">
					Fullname: <strong class="pull-right"><?= $row['fullname'];?></strong>
				</div>
			 <div class="form-group">
					Phone: <strong class="pull-right"><?= $row['phone'];?></strong>
				</div> 
			</div> 
			<div class="col-md-6">
				<div class="form-group">
					Email: <strong class="pull-right"><?= $row['email'];?></strong>
				</div> 
				 <div class="form-group">
					Lớp ĐK: <strong class="pull-right"><?= $row['grade'];?></strong>
				</div> 
			</div>
			</div>
			<hr>
			 <div class="box">

			 <h4 class="name">Thông tin Dịch vụ</h4>
			 <?php if(count($packet)>0){?>
				<table class="table tbl-main">
					<thead>
						<tr>
							<th class="text-center mhide" width="50">STT</th>
							<th>Dịch vụ</th>
							<th class="text-left mhide">Ngày đăng ký</th>
							<th class="text-left mhide">Ngày hết hạn</th>
							<th class="text-center mhide">Trạng thái</th>
							
						</tr>
					</thead>
					<tbody>
					<?php 
					
						foreach($packet as $key=>$vl){
						
							$start_time=$vl['start_time'];
							if($start_time=='') $start_time='-';
							$end_time=$vl['end_time'];
							if($end_time=='') $end_time='-';
							if($vl['status']=='L0') $label='Chưa thanh toán';
							else if ($vl['status']=='L1') $label='Đang sử dụng';
							else  $label='Đã hủy';
							?>
							<tr>
							<td class="text-center mhide"><?php echo $stt;?></td>
							<td class="text-center mhide"><?php echo $key;?></td>
							<td class="text-center mhide"><?php echo $start_time;?></td>
							<td class="text-center mhide"><?php echo $end_time;?></td>
							<td class="text-center mhide"><?php echo $label;?></td>
							
							</tr>
						<?php 
						}
					?>
				</table>
			<?php }
			else{
				echo '<div class="text-center"><h4>Tài khoản này chưa đăng ký dịch vụ!</h4>';
				echo '<a class="btn btn-success" href="'.ROOTHOST.'buy-package">Đăng ký ngay</a></div>';
			}
			?>
			</div>
		<div class="clearfix"></div>
		</div>
	
		
		<?php 
    }
    }

else{
    die('Vui lòng đăng nhập hệ thống');
}
?>

