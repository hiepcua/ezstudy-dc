<?php
session_start();
define('root_path','../../');
require_once(root_path.'global/libs/gfconfig.php');
require_once(root_path.'global/libs/gfinit.php');
require_once(root_path.'global/libs/gffunc.php');
require_once(root_path.'global/libs/gffunc_user.php');
require_once(root_path.'libs/cls.mysql.php');

$json 	= json_decode(file_get_contents('php://input'),true); 
$data 	= json_decode(decrypt($json['data'],PIT_API_KEY),true);
$key	= isset($data['key']) ? antiData($data['key']) : '';
$username 	= isset($data['username']) ? antiData($data['username']) : '';

function countTotalWalletHistories($username, $type='',$strwhere=''){
    if($type!='') $strwhere.=" AND type='$type'";
	$sql="SELECT SUM(`money`) as total FROM ez_wallet_histories WHERE `username`='$username' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}

if($key == PIT_API_KEY){
	$arr_data = array();
	$arr_box = array();

	// lấy ngày trong ngày
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$total_star=countTotalWalletHistories($username,1,$strwhere);
	$total_diamond=countTotalWalletHistories($username,2,$strwhere);
	$tile_star=$tile_diamond=0;

	global $_Conf_thuong_dat_moc;
	if(isset($_Conf_thuong_dat_moc) && count($_Conf_thuong_dat_moc)>0){
		$count_bonus = count($_Conf_thuong_dat_moc);
		$tile = ceil(100/$count_bonus);

		foreach($_Conf_thuong_dat_moc as $k=>$v) { 
			if($total_star>=$k){
				$v['active'] = 'true';
			}else{
				$v['active'] = 'false';
			}

			$arr_box[$k] = $v;
		}
	}

	$arr_data['box'] = $arr_box;
	$arr_data['total_star'] = $total_star;
	$arr_data['total_diamond'] = $total_diamond;

	echo json_encode(array('status'=>'yes','data'=>$arr_data));
}else{
	echo json_encode(array('status'=>'no','data'=>"key_fail"));
}
die();