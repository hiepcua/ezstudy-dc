//---------------- SHOW MESSAGE ---------------
function showMess(mess,type){
	var _title='';var _html="";
	switch(type){
		case 'error': 
			_title="<span>Lỗi</span>"; 
			_html="<p class='alert alert-danger'>"+mess+"</p>";
			break;
		case 'alert': 
			_title="<span>Cảnh báo</span>"; 
			_html="<p class='alert alert-warning'>"+mess+"</p>";
			break;
		default:  
			_title="<span>Thông báo</span>"; 
			_html="<p class='alert alert-info'>"+mess+"</p>";	
			break;
	}
	$('#myModal .modal-dialog').removeClass('modal-sm');
	$('#myModal .modal-dialog').removeClass('modal-lg');
	$('#myModal .modal-dialog').addClass('modal-notic');
	$('#myModal .modal-title').html(_title);
	$('#myModal .modal-body').html(_html);
    $('#myModal').modal('show');

}
function showForm(url,title,cls){
	$('#myModal .modal-dialog').removeClass('modal-sm');
	$('#myModal .modal-dialog').removeClass('modal-lg');
	$('#myModal .modal-dialog').addClass(cls);
	$('#myModal .modal-header .modal-title').html(title);
	$.post(url,function(req){
		$('#myModal .modal-body').html(req);
		$('#myModal').modal('show');
	})
}
function showFormWithParams(url,title,cls,data){
	$('#myModal .modal-dialog').removeClass('modal-sm');
	$('#myModal .modal-dialog').removeClass('modal-lg');
	$('#myModal .modal-dialog').addClass(cls);
	$('#myModal .modal-header .modal-title').html(title);
	$.post(url,data,function(req){
		$('#myModal .modal-body').html(req);
		$('#myModal').modal('show');
	})
}
function myModalhide() {
	$("#myModal").modal("hide");
}
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function tableHeadFixTop() {
	var tableOffset = $(".table").offset().top;
	var tableWidth = $(".table").width(); 
	var $header = $(".table > thead > tr").clone();
	var $fixedHeader = $("#header-fixed").append($header);

	$(window).bind("scroll", function() {
		var offset = $(this).scrollTop();
		
		if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
			$("#header-fixed").css({"width":tableWidth+"px"});
			$fixedHeader.show();
		}
		else if (offset < tableOffset) {
			$fixedHeader.hide();
		}
	});
}
function getListPro(cls,type,cat_id){
	var url = ROOTHOST+"ajaxs/product/list_product.php";
	$.post(url,{cat_id:cat_id,type:type},function(req){
		$("."+cls+" .list_product").html(req);
		$("."+cls+" .menu_selling a").each(function(){
			$(this).removeClass('active');
			var data = $(this).attr('dataitem');
			if(data==cls) $(this).addClass('active');
		})
	})
}
function getList5Pro(cls,type,cat_id){
	var url = ROOTHOST+"ajaxs/product/list_product.php";
	$.post(url,{cat_id:cat_id,type:type},function(req){
		$("."+cls+" .list_5product").html(req);
		$("."+cls+" .menu_selling a").each(function(){
			$(this).removeClass('active');
			var data = $(this).attr('dataitem');
			if(data==cls) $(this).addClass('active');
		})
	})
}
function QuantityMinus(){
	var sl = parseInt($('#add_number').val());
	if(sl>1) $('#add_number').val(sl-1);
	$(".product-desc .add-to-cart").attr("datanum",sl-1);
}
function QuantityPlus(){
	var sl = parseInt($('#add_number').val());
	if(sl<100) $('#add_number').val(sl+1);
	$(".product-desc .add-to-cart").attr("datanum",sl+1);
}
function changeQuantity() {
	var sl = parseInt($('#add_number').val());
	$(".product-desc .add-to-cart").attr("datanum",sl);
}
function formatNumber(nStr, decSeperate, groupSeperate) {
	nStr += '';
	x = nStr.split(decSeperate);
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
	}
	return x1 + x2;
}