<?php /* ------------------ Gia sư ---------------*/ ?>
<div class="col-md-12 col-xs-12 online_item1">
	<h2 class="main-title">GIA SƯ CỦA TÔI <span class="number">3</span></h2>
	<div class="card"><div class="card-block">
		<!--<h2 class="main-title">GIA SƯ CỦA TÔI <span class="number">3</span></h2>-->
		<h3 class="main-desc text-center">Bạn có 3 buổi gia sư trong tuần này</h3>
		<div class="table-block">
			<div class="table-header">
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-2">Ngày</div>
					<div class="col-md-2">Lớp</div>
					<div class="col-md-2">Giờ</div>
					<div class="col-md-2">Thời gian</div>
					<div class="col-md-2"></div>
				</div>
			</div>
			<div class="table-body">
				<div class="table-item odd">
					<div class="row">
						<div class="col-md-2 text-center"><b>Thứ 2</b></div>
						<div class="col-md-2">09/08</div>
						<div class="col-md-2"><a href="#" class="label label-success">Toán</a></div>
						<div class="col-md-2"><b>10:00</b></div>
						<div class="col-md-2">30 phút</div>
						<div class="col-md-2 text-center"><a href="#" class="btn btn-default">Vào lớp</a></div>
					</div>
				</div>
				<div class="table-item even">
					<div class="row">
						<div class="col-md-2 text-center"><b>Thứ 5</b></div>
						<div class="col-md-2">11/08</div>
						<div class="col-md-2"><a href="#" class="label label-success">Tiếng Việt</a></div>
						<div class="col-md-2"><b>16:00</b></div>
						<div class="col-md-2">30 phút</div>
						<div class="col-md-2 text-center"><a href="#" class="btn btn-default">Vào lớp</a></div>
					</div>
				</div>
				<div class="table-item odd">
					<div class="row">
						<div class="col-md-2 text-center"><b>Thứ 6</b></div>
						<div class="col-md-2">12/08</div>
						<div class="col-md-2"><a href="#" class="label label-success">Tiếng Anh</a></div>
						<div class="col-md-2"><b>10:00</b></div>
						<div class="col-md-2">30 phút</div>
						<div class="col-md-2 text-center"><a href="#" class="btn btn-default">Vào lớp</a></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clearfix"><hr/></div>
		<h3 class="main-desc text-center">Đăng ký lớp gia sư mới</h3>
		<div class="row text-center">
			<?php for($i=0;$i<3;$i++) { ?>
			<div class="item col-md-4 col-xs-12">
				<div class="thumb">
					<a href="#">
						<img src="<?php echo ROOTHOST;?>images/baihoc/anh_unit1.png" class="img-responsive"/>
					</a>
					<div class="subject"><span>Ngoại ngữ</span></div>
				</div>
				<div class="title">Unit 1: Home Life</div>
				<a href="#" class="btn_dangky">Đăng ký học</a>
			</div>
			<?php } ?>
		</div>
	</div></div>
</div>