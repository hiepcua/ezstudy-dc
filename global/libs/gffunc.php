<?php
function paging($total_rows,$max_rows,$cur_page,$str_param=""){
    $max_pages=ceil($total_rows/$max_rows);
    $start=$cur_page-5; if($start<1)$start=1;
    $end=$cur_page+5;   if($end>$max_pages)$end=$max_pages;
    $paging='
    <form action="" method="get" name="frmpaging" id="frmpaging">
    <input type="hidden" name="page" id="txtCurnpage" value="1" />
    <ul class="pagination">
    ';

    if($str_param!=""){
        $arr_param = explode("&", $str_param);
        if(count($arr_param)>0){
            array_shift($arr_param);
            foreach ($arr_param as $key => $value) {
                $tmp = explode("=", $value);
                $paging.='<input type="hidden" name="'.$tmp[0].'" value="'.$tmp[1].'">';
            }
        }
    }

    $paging.='<p align="center" class="paging">';
    $paging.="<strong>Total:</strong> $total_rows <strong>on</strong> $max_pages <strong>page</strong><br>";

    if($cur_page >1){
        $paging.='<li class="page-item"><a class="page-link" href="javascript:gotopage('.($cur_page-1).')"> « </a></li>';
    }
    if($max_pages>1){
        for($i=$start;$i<=$end;$i++)
        {
            if($i!=$cur_page)
                $paging.="<li class='page-item'><a class=\"page-link\" href=\"javascript:gotopage($i)\"> $i </a></li>";
            else
                $paging.="<li class='page-item active'><a class=\"page-link\" href=\"#\" class=\"cur_page\"> $i </a></li>";
        }
    }
    if($cur_page <$max_pages)
        $paging.='<li class="page-item"><a class="page-link" href="javascript:gotopage('.($cur_page+1).')"> » </a></li>';

    $paging.='</ul></p></form>';
    echo $paging;
}
function paging_index($total_rows,$max_rows,$cur_page){
    $max_pages=ceil($total_rows/$max_rows);
    $start=$cur_page-5; if($start<1)$start=1;
    $end=$cur_page+5;   if($end>$max_pages)$end=$max_pages;
    $paging='
    <form action="" method="post" name="frmpaging" id="frmpaging">
    <input type="hidden" name="page" id="txtCurnpage" value="1" />
    <ul class="pagination">
    ';

    $paging.='<p align="center" class="paging">';
    if($cur_page >1)
        $paging.='<li><a href="javascript:gotopage('.($cur_page-1).')"> « </a></li>';
    if($max_pages>1){
        for($i=$start;$i<=$end;$i++)
        {
            if($i!=$cur_page)
                $paging.="<li><a href=\"javascript:gotopage($i)\"> $i </a></li>";
            else
                $paging.="<li class='active'><a href=\"#\" class=\"cur_page\"> $i </a></li>";
        }
    }
    if($cur_page <$max_pages)
        $paging.='<li><a href="javascript:gotopage('.($cur_page+1).')"> » </a></li>';

    $paging.='</ul></p></form>';
    echo $paging;
}
function getCurentPage($max_pages=1){
    if($max_pages==0) $max_pages=1;
    $cur_page=isset($_GET['page'])?antiData($_GET['page'],'int'):1;
    if($cur_page<1)$cur_page=1;
    if($cur_page>$max_pages) $cur_page=$max_pages;
    return $cur_page;
}
function postCurentPage($max_pages=1){
    if($max_pages==0) $max_pages=1;
    $cur_page=isset($_POST['page'])?antiData($_POST['page'],'int'):1;
    if($cur_page<1)$cur_page=1;
    if($cur_page>$max_pages) $cur_page=$max_pages;
    return $cur_page;
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
        'Đ',':',',','.','?','`','~','!','@','#','$','%','^','&','*','(',')',"'",'"',
        '&','/','|','   ','  ',' ','---','--','“','”','+','–','[',']');

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
        'd','','','','','','','','','','','','','','','','','','',
        '','','',' ',' ','-','-','-','','','','','','');

    $str = str_replace($marTViet, $marKoDau, $str);
    return $str;
}
function Substring($str,$start,$len){
    $str=str_replace("  "," ",$str);
    $arr=explode(" ",$str);
    if($start>count($arr))  $start=count($arr);
    $end=$start+$len;
    if($end>count($arr))    $end=count($arr);
    $newstr="";
    for($i=$start;$i<$end;$i++)
    {
        if($arr[$i]!="")
            $newstr.=$arr[$i]." ";
    }
    if($len<count($arr)) $newstr.="...";
    return $newstr;
}
// Tạo chuỗi ký tự ngẫu nhiên
function rand_string( $length ) {
	$str="";
   $chars = "0123456789abcdefghijklmnopqrstuvwxyz0123456789";
   $size = strlen( $chars );
   for( $i = 0; $i < $length; $i++ ) {
		 $str .= $chars[ rand( 0, $size - 1 ) ];
	}
   return $str;
}
function http_url($url){
    if(defined('SSL') && SSL==true) return str_replace('http://','https://',$url);
else return str_replace('https://','http://',$url);
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
function getThumb($urlThumb='', $class='', $alt=''){
    if($urlThumb !=''){
        return "<img src=".$urlThumb." class='".$class."' alt='".$alt."'>";
    }
    else{
        return "<img src=".ROOTHOST.THUMB_DEFAULT." class='".$class."'>";
    }
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
    $data=preg_replace('/\s\s+/', ' ', $data);
    switch($type){
        case 'plaintext': 
        if(empty($data)) return '';
        $data=addslashes(trim(strip_tags($data)));
        if($tag) $data=htmlentities($data);
        break;
        case 'html':
        if(empty($data)) return '';
        $data=optimizeHTML($data);
            // remove blank tags
        $pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";
        $data= preg_replace($pattern,'',$data);
        $data= addslashes(trim($data));
        if($tag) $data=htmlentities($data);
        break;
        case 'int':
        if(empty($data)) return '0';
        $data=intval(trim($data));
        break;
        case 'float':
        if(empty($data)) return '0';
        $data=floatval(trim($data));
        break;
        default: break;
    }
    return $data;
}
//-----------------------CSDL------------------------------
function SysCount($table,$where){
    $sql="SELECT COUNT(*) as num FROM $table WHERE 1=1 $where";
    //echo $sql;
    $obj=new CLS_MYSQL;
    $result=$obj->Query($sql);
    if($result!=false){
        $r=$obj->Fetch_Assoc();
        return $r['num']+0;
    }else{
        return 0;
    }
}
function SysGetList($table,$fields=array(),$where='',$flag=true){
    if(count($fields)==0){
        $sql="SELECT * FROM $table WHERE 1=1 $where";
    }else{
        $sql="SELECT ";
        foreach($fields as $field){
            $sql.="`$field`,";
        }
        $sql=substr($sql,0,-1)." FROM $table WHERE 1=1 $where ";
    }
    //echo $sql;
    $obj=new CLS_MYSQL;
    $obj->Query($sql);
    if($flag){
        $arr=array();
        while($r=$obj->Fetch_Assoc()){
            $arr[]=$r;
        }
        return $arr;
    }
    return $obj;
}
function SysAdd($table,$arr, $type=''){//$type='' mặc định return last_id, $type!=''  thì return kq truy vấn
    if(!is_array($arr) || count($arr)==0) return false;
    $fields=$values='';
    foreach($arr as $key=>$val){
		//$val = mysqli_escape_string($val); // xu ly voi ky tu single quote
        $fields.="`$key`,";
        $values.="'$val',";
    }
    $sql="INSERT INTO ".$table."(".substr($fields,0,-1).") VALUES(".substr($values,0,-1).")";
	//echo $sql.'<br>';
    $obj=new CLS_MYSQL;
    $rs=$obj->Exec($sql); 
    $id=$obj->LastInsertID();
    if($type=='') return $id;
	return $rs;
}
function SysAdd2($table,$arr){
    if(!is_array($arr) || count($arr)==0) return false;
    $fields=$values='';
    foreach($arr as $key=>$val){
		//$val = mysqli_escape_string($val);
        $fields.="$key,";
        $values.="'$val',";
    }
    $sql="INSERT INTO ".$table."(".substr($fields,0,-1).") VALUES(".substr($values,0,-1).") RETURNING id;";
    //echo $sql.'<br>';
    $obj = new CLS_MYSQL;
    $obj->Query($sql);
    $row=$obj->Fetch_Assoc();
    return $row['id'];
}
function SysAddNotExist($table,$arr,$field_key){
    if(!is_array($arr) || count($arr)==0) return false;
    $fields=$values='';
    foreach($arr as $key=>$val){
		//$val = mysqli_escape_string($val);
        $fields.="$key,";
        $values.="'$val',";
    }
    $sql="INSERT INTO ".$table."(".substr($fields,0,-1).") VALUES(".substr($values,0,-1).")
    ON CONFLICT(".$field_key.") DO NOTHING ";
    $obj = new CLS_MYSQL;
    $res = $obj->Exec($sql); 
    //echo $sql."<br>";
    return $res;
}
function LastInsertID($table) {
    $sql = "SELECT Currval('".$table."_id_seq') LIMIT 1";
    $obj=new CLS_MYSQL;
    $res = $obj->Exec($sql);
    return $res;
}
function SysEdit($table,$arr,$where){
    $fields='';
    foreach($arr as $key=>$val){
		//$val = mysqli_escape_string($val);
        $fields.="`$key`='$val',";
    }
    $sql="UPDATE ".$table." SET ".substr($fields,0,-1)." WHERE $where";
     //echo $sql;
    $obj=new CLS_MYSQL;
    return $obj->Exec($sql);
}
function SysDel($table,$where){
    $sql="DELETE FROM ".$table." WHERE $where";
    // echo $sql;
    $obj=new CLS_MYSQL;
    $obj->Exec($sql);
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
    $resp = curl_exec($curl); //var_dump($resp);
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
/*
function ReportRunning(){
    $data['data']=encrypt(json_encode(array('time'=>time(),'host'=>$_SERVER['HTTP_HOST'],'ip'=>$_SERVER['SERVER_ADDR'],
        'key'=>PIT_API_KEY)),PIT_API_KEY);
    Curl_Post('http://logs.aumsys.net/api/host_runing.php',json_encode($data));
}
ReportRunning();
*/
function convert_number_to_words($number) {
    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = 'âm ';
    $decimal     = ' phẩy ';
    $one         = 'mốt';
    $ten         = 'lẻ';
    $dictionary  = array(
        0                   => 'Không',
        1                   => 'Một',
        2                   => 'Hai',
        3                   => 'Ba',
        4                   => 'Bốn',
        5                   => 'Năm',
        6                   => 'Sáu',
        7                   => 'Bảy',
        8                   => 'Tám',
        9                   => 'Chín',
        10                  => 'Mười',
        11                  => 'Mười một',
        12                  => 'Mười hai',
        13                  => 'Mười ba',
        14                  => 'Mười bốn',
        15                  => 'Mười lăm',
        16                  => 'Mười sáu',
        17                  => 'Mười bảy',
        18                  => 'Mười tám',
        19                  => 'Mười chín',
        20                  => 'Hai mươi',
        30                  => 'Ba mươi',
        40                  => 'Bốn mươi',
        50                  => 'Năm mươi',
        60                  => 'Sáu mươi',
        70                  => 'Bảy mươi',
        80                  => 'Tám mươi',
        90                  => 'Chín mươi',
        100                 => 'trăm',
        1000                => 'ngàn',
        1000000             => 'triệu',
        1000000000          => 'tỷ',
        1000000000000       => 'nghìn tỷ',
        1000000000000000    => 'ngàn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    // if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
    //  // overflow
    //  trigger_error(
    //  'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
    //  E_USER_WARNING
    //  );
    //  return false;
    // }
    
    if ($number < 0) {
        return $negative . $this->convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
        $string = $dictionary[$number];
        break;
        case $number < 100:
        $tens   = ((int) ($number / 10)) * 10;
        $units  = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
            $string .= strtolower( $hyphen . ($units==1?$one:$dictionary[$units]) );
        }
        break;
        case $number < 1000:
        $hundreds  = $number / 100;
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if ($remainder) {
            $string .= strtolower( $conjunction . ($remainder<10?$ten.$hyphen:null) . convert_number_to_words($remainder) );
        }
        break;
        default:
        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int) ($number / $baseUnit);
        $remainder = $number - ($numBaseUnits*$baseUnit);
        $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
        if ($remainder) {
            $string .= strtolower( $remainder < 100 ? $conjunction : $separator );
            $string .= strtolower( convert_number_to_words($remainder) );
        }
        break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}

function getClientIp(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] != '127.0.0.1')
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function AntiDDOS(){
    
    $clientIp=getClientIp();
    if($clientIp == 'UNKNOWN' ){
        header('HTTP/1.1 503 Service Unavailable');
        die;
    }
    // Assuming session is already started
    $uri = md5($_SERVER['REQUEST_URI']);
    $exp = 0.1; // 3 seconds
    $hash = $uri .'|'. microtime(true);
    if (!isset($_SESSION['DDOS'])) {
        $_SESSION['DDOS'] = $hash;
    }else{
        $arr = explode('|', $_SESSION['DDOS']);
        $_uri = $arr[0]; $_exp=$arr[1]; 
        if ($_uri == $uri && microtime(true) - $_exp < $exp) {
            header('HTTP/1.1 503 Service Unavailable');
            die;
        }
        $_SESSION['DDOS'] = $hash;
    }
    
    $limitps = 10; $banip=0;
    if (!isset($_SESSION['first_request'])){
        $_SESSION['requests'] = 0;
        $_SESSION['first_request'] = $_SERVER['REQUEST_TIME'];
    }
    $_SESSION['requests']++;
    if ($_SESSION['requests']>=10 && strtotime($_SERVER['REQUEST_TIME'])-strtotime($_SESSION['first_request'])<=1){
        $banip==1;
    }elseif(strtotime($_SERVER['REQUEST_TIME'])-strtotime($_SESSION['first_request']) > 2){
        $_SESSION['requests'] = 0;
        $_SESSION['first_request'] = $_SERVER['REQUEST_TIME'];
    }
    if ($banip==1) {
        header('HTTP/1.1 503 Service Unavailable');
        die;
    } 
}

function listCboDepartment($par_id = '', $level = 0, $cur_id = '') {
    if($par_id == '')
        $result = SysGetList("aum_department",array()," AND (par_id='' OR par_id is null)");
    else $result = SysGetList("aum_department",array()," AND par_id='$par_id'");
    $str_space="";
    if($level!=0){  
        $str_space.="|";
        for($i=0;$i<$level;$i++)
            $str_space.="--- "; 
    } 
    foreach ($result as $rows){
        $cls = '';
        if($cur_id != '' && $cur_id == $rows['gcode']) $cls = "selected";
        echo "<option value='".$rows['gcode']."' $cls>".$str_space.$rows['name']."</option>";
        listCboDepartment($rows['gcode'],$level+1,$cur_id);
    }
} 
function getData($url_json, $table_name, $key = 'id') {
    $json = file_get_contents("$url_json"); 
    $arr_json = json_decode(decrypt($json), true); 
    if(!$arr_json) {
        $result  = SysGetList("$table_name",array(),"");
        $arr_json = array();
        foreach($result as $r) {
            if(isset($r['isactive']) && $r['isactive'] == 0) continue; // Không lấy dữ liệu của trạng thái ẩn
            $id = $r["$key"];
            $arr_json["$id"]  = $r;
        }
        file_put_contents("$url_json",encrypt(json_encode($arr_json,JSON_UNESCAPED_UNICODE)) );
    }
    return $arr_json;
}
function setData($url_json, $arr) {
    file_put_contents("$url_json",encrypt(json_encode($arr,JSON_UNESCAPED_UNICODE)) );
}
function getJSON($url_json) {
    $json = file_get_contents("$url_json"); 
    $arr  = json_decode(decrypt($json),true); 
    return $arr;
}
function action_logs($code,$user,$arr_logs){
    $arr_logs['by']=$user;
    $arr=array('code'=>$code,'key'=>PIT_API_KEY,'logs'=>$arr_logs);
    $data['data']=encrypt(json_encode($arr),PIT_API_KEY);
    $result=Curl_Post(API_LOGS_SYSTEM,json_encode($data));
}
function getDateReport($type){
	$today=date('d-m-Y 23:59:59');
	if($type==1){
		$first_date=strtotime("$today -1 days");
		$last_date=strtotime($today);
	}
	elseif($type==2){
		$first_date = strtotime('monday this week');
		$last_date = strtotime('sunday this week');
		
	}else{
		$first_date=date('01-m-Y 00:00:01', strtotime($today));
		$last_date=date('t-m-Y 23:59:59', strtotime($today));
		$first_date=strtotime($first_date);
		$last_date=strtotime($last_date);
	}
	return array('first'=>$first_date,'last'=>$last_date);
}
function ago($time){
        $periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thế kỷ");
        $lengths = array("60","60","24","7","4.35","12","10");
        $now = time();
        $difference  = $now - $time;
        $tense         = "trước";
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);
       /* if($difference != 1) {
            $periods[$j].= "s";
        }*/
         if($difference ==0) {
             return "Vừa mới đăng";
        }
        else return $difference." ".$periods[$j]." trước";

    }
	function pushRealTime($data,$name_realtime){
    $options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher(
			'e0810f7aa48b8400d8c6',
			'c2292c3106430b550e0f',
			'478446',
			$options
		);
	$pusher->trigger($name_realtime, 'my-event',$data);
}
function conver_day_name($day_name){
    $new_name = "";
    switch ($day_name) {
        case 'Monday':
            $new_name = "Thứ 2";
            break;
        case 'Tuesday':
            $new_name = "Thứ 3";
            break;
        case 'Wednesday':
            $new_name = "Thứ 4";
            break;
        case 'Thursday':
            $new_name = "Thứ 5";
            break;
        case 'Friday':
            $new_name = "Thứ 6";
            break;
        case 'Saturday':
            $new_name = "Thứ 7";
            break;
        case 'Sunday':
            $new_name = "Chủ nhật";
            break;
        
        default:
            $new_name = "Thứ 2";
            break;
    }
    return $new_name;
}
?>