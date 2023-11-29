<?php
function isLogin(){
	if(isset($_SESSION['USER_LOGIN']) && $_SESSION['USER_LOGIN']['islogin']){
		/* $user=getInfo('username');
		if(checkExpires($user)===true) return false; */
		return true;
	}
	return false;
}
function getSessionLogin(){
	if(isset($_SESSION['USER_LOGIN'])){
		return $_SESSION['USER_LOGIN'];
	}
	return null;
}
function setSessionLogin($data){
	if(is_array($data)){ $_SESSION['USER_LOGIN']=$data;}
	else {$_SESSION['USER_LOGIN']=null;}
}
function getInfo($field){
	$info=isset($_SESSION['USER_LOGIN'][$field])?$_SESSION['USER_LOGIN'][$field]:'';
	return $info;
}
function setInfo($field,$val){
	if(isset($_SESSION['USER_LOGIN']))$_SESSION['USER_LOGIN'][$field]=$val;
}
function checkExpires($user){
	// get session login
	$now=time();
	if(isset($_SESSION['USER_LOGIN']) && $now-$_SESSION['USER_LOGIN']['action_time']>=ACTION_TIMEOUT){
		$obj=new CLS_MYSQL;
		$sql="SELECT session FROM ez_member_login WHERE username='$user' AND status=1 ORDER BY id DESC";
		$obj->Query($sql);
		if($obj->Num_rows()>0){
			$r=$obj->Fetch_Assoc();
			if($_SESSION['USER_LOGIN']['session']!=$r['session']){
				LogOut($user);
				return true;
			}
		}else{
			die('Check Expire error. Please contact administrator!');
		}
	}
	// check time out login
	if(isset($_SESSION['USER_LOGIN']) && $now-$_SESSION['USER_LOGIN']['action_time']>=MEMBER_TIMEOUT){
		LogOut();
	}
	return false;
}
function LogIn($user,$pass){
	$arr = array('status'=>'no', 'data'=>null);
	if($user==''||$pass=='') return $arr;
	$fields = array();
	$obj = new CLS_MYSQL;
	if(SysCount("ez_member","AND username='".$user."' AND status='yes'")!=1) return $arr;
	$r = SysGetList("ez_member",$fields,"AND username='".$user."' AND status='yes'");
	if($r[0]['password']!=$pass) return $arr;
	$arr['status'] = 'yes';
	$arr['data'] = $r[0];
	return $arr;
}
function LogOut($user){
	if(isset($_SESSION['USER_LOGIN'])){
		unset($_SESSION['USER_LOGIN']);
		$sql="UPDATE ez_member_login SET status=0 WHERE username='$user'";
		$obj=new CLS_MYSQL;
		$obj->Exec($sql);
	}
}

// Generate token
function getToken($length){
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet.= "0123456789";
	$max = strlen($codeAlphabet);

	for ($i=0; $i < $length; $i++) {
		$token .= $codeAlphabet[random_int(0, $max-1)];
	}

	return $token;
}
function getPacketMember($username=''){
	if($username=='') $username = getInfo('username'); 
	$rs=SysGetList('ez_member', array()," AND username='$username'");
	$edate=isset($rs[0])? $rs[0]['edate']:'';
	$today = time();
	if( $edate!='' && $today <= $edate) return $edate;
	return '';
}
function checkPacketMember($show='', $username=''){
	// Get member info
	$json = array();
	$json['key'] = PIT_API_KEY;
	$json['username'] = $username;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_MEMBER_INFO, json_encode($post_data)); 
	$member = array();
	if(is_array($rep['data']) && count($rep['data']) > 0) {
	    $member = $rep['data'];
	}
	
	$rs = $member;
	$edate = isset($rs['edate'])? $rs['edate']:'';
	$today = time();
	if($edate==''){
		if($show==true){
			echo "<div class='notic-expire'>";
			echo "<div class='label-title'>Mời trải nghiệm dịch vụ</div>";
			echo "<p class='txt'>Bạn đang sử dụng tài khoản miễn phí. Hãy đăng ký <span class='color-label'>".PACKET_NAME."</span> để có thể làm nhiệm vụ học tập, làm bài kiểm tra và nhận thưởng sao, kim cương nhé!</p>";
			echo '<a href="#" class="btn btn-success btn-act" onclick="frm_packet(\'EZ1\',2)"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Đăng ký ngay</a>';
			echo '</div>';
		}
		return false;
	}
	if( $edate!='' && $today > $edate){
		if($show==true){
			echo "<div class='notic-expire  red text-center'>";
			echo "<div class='label-expire'>Tài khoản đã hết hạn. Vui lòng nạp tiền vào tài khoản </div>";
			echo '<a href="#" class="btn btn-success" onclick="frm_packet(\'EZ1\',1)">Click vào đây để gia hạn tài khoản</a>';
			echo '</div>';
		}
		return false;
	}
	return true;		
}
function joinInAccount($to_user, $type=''){//$type==1 là trở về tk chame
	$this_user = getInfo('username');
	$strwhere='';
	if($type=='') $strwhere=" AND par_user='".$this_user."'";
	$r = SysGetList("ez_member",array()," AND username='".$to_user."' $strwhere");
	if(!isset($r[0])) return false;
	$_SESSION['USER_LOGIN']=$r[0];
	$_SESSION['USER_LOGIN']['islogin']=true;
	$_SESSION['USER_LOGIN']['packet_ez']=array();
	if($type=='') $_SESSION['USER_JOININ']=$r[0];
	else{
		if(isset($_SESSION['USER_JOININ'])) unset($_SESSION['USER_JOININ']);
	}
	return true;		
}

function get_bonus_histories($username,$bonus_configid,$strwhere){//kt nhiệm vụ đạt đc hay chưa
	$count=SysCount('ez_wallet_histories', " AND username='$username' AND bonus_configid='$bonus_configid' $strwhere");
	return $count;
}
function get_url_nv($_Nhiemvu, $type,$type_tbl, $title, $bonus_configid){
	$url_page='';
	$arr=array();
	if(isset($_Nhiemvu) && is_array($_Nhiemvu)){
		foreach($_Nhiemvu as $v) { 
			foreach($v as $vl) { 
				if($type_tbl==1) $contents=$vl['contents'];
				else $contents=$vl['content'];
				if($contents=='') continue;
				if($vl['status']!='') continue;
				if($vl['work_type']!='live'){
					$arr[]=$vl;
				}
			}
		}
	}
	
	if(isset($arr[0])){
		$item=$arr[0];
		if($type_tbl==1) $url_page=ROOTHOST."tool-work/".$item['id'];
		else $url_page=ROOTHOST."tool-work-config/".$item['id'];
	}
	
	if($type==2 && $url_page!='') $btn='<a href="'.$url_page.'" target="_brank" class="btn btn-more">Thực hiện <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
	else if($type==3) $btn='<span onclick="frm_subject_nv(1, '.$bonus_configid.')" class="btn btn-more">Thực hiện <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
	else if($type==4) $btn='<span onclick="frm_subject_nv(2, '.$bonus_configid.')" class="btn btn-more">Thực hiện <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
	else $btn='<span onclick="show_upgrade_notify_popup()" class="btn btn-more">Thực hiện <i class="fa fa-arrow-right" aria-hidden="true"></i></span>';
	return $btn;
}
/*
function getMember($username=''){
	$strwhere='';
	if($username!='') $strwhere.=" AND username='$username'";
	$r=SysGetList('ez_member', array(),"$strwhere");
	if(!isset($r[0])) return array();
	return $r[0];
}
function getChildMember(){
	$username=getInfo('username');
	$acc_child = SysGetList("ez_member",array(), " AND par_user='".$username."' ORDER BY cdate DESC"); 	
	$str_acc_child='';
	foreach($acc_child as $item) { 
		$stt++;
		$username=$item['username'];
		$str_acc_child.="'".$username."',";
	}
	$str_acc_child=substr($str_acc_child,0,-1);
	return $str_acc_child;
}
function getMemberOfPhuHuynh($username){
	$sql="SELECT DISTINCT grade,username FROM ez_member WHERE grade!='' AND grade_version!='' AND par_user='$username'"; 	
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$arr=array();
	while($r=$obj->Fetch_Assoc()){
		$grade=$r['grade'];
		$arr[$grade]=$r['username'];
	}
	return $arr;
}
*/
