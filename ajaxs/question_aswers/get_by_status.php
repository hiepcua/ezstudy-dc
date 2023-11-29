<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(incl_path.'gffunc_comment.php');

require_once(libs_path.'cls.mysql.php');
require_once(incl_path.'config_mon.php');
$status_post=isset($_POST['status_post']) ? antiData($_POST['status_post']):'all';
$username=isset($_POST['username']) ? antiData($_POST['username']):'';

$max_rows=10;
	$cur_page=1;
	if(isset($_POST['cur_page'])){
		$cur_page = (int)$_POST['cur_page'];
	}

	$json=array();
	$json['key']   = PIT_API_KEY;
	$json['by_member'] =$username;
	$json['page']=$cur_page;
	$json['maxrow']=$max_rows;
	if($status_post!='all'){
		$json['status']=$status_post;
	}
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_LIST_QUESTION,json_encode($post_data)); 
	$total_rows=isset($rep['list_status']["total"])?(int)$rep['list_status']["total"]:0;
?>

<form name="frm_list" class="frm_list" id="frm_list" method="post">
		<div class="content list_question">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>STT</th>
						<th>Môn</th>
						<th>Nội dung câu hỏi</th>
						<th>Trạng thái</th>
					</tr>
				</thead>
				<tbody>
					<?php

					if(is_array($rep['data']) && count($rep['data'])>0) {
						$max_pages=ceil($total_rows/$max_rows);
						$i=0;
						foreach($rep['data'] as $item){
							$i++;
							$by_member=$item['by_member'];
							$content=stripslashes($item['content']);
							$cdate=$item['cdate'];
							$status=$item['status'];
							$subject=$item['subject'];
							$id_question=$item['id'];
							$url=ROOTHOST."chat/".$id_question;
							$css_style=isset($_QuestionStatusStyle[$status])?$_QuestionStatusStyle[$status]:"#10d876"; 
							 // $content = preg_replace("/<img[^>]+\>/i", "", $content); 
							$content=strip_tags($content);
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td>
									<?php
									if(isset($_GLOBALS['LIST_MON_HOC'][$subject]['name']))
										echo $_GLOBALS['LIST_MON_HOC'][$subject]['name'];
									?>
								</td>
								<td>
									<p style="margin-bottom: 5px;">
										<a href="<?php echo $url; ?>">
											<?php echo $content; ?>
										</a>
									</p>
									<?php if($cdate!=0){ ?>
										<p style="color: #c3c3c3; font-size: 12px;margin-bottom: 0px;">
											<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y",$cdate); ?>
										</p>
									<?php }?>
								</td>
								<td style="max-width: 100px;">
									<span class="btn btn-success" style="padding: 2px 5px;background:<?php echo $css_style; ?>; border-color: <?php echo $css_style; ?> ">
										<?php 
										if(isset($_QuestionStatus[$status]))
											echo $_QuestionStatus[$status];
										else echo "...";
										?>
									</span>
								</td>
							</tr>
							<?php
						}
					}else {
						?>
						<tr>
							<td align="center" colspan="4">Dữ liệu trống!</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<!-- phân trang -->
		<div class="col-md-12 text-right">
			Show <input type="number" min="1" max="<?php echo $max_pages; ?>" style="width: 40px;border:1px solid #eee;" name="cur_page" class="cur_page" value="<?php echo $cur_page; ?>"> / <?php echo $max_pages; ?> pages

			<script type="text/javascript">
				$(document).ready(function(){
            // phân trang
            $(".cur_page").change(function(){
            	var cur_page=$(this).val();
            	var status_post="<?php echo $status_post; ?>";
            	var username="<?php echo $username; ?>";
            	$(".box_question").load("<?php echo ROOTHOST; ?>ajaxs/question_aswers/get_by_status.php",{status_post,username,cur_page},function(data){

		          			});
            });
          });
        </script>
      </div>
    </form>