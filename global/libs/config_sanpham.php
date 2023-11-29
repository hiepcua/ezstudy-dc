<?php 
// Danh sách ngành
$_GLOBALS['LIST_SAN_PHAM']=array();
$json=array();
$json['key']=PIT_API_KEY;
$json['grade']=getInfo('grade');
$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
$url=API_GET_LIST_SAN_PHAM;
$reponse_data=Curl_Post($url,json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
	if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
		$_GLOBALS['LIST_SAN_PHAM']=$reponse_data['data'];
	}//end if
}//end if
// echo "LIST_SAN_PHAM<pre>";
// var_dump($_GLOBALS['LIST_SAN_PHAM']);
// echo "</pre>";
?>

