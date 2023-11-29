<script src="https://cdn.ckeditor.com/4.16.1/standard-all/ckeditor.js"></script>
<?php 
require_once(incl_path.'gffunc_comment.php');
$id_question = isset($_GET['id_question']) ? antiData($_GET['id_question']) : '';
$cur_url = ROOTHOST."chat/$id_question";
if(isLogin()){
	$fullname = getInfo('fullname');
	$username = getInfo('username');
	$packet_status = getInfo('packet_status');
	$class_id = getInfo('class_id');

	$json = array();
	$json['key']   = PIT_API_KEY;
	$json['by_member'] = $username;
	$json['id_question'] = $id_question;
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_LIST_QUESTION_BY_ID,json_encode($post_data)); 
	$list_question_response=$rep['data'];
	$subject_of_id_question=isset($list_question_response['list_question'][$id_question]['subject'])?$list_question_response['list_question'][$id_question]['subject']:"";
	$grade_of_id_question=isset($list_question_response['list_question'][$id_question]['grade'])?$list_question_response['list_question'][$id_question]['grade']:"";
	$cdate_of_id_question=isset($list_question_response['list_question'][$id_question]['cdate'])?$list_question_response['list_question'][$id_question]['cdate']:"";

	// gửi câu hỏi sub
	if(isset($_POST['txt_content'])){
		$txt_username=isset($_POST['txt_username']) ? antiData($_POST['txt_username']):'';
		$txt_content=isset($_POST['txt_content']) ? $_POST['txt_content']:'';
		$txt_subject=isset($_POST['txt_subject']) ? antiData($_POST['txt_subject']):'';
		$txt_grade=isset($_POST['txt_grade']) ? antiData($_POST['txt_grade']):'';
		$json['key']   = PIT_API_KEY;
		$json['member'] = $txt_username;
		$json['content'] = $txt_content;
		$json['subject'] = $txt_subject;
		$json['grade'] = $txt_grade;
		$json['class_id']=$class_id;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_ADD_SUB_QUESTION_TO_TEACHER,json_encode($post_data)); 
		if(isset($rep['data'])&&$rep['data']=="success"){
			echo "<script>alert('Thêm mới thành công!');window.location.href='".$cur_url."'</script>";
		}
	}
	?>
	<style type="text/css">
		.list-comment img {max-width: 600px;}
		#show_editor {
			border: none;
			background: #fff;
			color: var(--theme-color);
		}
		.box_list_comment .item {margin-top: 5px;}
		.box_list_comment .item .avatar_cmt {
			text-align: center;
			border: 1px solid #eee;
		}
		.box_list_comment .item .feather-user {font-size: 30px;}
	</style>
	<div class="card1">
		<div class="row">
			<div class="col-md-9 ">
				<div class="list-comment topic">
					<div class="box-card1" style="padding: 10px;">
						<div class="txt-cm">
							<div class="item-info-user">
								<ul class="list-inline">
									<li class="txt-username"><?php echo $fullname;?></span>
									</li>
									<li class="txt-time"><?php echo date('d-m-Y H:i:s',$cdate_of_id_question);?></li>
								</ul>
							</div>
							<p>
								<?php 
								if(isset($list_question_response['list_question'][$id_question])){
									echo stripcslashes($list_question_response['list_question'][$id_question]['content']);
								}
								?>
							</p>
						</div>
					</div> <!--end box-card1 -->

					<div class="box_list_comment">
						<?php if(!empty($list_question_response['list_question'])){
							foreach ($list_question_response['list_question'] as $key_sub => $value_sub) {
								if($key_sub!=$id_question){
									$content_sub=isset($value_sub['content'])?stripslashes($value_sub['content']):"";
									$member_sub=isset($value_sub['member'])?$value_sub['member']:"";
									$cdate_sub=isset($value_sub['cdate'])?$value_sub['cdate']:"";
									?>
									<div class="row item">
										<div class="col-md-2 pull-left">
											<?php 
											$tmp_hoten='';
											if($member_sub==$username){
												$tmp_hoten=$fullname;
											}else {
												if(isset($list_question_response['list_teacher'][$member_sub])) $tmp_hoten=$list_question_response['list_teacher'][$member_sub]['fullname'];
											}
											?>
											<p class="avatar_cmt">
												<i class="feather-user"></i><br>
												<?php echo $tmp_hoten; ?>
											</p>

										</div>

										<div class="col-md-10 pull-left">
											<p>
												<?php echo $content_sub; ?>
											</p>
											<?php if($cdate_sub!=0){ ?>
												<p style="color: #c3c3c3; font-size: 12px;">
													<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y",$cdate_sub); ?>
												</p>
											<?php }?>
										</div>
									</div>

									<?php
								}
							}
						} ?>

						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-10">
								<?php if($packet_status=="running"){ ?>
									<div class="comment-tool" id="comment-tool" style="clear: both;">
										<h4 class="name">Nhập nội dung mới:
											<!-- <button id="show_editor">Show editor</button> -->
										</h4>
										<div class="box-comments">
											<form method="post" id="frm-comment" class="frm-comment <?php if($username!='') echo "isname";?>">
												<input name="txt_username" value="<?php echo $username;?>" type="hidden">
												<!-- <input name="txt_status" value="0" class="txt_status" type="hidden"> -->
												<input name="txt_subject" value="<?php echo $subject_of_id_question;?>" type="hidden">
												<input name="txt_grade" value="<?php echo $grade_of_id_question;?>" type="hidden">

												<div class="content-txt">
													<textarea name="txt_content" class=" form-control txt_content1" id="txt_content" required="true" placeholder="Nội dung hỏi đáp ( Ít nhất từ 10 đến 500 ký tự )" rows="5" /></textarea>
													<div class="item-act">
														<ul class="list-inline" style="margin-top: 15px;">
															<li><button type="button" name="btn_submit" class="btn btn-success submit_frm">Gửi</button></li>
															<li>
																<button type="reset" class="btn btn-default " id="reset">Reset</button></li>
															</ul>
														</div>
													</div>
												</form>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>


						</div>


					</div> <!--end box_list_comment -->


				</div>

			
		

		<div class="col-md-3" style="background: #fff;">
			<?php include_once(COM_PATH."com_frontpage/hocsinh/canhan.php"); ?>
		</div>
		<div class="clearfix"><div>

		</div>
	<?php }?>
	<script>

		$(document).ready(function(){

						// $("#show_editor").click(function(){
						// 	$(".txt_status").val("1");
						CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://ckeditor.com/docs/ckeditor4/4.16.1/examples/assets/plugins/ckeditor_wiris/', 'plugin.js');
						
						CKEDITOR.replace('txt_content',EditOption);
						// });
						

					});


		$('.submit_frm').click(function(){
			var flag=true; var str="";
			var txt_status=$(".txt_status").val();
			var editorText='';
						// if(txt_status==1){
							editorText= CKEDITOR.instances.txt_content.getData();
						// }else {
						// 	editorText=$("#txt_content").val();
						// }
						
						var txt_subject=$('#txt_subject').val();
						if(editorText==''){
							flag=false;
							str+="Vui lòng nhập nội dung \n";
						} 

						if(editorText.length<6){
							flag=false;
							str+="Nội dung quá ngắn\n";
						}

						if(txt_subject==''){
							flag=false;
							str+="Vui lòng chọn môn \n";
						}
						if(flag){
							$('#frm-comment').submit();
						}else alert(str);

					});
    // reset
    $('#reset').click(function(){
    	CKEDITOR.instances.txt_content.setData('');
    });

</script>
