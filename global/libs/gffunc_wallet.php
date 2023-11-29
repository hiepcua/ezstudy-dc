<?php
function updatePayWallet($tbl_wallet,$username,$number){
	$sql="UPDATE $tbl_wallet SET `money`=`money`-$number WHERE username='$username'";
	
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function updatePushWallet($tbl_wallet,$username,$number){
	$time=time();
	$sql="INSERT INTO $tbl_wallet (username, money, money_total,cdate, mdate, status)
		VALUES ('$username', '$number', '$number','$time','$time','1')
		ON DUPLICATE KEY UPDATE
		  mdate     = '$time',
		  money     = money+$number,
		  money_total = money_total+$number";
		
		
	$obj=new CLS_MYSQL;
	return $obj->Query($sql);
}
function countTotalWallet($tbl_wallet,$username, $type=1, $strwhere=''){//1 là số dư, 2 là tổng nạp vào
    $name_field=$type==1 ? 'money':'money_total';
    $sql="SELECT `".$name_field."` as total FROM $tbl_wallet WHERE `username`='$username' $strwhere";
    $obj=new CLS_MYSQL;
    $obj->Query($sql);
    $r=$obj->Fetch_Assoc();
    return $r['total']+0;
}

function countTotalWalletHistories($username, $type='',$strwhere=''){
    if($type!='') $strwhere.=" AND type='$type'";
	$sql="SELECT SUM(`money`) as total FROM ez_wallet_histories WHERE `username`='$username' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function getDS($username,$type_date){
	$tbl_wallet='tbl_wallet_s_histories';
	$arr_date=getDateReport($type_date);
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$sql="SELECT SUM(`money`) as total FROM $tbl_wallet WHERE `username`='$username' $strwhere";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['total']+0;
}
function getListWallet($array, $type='b', $flag_money=''){ //$flag_money=1 chuyển đổi money thành dương
    if($array->Num_rows()>0){
    ?>
    <table class="table tbl-main">
        <thead><tr>
            <th class="text-center mhide" width="50">STT</th>
            <th>Nội dung</th>
            <th class="text-center mhide">Loại giao dịch</th>
            <th class="text-right">Số điểm</th>
            <th class="text-center mhide">Thời gian giao dịch</th>
            <th width="90">Trạng thái</th>
        </tr></thead>
        <tbody>
        <?php
        $stt=0;
		$total_money_on_page=0;
            while($r=$array->Fetch_Assoc()) {
                $stt++;
                $cdate=date('Y-m-d H:i',$r['cdate']);
                $flag=$r['status']==1? 'fa-check-circle done':'fa-spinner';
				if($r['type']==1) $label=$type=='b'? 'Nạp điểm':'Kích hoạt';
				elseif($r['type']==2) $label=$type=='b'? 'Chuyển điểm':'Gia hạn';
				else  $label=$type=='b'?'Gia hạn':'Liên kết';
				$money=$r['money'];
				if($flag_money==1 && $money<0) $money=-1*$r['money'];
				$total_money_on_page+=$money;
                ?>
                <tr>
                    <td class="text-center mhide"><?php echo $stt;?></td>
                    <td><?php echo $r['note'];?></td>
                    <td class="text-center mhide"><?php echo $label;?></td>
                    <td class="text-right"><?php echo number_format($money);?>đ</td>
                    <td class="text-center mhide"><?php echo $cdate;?></td>
                    <td class="text-center"><i class="fa <?php echo $flag;?>"></i></td>
                </tr>
            <?php }
			if($flag_money==1){
        ?>
		 <tr class="total_tr">
				<td colspan='3' class="text-right" style="color: #333">Tổng:</td>
				<td class="td-price text-right"><?php echo number_format($total_money_on_page); ?>đ</td>
				<td></td>
				<td></td>
			</tr>
			   <?php }
        ?>
        </tbody>
    </table>
    <?php
    }
}
function getStarDiamond($number_work, $type){//$type 1 là đăng nhập, 2 là làm nv, 3 là làm bài tập, 4 xem bài bài lý thuyết, 5 hoàn thành, 6 ht xuất sắc
	$item = SysGetList("ez_bonus_config",array()," AND number='$number_work' AND type='$type'");
	
	$conf_id=$num_star=$num_diamond='';
	if(isset($item[0])){
		$num_star=$item[0]['num_star'];
		$num_diamond=$item[0]['num_diamond'];
		$conf_id=$item[0]['id'];
	}
	return array('num_star'=>$num_star, 'num_diamond'=>$num_diamond,'conf_id'=>$conf_id);
}


function AddBonus($username, $count_work, $type_work){
	$flag=true;
	//get date today
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	//$count_work là số bài thực hiện, $type_work là type thưởng:1 là login, 2 là vào làm bài 1,2,5 bài, 3 là hoàn thành bài
	$arr_bonus=getStarDiamond($count_work,$type_work);
	$number_star=$arr_bonus['num_star'];
	$number_diamond=$arr_bonus['num_diamond'];
	$conf_id=$arr_bonus['conf_id'];
	$count=SysCount('ez_wallet_histories'," AND username='$username' AND bonus_configid='$conf_id' $strwhere");
	//var_dump($count);
	if($count>0) $flag=false;
	if($number_star=='' OR $number_diamond=='') $flag=false;
	if($flag==true){
		
		$note='';
		if($type_work==1) $note="đăng nhập hệ thống";
		if($type_work==2) $note="khi bạn làm ".$count_work." bài bất kỳ trong nhiệm vụ";
		if($type_work==3) $note="khi bạn làm ".$count_work." bài tập bất kỳ";
		if($type_work==4) $note="khi bạn xem lý thuyết 1 bài bất kỳ";
		if($type_work==5) $note="khi bạn đã hoàn thành ".$count_work." bài bất kỳ";
		if($type_work==6) $note="khi bạn đã hoàn thành xuất sắc ".$count_work." bài bất kỳ";
		//echo $note;
		$arr=array();
		$arr['cuser']=$arr['username']=$username;
		$arr['type']=1;// 1 là star, 2 là kim cương
		$arr['status']=1;
		$arr['cdate']=$arr['cdate']=time();
		$arr['money']=$number_star;
		$arr['bonus_configid']=$conf_id;
		$arr['note']="+".$number_star." sao ".$note;
		
		$rs1=updatePushWallet('ez_wallet_s',$username,$number_star);
		$rs2=SysAdd('ez_wallet_histories',$arr,1);	
		
		$arr['type']=2;
		$arr['money']=$number_diamond;
		$arr['note']="+".$number_diamond." kim cương ".$note;
		$rs1=updatePushWallet('ez_wallet_d',$username,$number_diamond);
		$rs2=SysAdd('ez_wallet_histories',$arr,1);
		//AddBonusStar($username);
	}
}		
function AddBonusStar($username){
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	$sql ="SELECT SUM(money) as number FROM ez_wallet_histories WHERE username='$username' AND type=1 AND  bonus_configid > 0 $strwhere";
	 $obj = new CLS_MYSQL;
	$obj->Query($sql);
	$row=$obj->Fetch_Assoc();
	$number=$row['number'];
	$star=$diamond=0;
	/*if($number>=10 && $number<20){
		$star=2;
		$diamond=5;
		$cur_star=10;
	}
	if($number>=20 && $number<39){
		$star=5;
		$diamond=20;
		$cur_star=20;
	}
	if($number>=40 && $number<59){
		$star=10;
		$diamond=50;
		$cur_star=40;
	}
	if($number>=60){
		$star=20;
		$diamond=100;
		$cur_star=60;
	}
	*/
	$cur_star=$star=$diamond=0;
	foreach($_Conf_thuong_dat_moc as $key=> $vl){
		if($key <= $number){
			$star= $vl['num_star'];
			$diamond= $vl['num_diamond'];
			$cur_star=$key;
		}
	}
	if($star>0 && $diamond>0){
		$time=time();
		$note="+".$star." sao thưởng khi đạt ngưỡng ".$cur_star." sao";
		$count=SysCount("ez_wallet_histories", " AND username='$username' AND type='1' AND bonus_configid='0' $strwhere");

		if($count<1){
			$sql ="INSERT INTO ez_wallet_histories(username,money,cdate,note, type, cuser,status, bonus_configid)
			VALUES('$username','$star','$time','$note','1','$username','1','0')";
			 $obj = new CLS_MYSQL;
			if($obj->Query($sql)==true){
				updatePushWallet('ez_wallet_s',$username,$star);
				$note="+".$diamond." kim cương thưởng khi đạt ngưỡng ".$cur_star." sao";
				$sql ="INSERT INTO ez_wallet_histories(username,money,cdate,note, type, cuser,status, bonus_configid)
				VALUES('$username','$diamond','$time','$note','2','$username','1','0')";
				 $obj = new CLS_MYSQL;
				if($obj->Query($sql)==true) updatePushWallet('ez_wallet_d',$username,$star);
			}
		}
	}
}	
?>