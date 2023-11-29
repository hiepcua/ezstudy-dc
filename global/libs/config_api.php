<?php
//------------------------ GET VERSION MÔN HỌC -----------------------------
function api_get_version(){
	// Hiện tại lớp 1 và lớp 6 mới có phần lọc chọn bộ sách
	//if( in_array(getInfo('grade'),array("K01","K06")) ) {
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = getInfo('grade');
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GRADE_VERSION,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$_Grade_version = $rep['data'];
	}
	return $_Grade_version;
	//}
	return '';
}

//------------------------ GET MÔN HỌC -----------------------------
function api_get_subject($grade=''){
	$json = array();
	$json['key']   = PIT_API_KEY;
	if($grade!='') $json['grade'] = $grade;
	else $json['grade'] = getInfo('grade');
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_SUBJECT,json_encode($post_data));
	$_Subjects=array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$_Subjects = $rep['data'];
		$_SESSION['SUBJECT'] = $_Subjects;
	}
	return $_Subjects;
}
//------------------------ GET Thông báo từ tearcher-----------------------------
function api_get_mesenger($username='', $type=''){
	$json = array();
	$json['key'] = PIT_API_KEY;
	if($username=='') $json['username'] = getInfo('username');
	else $json['username'] = $username;
	if($type=='') $url = API_MESSENGER;
	else $url = API_MESSENGER_PHUHUYNH;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post($url,json_encode($post_data)); 
	//var_dump($json);
	$item=array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function api_get_notifi($username='', $id='',$page=1,$max_row=30,$get_total=false){//defalt api là 30
	$json = array();
	$json['key']   = PIT_API_KEY;
	if($username=='') $json['username'] = getInfo('username');
	else $json['username'] = $username;
	if($id!='') $json['id'] = $id;
	$json['page'] = $page;
	$json['max_row'] = $max_row;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_NOTIFI,json_encode($post_data)); 	
	
	$item=array();
	$count=0;
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
		if($get_total==true) return $rep['total_rows'];
	}
	return $item;
}
function api_get_service($type='',$username=''){
	$json = array();
	$json['key']   = PIT_API_KEY;
	if($username=='') $json['username'] = getInfo('username');
	else $json['username'] = $username;
	$json['type'] = $type;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_SERVICE,json_encode($post_data)); 
	$item=array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}
function api_get_service_member($type='',$username=''){
	$json = array();
	$json['key']   = PIT_API_KEY;
	if($username=='') $json['username'] = getInfo('username');
	else $json['username'] = $username;
	$json['type'] = $type;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_SERVICE,json_encode($post_data)); 
	$item=array();
	if(is_array($rep['data1']) && count($rep['data1']) > 0) {
		$item = $rep['data1'];
	}
	return $item;
}
function api_get_packet_service(){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_PACKET,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}
function api_get_live_free($grade='',$subject='',$teacher='',$live_free_id='',$inday=''){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['live_free_id'] 	= $live_free_id;
	$json['teacher']   		= $teacher;
	$json['grade']   		= $grade;
	$json['subject']   		= $subject;
	$json['inday']   		= $inday;

	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LIVE_FREE,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function getDataNhiemVu($strwhere,$type_tbl, $type_obj=false){//type_obj= true thì trả ra object
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = getInfo('username');
	$json['strwhere'] 	= addslashes($strwhere);
	$json['grade'] 	= getInfo('grade');
	$json['type_tbl'] 	= $type_tbl;
	if($type_tbl!=1) $json['flag'] 	= 'L1';
	$version = getInfo('grade_version');
	$json['version'] 	= $version == "N/a" ? $json['grade']."_V01" : $version;
	$arr_sub = array();
	$subject_list = getInfo('subject_list');
	$arr_subject_list = explode(',', $subject_list);
	foreach ($arr_subject_list as $key => $value) {
		$item = explode('_', $value);
		$arr_sub[] = $item[1];
	}
	$str_sub = implode(",", $arr_sub);
	$json['subject'] 	= $str_sub;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_NHIEMVU,json_encode($post_data)); 
	if($rep['status'] == "yes"){
		if($type_obj==true) $_Nhiemvu=$rep['data_obj'];
		else $_Nhiemvu=$rep['data'];
	}else $_Nhiemvu=array();
	return $_Nhiemvu;
}
function getDataNhiemVuMember($username,$grade,$version,$type_tbl){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$json['grade'] 	= $grade;
	$json['type_tbl'] 	= $type_tbl;
	if($type_tbl!=1) $json['flag'] 	= 'L1';
	$version = $version;
	$json['version'] 	= $version == "N/a" ? $json['grade']."_V01" : $version;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_NHIEMVU,json_encode($post_data)); 
	if($rep['status'] == "yes"){
		if($type_obj==true) $_Nhiemvu=$rep['data_obj'];
		else $_Nhiemvu=$rep['data'];
	}
	else $_Nhiemvu=array();
	return $_Nhiemvu;
}
function getLesson($subject){
	$lesson=$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] 		= getInfo('grade');
	$json['subject'] = $subject;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$lesson = $rep['data'];
		
	}
	return $lesson;
}
function getLessonSubject($subject){
	$lesson=$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] 		= getInfo('grade');
	$json['subject'] = $subject;
	$json['version'] = getInfo('grade_version');
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_LESSION_SUBJECT,json_encode($post_data));
	
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$lesson = $rep['data'];
	}
	return $lesson;
}
function api_get_packet($type){ //1 là gói account, 2 là dịch vụ gv hướng dẫn
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['grade'] = getInfo('grade');
	$json['type'] = $type;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_PACKET_CONFIG,json_encode($post_data)); 

	$item=array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}
function api_get_product($grade=''){ 
	$json=array();
	$json['key']=PIT_API_KEY;
	if($grade!='') $json['grade']=$grade;
	$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
	$url=API_GET_LIST_SAN_PHAM;
	$reponse_data=Curl_Post($url,json_encode($post_data));
	if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
		$item=$reponse_data['data'];
	}
	return $item;
}
/*
function api_get_member_service($type=''){ //$type='' check có dv hay ko, type=1 là lấy ra list dv
	$arr_service=$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = getInfo('username');
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_SERVICE,json_encode($post_data));
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		foreach($rep['data'] as $vl){
			if($vl['status']=='L1') $arr_service[]=$vl;
		}
	}
	if($type!='') return $arr_service;
	if(count($arr_service)>0) return true;
	return false;
}
*/
function api_get_list_member($username="",$par_user="",$grade="",$partner_code="",$packet_status="",$saler="",$packet=""){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$json['par_user'] = $par_user;
	$json['grade'] = $grade;
	$json['partner_code'] = $partner_code;
	$json['packet_status'] = $packet_status;
	$json['saler'] = $saler;
	$json['packet'] = $packet;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_MEMBER,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function api_get_child_member($member){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_CHILD,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function api_get_member_info($member){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_INFO,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function api_get_teacher_member($member){ // Lấy list giáo viên của học sinh
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $member;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_TEACHER,json_encode($post_data)); 
	$item = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

function api_update_member($username, $arr = array()){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$json['arr'] = $arr;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_UPDATE_INFO,json_encode($post_data)); 
	if($rep['status']=="yes" && $rep['data']=="success") {
		return true;
	}
	return false;
}

// Lấy lịch sử học tập của học sinh
function api_get_member_learning_history($username="", $grade=""){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$json['grade'] = $grade;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_LEARNING_HISOTY,json_encode($post_data)); 
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

// Kiểm tra học sinh đã gửi yêu cầu đăng ký/nâng cấp dịch vụ hay chưa.
function api_check_service_order($username="", $packet=""){
	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['username'] = $username;
	$json['packet'] = $packet;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_CHECK_SERVICE_ORDER,json_encode($post_data)); 
	if(is_array($rep['data']) && count($rep['data']) > 0) {
		$item = $rep['data'];
	}
	return $item;
}

// Update is_read notify
function api_update_is_read_notifi($username='', $id=''){
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['id'] = $id;
	$json['username'] = $username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_ISREAD_NOTIFI,json_encode($post_data)); 	
	if($rep['status']=="yes" && $rep['data']=="success") {
		return "success";
	}
	return "error";
}