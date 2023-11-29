<div class="timeline hidden-xs">
	<?php 
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
		$count_bonus=count($_Conf_thuong_dat_moc);
		$tile=ceil(100/$count_bonus);
		echo '<div class="line">';
		$i=0;
		foreach($_Conf_thuong_dat_moc as $k=>$v) { 
			$i++;
			$width=$tile*$i;
			$acv='';
			if($total_star>=$k)  $acv='active';
			echo '<span class="not not'.$i.' '.$acv.'" style="left:'.$width.'%"><span class="ic"></span><span class="triangle"></span><span class="number">'.$k.'</span></span>';
		}
		echo '</div>';
		$tile_star=round(($total_star*10)/$count_bonus,3);
		//$tile_diamond=round(($total_diamond*10)/$count_bonus,3);
	}
	?>
	<div class="line-bonus">
		<div class="line-star"><div class="content" style="width:<?php echo $tile_star;?>%"><span class="ic-star"></span></div></div>
		<!--<div class="line-diamond"><div class="content" style="width:<?php echo $tile_diamond;?>%"><span class="ic-diamond"></span></div></div>-->
	</div>
</div>