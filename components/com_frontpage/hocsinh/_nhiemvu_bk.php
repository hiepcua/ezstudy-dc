<?php 
$username = getInfo('username');
global $type_tbl;
global $arr_subject;
?>
<div class="card-nv skill-section">
	<?php 
	// lấy ngày today
	$arr_date=getDateReport(1); 
	$first_date=isset($arr_date['first'])? $arr_date['first']:'';
	$last_date=isset($arr_date['last'])? $arr_date['last']:'';
	$strwhere=" AND cdate > $first_date AND cdate<=$last_date";
	global $_Nhiemvu;
	//echo '<pre>';
	//var_dump($_Nhiemvu);
	//echo '</pre>';
	if(count($_Nhiemvu)>0) {
	?>
		<div class="nav nav-tabs nav-tabs-ctr" role="tablist">
			<a href="#home" role="tab" data-toggle="tab" class="active">
				Mục tiêu ngày
			</a>
			<a href="#about" role="tab" data-toggle="tab">
				Nhiệm vụ
			</a>
		</div>
		<div class="content-nv">
			<div class="content">
				<div class="tab-content">
					<div class="tab-pane active in" id="home">
						<h2 class="title-label">Nhiệm vụ trong ngày</h2>
						<?php include('timeline_bonus.php');?>
						<div class="box-nvngay scrollbar-stype">
						<!--<h3 class="label-title">Nhiệm vụ trong ngày</h3>-->
						<?php 
							$item = SysGetList("ez_bonus_config",array(), " AND type_nv='1'",false);
							$i=0;
							while($v=$item->Fetch_Assoc()) { 
								$i++;
								$name=$v['name'];
								$type=$v['type'];
								$star=$v['num_star'];
								$diamond=$v['num_diamond'];
								$url=ROOTHOST."work-day/".$v['id'];
								$label=$class='';
								//echo $strwhere;
								if(get_bonus_histories($username,$v['id'],$strwhere)>0){
									$class="active";
									$label='<span class="btn">Hoàn thành</span>';
								}
								else $label=get_url_nv($_Nhiemvu,$type,$type_tbl,$name,$v['id']);
								?>
									<div class=" item-nvngay <?php echo $class;?>">
									<span class="stt st<?php echo $i;?>"></span>
									<h4><?php echo $name;?></h4>
									<?php echo $label;?>
									<ul class="list-inline list-bonus-nv">
										<li class="color-star"><span class="number">+<?php echo $star;?></span><span class="ic-star ic"></span></li>
										<li><span class="number">+<?php echo $diamond;?></span><span class="ic-diamond ic"></span></li>
									</ul>
								</div>
							<?php } 
							?>
						</div>
					</div>
					<div class="tab-pane" id="about">
					<?php
					$arr_date=getDateReport(2); 
					$first_date=isset($arr_date['first'])? $arr_date['first']:'';
					$last_date=isset($arr_date['last'])? $arr_date['last']:'';
					if($type_tbl==1) $label_title='Nhiệm vụ tuần <span class="label-p">(Từ '.date('d-m-Y',$first_date).' đến '.date('d-m-Y',$last_date).')</span>';
					else $label_title="Nhiệm vụ khung";
					?>
						<h2 class="title-label"><?php echo $label_title;?></h2>
						<?php include('timeline_bonus.php');?>
						<div class="list box-nv-main scrollbar-stype">
						<?php 
						global $_Subjects;
						$item_bonus = SysGetList("ez_bonus_config",array(), " AND type_nv='2'");
						
						foreach($_Nhiemvu as $k=>$v) { 
						$id_mon=$this_grade."_".$k;
						if($type_tbl==1 && !in_array($id_mon,$arr_subject)) continue;
						$mon=isset($_Subjects[$id_mon]) ? $_Subjects[$id_mon]['subject_name']:"";
						$ic=isset($_Subjects[$id_mon]) ? $_Subjects[$id_mon]['subject_icon']:"";
	
							$arr_url=array();
								$i=0;
								foreach($v as $key=>$item) { 
								//var_dump($item);
									if($type_tbl==1) $contents=$item['contents'];
									else $contents=$item['content'];
									if($contents=='') continue;
									
									$i++;
									$name=$item['title']==''? 'Bài tập môn '.$mon:$item['title'];
									$number=$item['number_quiz'];
									$status=$item['status'];
									$lambai='Làm bài';
									$cat=$item['work_type'];
									if($cat=='quiz'){
										$label='<span class="btn text-warning alert-warning"><i class="ic feather-edit-3"></i> Luyện tập</span>';
									}
									elseif($cat=='baitap'){
										$label='<span class="btn text-primary alert-primary"><i class="ic feather-book"></i> Bài tập</span>';
									}
									elseif($cat=='test'){
										$label='<span class="btn text-success alert-success"><i class="ic feather-check-square"></i> Bài kiểm tra</span>';
										
									}else{
										$label="";
										
									}
									if($type_tbl==1) $url=ROOTHOST."tool-work/".$item['id'];
									else $url=ROOTHOST."tool-work-config/".$item['id'];
									$arr_url[]=$url;
									if($cat=='live'){
										$contents=json_decode($item['contents'],true);
										$label_live='';
										if(count($contents)>0){
											foreach($contents as $vl){
												$url=$vl['link'];
												$name=$vl['title'];
												$label_live.='<a href="'.$url.'" class="btn btn-live text-danger alert-danger"><i class="ic feather-tv"></i> '.$name.'</a>';
											}
										}
										$url=$contents[0]['link'];
										$label=$label_live;
										$lambai='Đi tới';
										
									}
									?>
									<div class=" item-nv <?php if($status==1) echo 'active';?>">
									<a href="" class=" thumb icon <?php echo $ic;?> icon-subject">
					
									</a>
									<h4><?php echo $name;?></h4>
									<?php echo $label;?> <span class="btn btn-label">Môn <?php echo $mon;?></span>
									<?php if($number>0) echo '<span class="btn">Số câu '.$number.'</span>';
									if(count($item_bonus)>0){
									echo '<ul class="list-inline list-bonus-nv nv-chinh">';

									foreach($item_bonus as $r){
										$star=$r['num_star'];
										$diamond=$r['num_diamond'];
										if($r['id']==9){
											$star=$star*2;
											$diamond=$diamond*2;
										}
										?>
										<li class="label-bonus"><?php echo $r['name'];?>: </li>
											<li class="color-star"><span class="number">+<?php echo $star;?></span><span class="ic-star ic"></span></li>
											<li><span class="number">+<?php echo $diamond;?></span><span class="ic-diamond ic"></span></li>
									
										<?php
									}
									echo '</ul>';
									}
									
									
									?>
									
									<?php 
									echo '<div class="box-btn-act">';
									if($status==1){
										
										echo  '<a href="'.$url.'" class="btn btn-refresh" >Làm lại <i class="fa fa-refresh" aria-hidden="true"></i></a>';
										$url=ROOTHOST."review-work/".$item['id'];
										echo  '<a href="'.$url.'" class="btn btn-done">Xem lại</a>';
										
									}
									elseif($status==2){
										echo  '<a href="#" class="btn btn-done action-done"><i class="fa fa-check-square-o" aria-hidden="true"></i> Hoàn thành</a>';
									}
									else echo '<a href="'.$url.'" target="_brank" class="btn  btn-action">'.$lambai.' <i class="fa fa-arrow-circle-right"></i></a>';
									echo '</div>';
									//if($status==1) echo '<p class="notic">Bài đã làm nhưng chưa hoàn thành</p>';
									?>
										
										
								</div>
								<?php } ?>
						
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php 
		}
		else{
			if(isset($_SESSION['show_nv_khung'])) include_once("nhiemvu_add.php");
		}
			
	?>
</div>
<script>
 function frm_subject_nv(type, bonus_configid){ //type 1 là bài tập, 2 là lý thuyết
	 $('#myModalPopup').modal('show');
	 if(type==1) label="Làm 1 bài tập về nhà";
	 else label="Xem lý thuyết môn bất kỳ";
	$('#myModalPopup .modal-title').html(label);
	$.post('<?php echo ROOTHOST;?>ajaxs/nhiemvu/frm_muctieungay.php',{type,bonus_configid},function(req){
		$('#modal-content').html(req);
	});
 } function notic_nv(){ 
	 alert('Hiện tại các bài nhiệm vụ mới chưa được thêm. Vui lòng quay lại sau!');
 }
</script>



