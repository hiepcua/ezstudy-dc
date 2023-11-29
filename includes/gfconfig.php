<?php
define("DIR_UPLOAD_FILE","uploads/");
$GLOBALS['ARR_HE'] = array(
	'01'=>"Tiểu học",'02'=>"Trung học cơ sở",'03'=>'Trung học phổ thông',
	'04'=>"TH & THCS", "05"=>"THCS & THPT"
);
$GLOBALS['GENDER_LABEL'] = array(
	'0'=>"Nữ",
	'1'=>"Nam"
);
$GLOBALS['ARR_LEVEL'] = array(
	'0'=>"Nhận biết",'1'=>"Thông hiểu",'2'=>"Vận dụng",'3'=>"Vận dụng cao"
);
$GLOBALS['TYPE_EDU'] = array(
	'PT'=>"Phổ thông",
	'BT'=>"Bổ túc",
);
$GLOBALS['TYPE_QUIZ'] = array(
	'0'=>"Câu hỏi 1 lựa chọn",
	'1'=>"Câu hỏi nhiều lựa chọn",
	'2'=>"Câu hỏi đúng sai"
);
$GLOBALS['QUIZ_ANSWER'] = array(
	'0'=>"A",'1'=>"B",
	'2'=>"C",'3'=>"D",'4'=>"E",
);
$GLOBALS['ARR_PTXT'] = array(
	'0'=>"Xét học bạ",
	'1'=>"Xét điểm thi tốt nghiệp THPT"
);
$GLOBALS['ARR_DIADIEM'] = array(
	'0'=>"Cơ sở 1",
	'1'=>"Cơ sở 2"
);
$GLOBALS['TRANGTHAI'] = array(
	'0'=>array("name"=>"Nháp","label"=>"label-info"),
	'1'=>array("name"=>"Xác nhận","label"=>"label-success"),
	'2'=>array("name"=>"Đã duyệt","label"=>"label-primary")
);
$GLOBALS['TRANGTHAI_HS'] = array(
	'0'=>"Đã bỏ học",
	'1'=>"Đang học",
	'2'=>"Bảo lưu"
);
$GLOBALS['TRANGTHAI_LABEL'] = array(
	'0'=>"label-danger",
	'1'=>"label-success",
	'2'=>"label-warning"
);

//PHAN  QUYEN
$GLOBALS['ARR_ACTION'] = array(
	'view'		=>array( "value" => 1, "name" => "Xem"),
	'add'		=>array( "value" => 2, "name" => "Thêm"),
	'edit'		=>array( "value" => 4, "name" => "Sửa"),
	'delete'	=>array( "value" => 8, "name" => "Xóa"),
	'accept'	=>array( "value" => 16,"name" => "Xác nhận"),
	'approved'	=>array( "value" => 32,"name" => "Duyệt")
);
$GLOBALS['ARR_COM'] = array(
	'config' 	=> array(
		"value" => 1,
		"name"	=> "Cấu hình"
	),
	'guser'		=>array(
		"value" => 2,
		"name"	=> "Nhóm quyền"
	),
	'user'		=>array(
		"value" => 4,
		"name"	=> "Quản trị viên"
	),
	'lesson'	=>array(
		"value" => 8,
		"name"	=> "Học liệu"
	),
	'exercise'	=>array(
		"value" => 16,
		"name"	=> "Bài học"
	),
	'teachers'	=>array(
		"value" => 32,
		"name"	=> "Giáo viên"
	),
	'orders'	=>array(
		"value" => 64,
		"name"	=> "Đơn hàng"
	),
	'subject'	=>array(
		"value" => 128,
		"name"	=> "Môn học"
	),
	'quiz' 	=>array(
		"value" => 256,
		"name"	=> "Ngân hàng câu hỏi"
	),
	'test'		=>array(
		"value" => 512,
		"name"	=> "Đề thi"
	),
	'exam'		=>array(
		"value" => 1024,
		"name"	=> "Ngân hàng đề"
	),
	'exam_result'=>array(
		"value" => 2048,
		"name"	=> "Kết quả thi"
	),
	'report'	=>array(
		"value" => 4096,
		"name"	=> "Thống kê - Báo cáo"
	),
	'products'	=>array(
		"value" => 8192,
		"name"	=> "Sản phẩm"
	)
);
$GLOBALS['MSG_PERMIS']='<div class="main-body" style="padding-top:45px"><div class="page-wrapper"><div class="page-body">
<div class="card"><div class="card-header">
<h4 align="center">Bạn không có quyền truy cập.</h4>
</div></div>
</div></div></div>';
?>