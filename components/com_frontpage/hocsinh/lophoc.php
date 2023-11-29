<?php /*------------------ Lớp học online ---------------*/ ?>
<div class="col-md-12 col-xs-12 online_item2">
	<h2 class="main-title">LỚP HỌC ONLINE <span class="number">5</span></h2>
	<div class="card"><div class="card-block">
		<h3 class="main-desc text-center">Lớp học trực tuyến đăng ký miễn phí hàng tuần</h3>
		<div id="online_slide" class="text-center owl-carousel">
			<?php for($i=0;$i<5;$i++) { ?>
			<div class="item">
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
		<div class="text-center">
			<a href="#" class="view-all">Xem tất cả <i class="fa fa-arrow-circle-right"></i> </a>
		</div>
	</div></div>
</div>
<script>
$(document).ready(function(){	
	// set 2 cột bằng nhau
	/*var highestBox = 0;
	$('.online_classes .card .card-block').each(function(){  
	   if($(this).height() > highestBox)
		  highestBox = $(this).height();  
	})  
    $('.online_classes .card .card-block').height(highestBox);*/
	// slide
	var owl1 = $("#online_slide");
	owl1.owlCarousel({
		margin: 10,
		autoPlay:true,
		responsiveClass: true,
		stopOnHover : true,
		smartSpeed: 3000,
		navigation: true,
		navigationText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
		dots: ($(".owl-carousel .item").length > 1) ? true: false,
		loop:($(".owl-carousel .item").length > 1) ? true: false,
		itemsCustom : [
			[0, 2],
			[450, 2],
			[768, 3],
			[992, 4],
			[1200, 4]
		]
	});
	/*$(".owl-prev").click(function(){
		owl1.trigger('owl.prev');
	})
	$(".owl-next").click(function(){
		owl1.trigger('owl.next');
	});*/
})
</script>