<?php
function isSSL(){
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return true;
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') return true;
	else return false;
}
$REQUEST_PROTOCOL = isSSL()? 'https://' : 'http://';

define('ROOTHOST',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('WEBSITE',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/');
define('ROOTHOST_PATH',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/tooltracnghiem/');
define('EXAM_PATH',$REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'].'/toolexam/');
define('ROOTHOST_ADMIN',ROOTHOST);
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].'/');
// ---------------DEFINE CONSTANT API----------------
define('APP_ID','1663061363962371');
define('APP_SECRET','dd0b6d3fb803ca2a51601145a74fd9a8');

define('CB_SECRET','Rp7qqRgxQtJaPbupWD4VSDjn0tm4ccZZ');
define('CB_APIKEY','TlMKSzz8D6mAtcFg');

define('PIT_API_KEY','6b73412dd2037b6d2ae3b2881b5073bc');
define('PACKET_NAME','Gói tài khoản Standard');

define('API_DC_URL','https://dc.ezstudy.vn/api/');
define('URL_ROOTHOST_VIDEO','https://dc.ezstudy.vn/');
define('PARTNER_CODE','vstudy1.1');
define('KEY_AUTHEN_COOKIE','BNB_260584');
// Member register
define('API_MEMBER_LOGIN',API_DC_URL.'member-login'); 
define('API_MEMBER_REGISTER',API_DC_URL.'member-regiter'); 
define('API_JOIN_ROOM',API_DC_URL.'join_room'); 

// Member
define('API_GET_MEMBER',API_DC_URL.'list-member');
define('API_MEMBER_EDIT',API_DC_URL.'member-edit'); 
define('API_MEMBER_UPDATE',API_DC_URL.'app/member-edit'); 
define('API_MEMBER_UPDATE_INFO',API_DC_URL.'member-update-info'); 
define('API_MEMBER_INFO',API_DC_URL.'member-info'); 
define('API_MEMBER_CHILD',API_DC_URL.'list-member-child'); 
define('API_MEMBER_LEARNING_HISOTY',API_DC_URL.'member-learning-history'); 
define('API_CHECK_SERVICE_ORDER',API_DC_URL.'check-request-service-order'); 

// List Packet
define('API_PACKET',API_DC_URL.'list-packet'); 

// Dịch vụ (service)
define('API_SERVICE_ADD_ORDER',API_DC_URL.'add-order-service'); 
define('API_SERVICE_LIST_ORDER',API_DC_URL.'list-order-service'); 

//Danh sách khối lớp
define('API_GRADE',API_DC_URL.'grade'); 		
//Danh sách bộ sách (version)
define('API_GRADE_VERSION',API_DC_URL.'grade-version'); 
//Danh sách môn học
define('API_SUBJECT',API_DC_URL.'subject');
//Danh sách bài học
define('API_LESSION',API_DC_URL.'list-lesson'); 	
define('API_LESSION_SUBJECT',API_DC_URL.'lesson-subject'); 	
//Bài học chi tiết
define('API_LESSION_DETAIL',API_DC_URL.'lesson-detail'); 

define('API_LESSION_EXERCISE',API_DC_URL.'lesson-exercise'); 	
define('API_LESSION_VIDEO',API_DC_URL.'lesson-video'); 	
//Nhiệm vụ học tập
define('API_NHIEMVU',API_DC_URL.'list-work'); 	
define('API_NHIEMVU_BONNUS',API_DC_URL.'list-work-bonus'); 	
//Tạo nhiệm vụ học tập
define('API_NHIEMVU_ADD',API_DC_URL.'create-work'); 	
//process nộp bài
define('API_SAVEWORK',API_DC_URL.'save-work'); 	
		
 			
define('API_LIST_PACKET',API_DC_URL.'list-packet-histories'); 			
define('API_REGISTER_SERVICE',API_DC_URL.'save-service'); 					
define('API_EXTEND_EZ1',API_DC_URL.'extend-ez1'); 		
define('API_PACKET_CONFIG',API_DC_URL.'list-packet'); 		
define('API_EXAM_GROUP',API_DC_URL.'list-exam-group'); 		
define('API_LIST_EXAM',API_DC_URL.'list-exam'); 		
define('API_MEMBER_SERVICE',API_DC_URL.'member-service'); 		
define('API_MEMBER_SERVICE_ORDER',API_DC_URL.'member-service-order'); 		
define('API_SERVICE',API_DC_URL.'list-service'); 		
define('API_NOTIFI',API_DC_URL.'list-notifi'); 		
define('API_MESSENGER',API_DC_URL.'list-messenger'); 		
define('API_MESSENGER_PHUHUYNH',API_DC_URL.'list-messenger-phuhuynh'); 		
define('API_LIVE_FREE',API_DC_URL.'get_schedule_live_free'); 		
define('API_LIVE_FREE_REGISTED',API_DC_URL.'get_live_free_registed'); 		
define('API_LIVE_FREE_CUR_DAY',API_DC_URL.'get_live_free_cur_day'); 		
define('API_ISREAD_NOTIFI',API_DC_URL.'isread-notifi'); 



define("API_ADD_CHAT", API_DC_URL."add-chat");
define("API_GET_CHAT", API_DC_URL."list-chat");
define("API_ADD_CHAT_TOPIC", API_DC_URL."add-chat-topic");
define("API_GET_CHAT_TOPIC", API_DC_URL."list-chat-topic");
define("API_GET_MEMBER_INFO", API_DC_URL."get_member_info");
define("API_GET_TASK_MEMBER_WORK1", API_DC_URL."get_task_member_work1");

//Get nhận xét của giáo viên
define("API_GET_REVIEW", API_DC_URL."list-review-member");


//Chat phuhuynh-giaovien
define("API_ADD_MESSENGER", API_DC_URL."add-messenger");
define("API_GET_MESSENGER", API_DC_URL."list-messenger-chat");
define("API_GET_TEACHER", API_DC_URL."get-teacher-member");


// CODE NEW VINH
define("API_GET_LIST_SAN_PHAM", API_DC_URL."get_list_product");
define("API_PUSH_DON_HANG", API_DC_URL."save_register_product");
define("API_GET_LIST_REGISTER_PRODUCT", API_DC_URL."get_list_register_product");
define("API_GET_LIST_STATUS_OF_ORDER", API_DC_URL."get_list_status_of_order");


define("API_ADD_QUESTION_TO_TEACHER", API_DC_URL."create_question_answers");
define("API_ADD_SUB_QUESTION_TO_TEACHER", API_DC_URL."create_sub_question");

define("API_GET_LIST_QUESTION", API_DC_URL."list_question_by_student");
define("API_GET_LIST_QUESTION_BY_ID", API_DC_URL."list_question_by_id");
define("API_GET_LIST_MON", API_DC_URL."get_mon_hoc");


#Report khung nhiệm vụ học tập của 1 học sinh
define("API_GET_LIST_REPORT_KHUNG_NV", API_DC_URL."report_khung_nv/get_list");
define("API_GET_DETAIL_KHUNG_NV", API_DC_URL."report_khung_nv/get_detail");
define("API_COUNT_KHUNG_NV", API_DC_URL."report_khung_nv/get_count");
#end Report khung nhiệm vụ học tập của 1 học sinh

define("API_GET_METHOD_PAYMENT", API_DC_URL."get_method_payment");



define('ROOT_PATH',''); 
define('CONFIG_PATH',ROOT_PATH.'global/libs/');
define('TEM_PATH',ROOT_PATH.'templates/');
define('COM_PATH',ROOT_PATH.'components/');
define('MOD_PATH',ROOT_PATH.'modules/');
define('INC_PATH',ROOT_PATH.'includes/');
define('LAG_PATH',ROOT_PATH.'languages/');
define('EXT_PATH',ROOT_PATH.'extensions/');
define('EDI_PATH',EXT_PATH.'editor/');
define('DOC_PATH',ROOT_PATH.'documents/');
define('DAT_PATH',ROOT_PATH.'databases/');
define('IMG_PATH',ROOT_PATH.'images/');
define('MED_PATH',ROOT_PATH.'media/');
define('LIB_PATH',ROOT_PATH.'libs/');
define('URLEDITOR',ROOTHOST.'');
define('BASEVIRTUAL0',ROOTHOST.'images/');

define('MAX_ROWS',100);
define('ADMIN_LOGIN_TIMEOUT',-1);
define('URL_REWRITE','1');
define('USER_TIMEOUT',6000);
define('MEMBER_TIMEOUT',10000);
define('ACTION_TIMEOUT',600);
define('MEMBER_STATUS',1);
define('MEMBER_ROOT','');
define('MAX_CHILD',10);
define('EXPIRE_TIME',1296000); // 15 ngày chuyển về kiểu time
define('EXPIRE_DAY',15); // 15 ngày hết hạn dùng thử
define('MIN_DAY',365); //trước khi hết hạn sẽ show button gia hạn

define('SMTP_SERVER','smtp.gmail.com');
define('SMTP_PORT','465');
define('SMTP_USER','hoangtucoc321@gmail.com');
define('SMTP_PASS','Nsn2651984');
define('SMTP_MAIL','hoangtucoc321@gmail.com');	
define('SMTP_PASS_AUT','mrofafusrhipmbol');

define('MAIL_ADMIN','hoangtucoc321@gmail.com');
define('COMPANY_NAME','EZ STUDY');

$_HOST_LIST=array('dc.ezstudy.vn');
$_PERMISS_GROUP = array("G01","G05");
$_FILE_TYPE=array('docx','excel','pdf');
$_MEDIA_TYPE=array('mp4','mp3');
$_IMAGE_TYPE=array('jpeg','jpg','gif','png');
$_SCHOOL_GROUP=array('DH'=>'Đại Học','CD'=>'Cao Đẳng','TC'=>'Trung cấp','NG','Nghề');
$PERMISSION_ACCESS = array('G04','G01');
$PERMISSION_FULL = array('G04_TP','G04_PP','G01_TP','G01_NV');

$_AccountType = array(
	"chame" 	=> "Phụ huynh",
	"hocsinh" 	=> "Học sinh",
);
$_Grade = array(
	"K01" => "Lớp 1",
	"K02" => "Lớp 2",
	"K03" => "Lớp 3",
	"K04" => "Lớp 4",
	"K05" => "Lớp 5",
	"K06" => "Lớp 6",
	"K07" => "Lớp 7",
	"K08" => "Lớp 8",
	"K09" => "Lớp 9",
	"K10" => "Lớp 10",
	"K11" => "Lớp 11",
	"K12" => "Lớp 12",
);
$_PACKET_STATUS=array(
	'running'=>"Đang hoạt động",
	'pending'=>"Tạm dừng",
	'expire'=>"Hết hạn",
	'lock'=>"Tạm khóa",
);
$_Conf_ClassType = array(
	"C03" => "Nhóm 3",
	"C05" => "Nhóm 5",
	"C10" => "Nhóm 10",
);

$_Conf_LevelQuiz = array(
	"L01" => "Hiểu",
	"L02" => "Nâng cao",
	"L03" => "Khó",
);
$_Parket = array(
	"P01" => "Gói 1 tháng",
	"P03" => "Gói 3 tháng",
	"P06" => "Gói 6 tháng",
	"P12" => "Gói 12 tháng",
);
$_Conf_TeacherType = array(
	"T01" => "Giáo viên",
	"T02" => "Sinh viên",
	"T03" => "Giáo viên giỏi",
	"T04" => "Sinh viên đạt giải",
);
$_Conf_Payment = array(
	"P01" => "Vas",
	"P02" => "Chuyển khoản",
);
$_Conf_Nhiemvungay = array(
	"N01" => array("name"=>"Đăng nhập","star"=>1, 'diamon'=>5),
	"N02" => array("name"=>"Làm 1 bài tập","star"=>5, 'diamon'=>10),
	"N03" => array("name"=>"Hoàn thành nhiệm vụ","star"=>10, 'diamon'=>50),
	"N04" => array("name"=>"Hoàn thành xuất sắc","star"=>20, 'diamon'=>100),
);
$_Conf_Subjects = array(
	"M01" => array("name"=>"Toán","icon"=>"toan"),
	"M02" => array("name"=>"Tiếng Việt","icon"=>"tiengviet"),
	"M03" => array("name"=>"Văn học","icon"=>"vanhoc"),
	"M04" => array("name"=>"Vật lý","icon"=>"vatly"),
	"M05" => array("name"=>"Hóa học","icon"=>"hoahoc"),
	"M06" => array("name"=>"Sinh học","icon"=>"sinhhoc"),
	"M07" => array("name"=>"Địa lý","icon"=>"dialy"),
);
$_Conf_thuong_dat_moc=array(
'10'=>array('num_star'=>2,'num_diamond'=>5),
'20'=>array('num_star'=>5,'num_diamond'=>20),
'40'=>array('num_star'=>10,'num_diamond'=>50),
'60'=>array('num_star'=>20,'num_diamond'=>100),
);


$_QuestionStatus = array(
	"L0" => "Đang mở",
	"L1" => "Đã trả lời",
	"L2" => "Đã đóng",
	"L3" => "Đã khóa"
);
$_QuestionStatusStyle = array(
	"L0" => "#10d876",
	"L1" => "#FE9431",
	"L2" => "#b20202",
	"L3" => "#000"
);


// report khung nhiệm vụ của học sinh
$_QuestionStatus = array(
	"L0" => "Đang mở",
	"L1" => "Đã trả lời",
	"L2" => "Đã đóng",
	"L3" => "Đã khóa"
);

$_KhungNhiemVuStatus = array(
	""=>"Chưa làm",
	"1" => "Chưa qua", //chỉ L2
	"2" => "Đã qua", //chỉ L2
);
$_KhungNhiemVuFlag = array(
	""=>"Not active",
	"L1" => "Chưa hoàn thành", // đang làm
	"L2" => "Hoàn thành",
);
$_KhungNhiemVuFlagStyle = array(
	""=>"#000",
	"L1" => "#3f88fa",
	"L2" => "#449d44",
);
// end report khung nhiệm vụ của học sinh
define('HOTLINE', "0988888888");
