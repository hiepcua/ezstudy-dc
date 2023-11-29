<?php 
// Danh sách ngành
$_GLOBALS['LIST_GOI_DANG_KY']=array();
if(!isset($json)) $json=array();
$json['key']=PIT_API_KEY;
$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
$url=API_GET_LIST_REGISTER_PRODUCT;
$reponse_data=Curl_Post($url,json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
	if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
		$_GLOBALS['LIST_GOI_DANG_KY']=$reponse_data['data'];
	}//end if
}//end if


// echo "LIST_GOI_DANG_KY<pre>";
// var_dump(json_encode($post_data));
// echo "</pre>";
?>