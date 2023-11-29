<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');

if(isset($_POST['username'])) {
	$username = isset($_POST['username'])?antiData($_POST['username']):'';
	$email = isset($_POST['email'])?antiData($_POST['email']):'';
	
	// check email
	$rs = sysGetList("ez_member",array('email','fullname')," AND username='$username'");
	if(!isset($rs[0])) die("empty"); // không có dữ liệu
	if($rs[0]['email'] == '') die("not_email"); // Chưa có thông tin email
	if($email != $rs[0]['email']) die("not_match"); // Email không khớp
	
	$fullname = $rs[0]['fullname'];
	
	// get email admin
	$email_admin = MAIL_ADMIN;
	
	require_once('../../extensions/phpmailer/class.phpmailer.php');
	require_once('../../extensions/phpmailer/class.smtp.php');
	
	// lấy nội dung gửi
	$body =file_get_contents('../../mail/change_pass.html');
	$body=str_replace('{fullname}',$fullname,$body);
	$body=str_replace('{username}',$username,$body);
	$newpass = rand_string(6); // mật khẩu ngẫu nhiên
	$body=str_replace('{newpass}',$newpass,$body);
	$body=str_replace('{email_admin}',$email_admin,$body);

	// cấu hình mail
	$nFrom = COMPANY_NAME;
	$mFrom =$email_admin;  		 //dia chi email cua ban 
	$mPass =SMTP_PASS_AUT;       //mat khau email cua ban
	$nTo = $fullname; 			 //Ten nguoi nhan
	$mTo = $email;   	 //dia chi nhan mail
	$mail= new PHPMailer();
	$title = "Yêu cầu đổi mật khẩu trên ".COMPANY_NAME;   //Tieu de gui mail
	$mail->isSMTP();     

	$mail->CharSet    = "utf-8";
	$mail->SMTPDebug  = 0;   			 // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;    		 // enable SMTP authentication
	$mail->Host       = "smtp.gmail.com";// sever gui mail.

	$mail->SMTPSecure = "tls";         	 //If SMTP requires TLS encryption then set it                  
	$mail->Port = 587;    				 //Set TCP port to connect to 
	
	// Gui mail
	$mail->Username   = $mFrom;  // khai bao dia chi email
	$mail->Password   = $mPass;  // khai bao mat khau
	$mail->SetFrom($mFrom, $nFrom);
	$mail->AddReplyTo($email_admin, COMPANY_NAME);
	$mail->Subject    = $title;  // tieu de email 
	$mail->MsgHTML($body);		 // noi dung chinh cua mail se nam o day.
	$mail->AddAddress($mTo, $nTo);
	//var_dump($mail);
	
	// thuc thi lenh gui mail 
	if(!$mail->Send()) {	
		//die('Error: '.$mail->ErrorInfo);
		die('error_send');
	} else {
		// cập nhật đổi mật khẩu
		$arr = array();
		$arr['password'] = hash('sha256', $username).'|'.hash('sha256', $newpass);		
		$result = SysEdit("ez_member",$arr," AND username='$username'");
		
		// chuyển dữ liệu member về DC
		$json = array();
		$json['key']   = PIT_API_KEY;
		$json['arr']   = $arr;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$req = Curl_Post(API_MEMBER_UPDATE_INFO,json_encode($post_data));
		
		die('success');
	}
}