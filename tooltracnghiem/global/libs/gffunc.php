<?php
function paging($total_rows,$max_rows,$cur_page){
    $max_pages=ceil($total_rows/$max_rows);
    $start=$cur_page-5; if($start<1)$start=1;
    $end=$cur_page+5;   if($end>$max_pages)$end=$max_pages;
    $paging='
    <form action="" method="post" name="frmpaging" id="frmpaging">
    <input type="hidden" name="txtCurnpage" id="txtCurnpage" value="1" />
    <ul class="pagination">';
    if($max_pages>1){
        $paging.='<li><a href="javascript:gotopage('.($cur_page-1).')"><span aria-hidden="true">&laquo;</span></a></li>';
        for($i=$start;$i<=$end;$i++)
        {
            if($i!=$cur_page)
                $paging.="<li><a href=\"javascript:gotopage($i)\"> $i </a></li>";
            else
                $paging.="<li class='active'><a href=\"javascript:gotopage($i)\" class=\"cur_page\"> $i </a></li>";
        }
    }
    if($cur_page <$max_pages)
        $paging.='<li><a href="javascript:gotopage('.($cur_page+1).')"><span aria-hidden="true">&raquo;</span></a></li>';

    $paging.='</ul></form>';
    echo $paging;
}
function isMobile(){
	if(preg_match("/(iPad)/i", $_SERVER["HTTP_USER_AGENT"])) return false;
	elseif(preg_match("/(iPhone|iPod|android|blackberry|Mobile|Lumia)/i", $_SERVER["HTTP_USER_AGENT"])) return true;
	else return false;
}
function un_unicode($str){
	$marTViet=array(
	'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
	'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
	'ì','í','ị','ỉ','ĩ',
	'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
	'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
	'ỳ','ý','ỵ','ỷ','ỹ',
	'đ',
	'A','B','C','D','E','F','J','G','H','I','K','L','M',
	'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
	'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
	'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
	'Ì','Í','Ị','Ỉ','Ĩ',
	'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
	'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
	'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
	'Đ',":",",",".","?","`","~","!","@","#","$","%","^","&","*","(",")","'",'"','&','/','|','   ','  ',' ','---','--');

	$marKoDau=array(
	'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
	'e','e','e','e','e','e','e','e','e','e','e',
	'i','i','i','i','i',
	'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
	'u','u','u','u','u','u','u','u','u','u','u',
	'y','y','y','y','y',
	'd',
	'a','b','c','d','e','f','j','g','h','i','k','l','m',
	'n','o','p','q','r','s','t','u','v','w','x','y','z',
	'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
	'e','e','e','e','e','e','e','e','e','e','e',
	'i','i','i','i','i',
	'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
	'u','u','u','u','u','u','u','u','u','u','u',
	'y','y','y','y','y',
	'd',"","","","","","","","","","","","","","",'','','','','','','',' ',' ','-','-','-');

	$str = str_replace($marTViet, $marKoDau, $str);
	return $str;
}
function Substring($str,$start,$len){
	$str=str_replace("  "," ",$str);
	$arr=explode(" ",$str);
	if($start>count($arr))	$start=count($arr);
	$end=$start+$len;
	if($end>count($arr))	$end=count($arr);
	$newstr="";
	for($i=$start;$i<$end;$i++)
	{
		if($arr[$i]!="")
		$newstr.=$arr[$i]." ";
	}
	if($len<count($arr)) $newstr.="...";
	return $newstr;
}
function encrypt($string,$key=KEY_AUTHEN_COOKIE){
	$iv = md5(uniqid(rand(), true));
	$salt =md5(uniqid(rand(), true));
 	$hashKey = hash('sha256', md5($key).'|'.$salt);
	$hashKey = substr($hashKey, 0, 16);
	$iv = substr($iv, 0, 16);
	$encryptedString = openssl_encrypt($string,'AES-128-CBC', $hashKey, 0, $iv);
	$encryptedString = base64_encode($encryptedString);
	$output = ['ciphertext' => $encryptedString, 'iv' => $iv, 'salt' => $salt];
	return base64_encode(json_encode($output));
}

function decrypt($encryptedString,$key=KEY_AUTHEN_COOKIE){
	$json = json_decode(base64_decode($encryptedString), true);
	try {
		$salt = $json["salt"];
		$iv = $json["iv"];
		$cipherText = base64_decode($json['ciphertext']);
	} catch (Exception $e) {
		return null;
	}
	$hashKey = hash('sha256', md5($key).'|'.$salt);
	$hashKey = substr($hashKey, 0, 16);
	$decrypted= openssl_decrypt($cipherText ,'AES-128-CBC', $hashKey, 0, $iv);
	return $decrypted;
}
function http_url($url){
	if(defined('SSL') && SSL==true) return str_replace('http://','https://',$url);
	else return str_replace('https://','http://',$url);
}
function optimizeHTML($Html) {
  $encoding = mb_detect_encoding($Html);
  $doc = new DOMDocument('', $encoding);
  @$doc->loadHTML('<html><head>'
    . '<meta http-equiv="content-type" content="text/html; charset='
    . $encoding . '"></head><body>'. trim($Html) . '</body></html>');
  $nodes = $doc->getElementsByTagName('body')->item(0)->childNodes;
  $html = '';
  $len = $nodes->length;
  for ($i = 0; $i < $len; $i++) {
    $html .= $doc->saveHTML($nodes->item($i));
  }
  return $html;
}
function antiData($data,$type='plaintext',$tag=false){
	if(empty($data)) return '';
	$data=preg_replace('/\s\s+/', ' ', $data);
	switch($type){
		case 'plaintext': 
			$data=addslashes(trim(strip_tags($data)));
			break;
		case 'html':
			// remove unopentag and unclosetags
			$data=optimizeHTML($data);
			// remove blank tags
			$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";
			$data= preg_replace($pattern,'',$data);
			$data= addslashes(trim($data));
			break;
		case 'int':
			$data=(int)trim($data);
			break;
		case 'float':
			$data=(float)trim($data);
			break;
		default: break;
	}
	if($tag) $data=htmlentities($data);
	return $data;
}
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyz012345678901234567890123456789ABCDEFGHIJKLMNOPQRSTUWXYZ012345678901234567890123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function randomNumber() {
    $alphabet = "012345678901234567890123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
//-----------------------CSDL------------------------------
function SysCount($table,$where){
	$sql="SELECT COUNT(*) as num FROM $table WHERE 1=1 $where";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['num']+0;
}
function SysGetList($table,$fields=array(),$where='',$flag=true, $type=true){
    if($type==true) $where=" WHERE 1=1 ".$where;
    if(count($fields)==0){
        $sql="SELECT * FROM $table $where";
    }else{
        $sql="SELECT ";
        foreach($fields as $field){
            $sql.="$field,";
        }
        $sql=substr($sql,0,-1)." FROM $table $where ";
    }
    //echo $sql.'<br>';
    $obj=new CLS_MYSQL;
    $result=$obj->Query($sql);
    if($flag){
        $arr=array();
        while($r=$obj->Fetch_Assoc()){
            $arr[]=$r;
        }
        return $arr;
    }
    return $obj;
}
function SysAdd($table,$arr, $type=false){//$type=1 là return last_id
	if(!is_array($arr) || count($arr)==0) return false;
	$fields=$values='';
	foreach($arr as $key=>$val){
		$fields.="`$key`,";
		$values.="'$val',";
	}
	$sql="INSERT INTO ".$table."(".substr($fields,0,-1).") VALUES(".substr($values,0,-1).")";
   // echo $sql;
	$obj=new CLS_MYSQL;
	$rs=$obj->Exec($sql);
	$id=$obj->LastInsertID();
	if($type) return $id;
    return $rs;
}
function SysEdit($table,$arr,$where){
    $fields='';
    foreach($arr as $key=>$val){
        $fields.="$key='$val',";
    }
    $sql="UPDATE ".$table." SET ".substr($fields,0,-1)." WHERE 1=1 $where";
    //echo $sql;
    $obj=new CLS_MYSQL;
    $res = $obj->Exec($sql);
    return $res;
}
function SysActive($table,$where,$status=null){
	if($status!=null) $sql="UPDATE ".$table." SET `isactive`=$status WHERE $where";
	else $sql="UPDATE ".$table." SET `isactive`=if(`isactive`=1,0,1) WHERE $where";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function SysTrash($table,$where,$status=null){
	if($status!=null) $sql="UPDATE ".$table." SET `is_trash`=$status WHERE $where";
	else $sql="UPDATE ".$table." SET `is_trash`=if(`is_trash`=1,0,1) WHERE $where";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function SysDel($table,$where){
    $sql="DELETE FROM ".$table." WHERE 1=1 $where";

    $obj=new CLS_MYSQL;
    return $obj->Exec($sql);
}
function Curl_Post($url,$jsonBody){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBody);                         
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                        
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                            
        'Content-Type: application/json',                                           
        'Content-Length: ' . strlen($jsonBody)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    return json_decode($resp,true);
}
function Curl_Get($url){
	$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($curl);//var_dump($resp);
    curl_close($curl);
    return json_decode($resp,true);
}
function getLastID($table) {
    $sql="SELECT max(id) as max FROM $table";
    $objdata=new CLS_MYSQL;
    $objdata->Query($sql);
    $row=$objdata->Fetch_Assoc();
    return $row['max']+0;
}
?>