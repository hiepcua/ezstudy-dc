
<div class="lession-page">
	<div class="card">
		<div class="card-block card-subject text-center">
			<div class="row row-item">
			<?php
			$type_user = getInfo('utype');
			if($type_user=='chame'){
				$arr=getMemberOfPhuHuynh($username);
				$grade='';
				$n=count($arr);
				if($n>0){
					foreach($arr as $key=>$r){
						if($n==1) $grade=$key.",";
						else $grade.="'".$key."',";
					}
				}
				$grade=substr($grade,0,-1);
			}else $grade=getInfo('grade');
			$_Product=api_get_product($grade);
			if(count($_Product)>0){
				foreach ($_Product as $key => $value) {
					$mon=isset($_Conf_Subjects[$value['subject']]) ? $_Conf_Subjects[$value['subject']]['name']:"";
					$level=$value['teacher_level'];
					$price=$value['price'];
					$from_date=date('d-m-Y',$value['from_date']);
					?>
					<div class="col-md-3 col-xs-6 col-item">
					<div class="item">
						<div onclick="buy_course('<?php echo $key;?>')" class="card course-card  p-0 shadow-xss border-0 rounded-lg overflow-hidden mr-1 mb-4">
							<div class="card-image w-100 mb-3">
								<span class="video-bttn position-relative d-block">
								<img src="<?php echo ROOTHOST;?>images/333.jpg" alt="image" class="w-100">
								</span>
							</div>
							<div class="card-body pt-0">
								<span class="font-xsssss mon fw-700 pl-3 pr-3 lh-32 text-uppercase rounded-lg ls-2 alert-warning d-inline-block text-warning mr-1"><?php echo $mon; ?></span>
								<span class="font-xss gia fw-700 pl-3 pr-3 ls-2 lh-32 d-inline-block text-success float-right"><?php echo number_format($price); ?>đ</span>
								<h4 class="name fw-700 font-xss mt-3 lh-28 mt-0"><a href="default-course-details.html" class="text-dark text-grey-900"><?php echo $value['name']; ?></a></h4>
								<h6 class="font-xssss num-lesson text-grey-500 fw-600 ml-0 mt-2"> 32 Lesson </h6>
								<span class="price text-success"><?php echo number_format($price); ?>đ</span>
								<div class="text-center">
									<a href="#" class="btn btn-primary">Đăng ký ngay</a>
								</div>
							</div>
						</div>
					</div>
					</div>
					
					<?php
				}
			}
			?>	
		
				
			</div>
		</div>
	</div>
</div>
<script>
function buy_course(id){
	$('#myModalPopup').modal('show');
	$('#myModalPopup .modal-dialog').addClass('modal-lg');
	var label='Mua dịch vụ';
	$('#myModalPopup .modal-title').html(label);
	var url = "<?php echo ROOTHOST;?>ajaxs/register_course/frm_buycourse.php";
	$.post(url,{id},function(req){
		$('#modal-content').html(req);
	});
}
</script>