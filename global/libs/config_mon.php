<?php 
// Danh sách ngành
$_GLOBALS['LIST_MON_HOC']=array();
$json=array();
$json['key']=PIT_API_KEY;
$post_data['data']=encrypt(json_encode($json),PIT_API_KEY);
$url=API_GET_LIST_MON;
$reponse_data=Curl_Post($url,json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
	if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
		$_GLOBALS['LIST_MON_HOC']=$reponse_data['data'];
	}//end if
}//end if

// echo json_encode($post_data);
// echo "LIST_MON_HOC<pre>";
// var_dump($_GLOBALS['LIST_MON_HOC']);
// echo "</pre>";
?>