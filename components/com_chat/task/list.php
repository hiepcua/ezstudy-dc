<script src="https://cdn.ckeditor.com/4.16.1/standard-all/ckeditor.js"></script>
<?php 
require_once(incl_path.'gffunc_comment.php');
require_once(incl_path.'config_mon.php');
$_Subjects = api_get_subject();

if(isLogin()){
	$username=getInfo('username');
	$class_id= getInfo('class_id');
	$packet_status=getInfo('packet_status');

	// gửi câu hỏi mới
	if(isset($_POST['txt_content'])){
		$txt_username=isset($_POST['txt_username']) ? antiData($_POST['txt_username']):'';
		$txt_content=isset($_POST['txt_content']) ? $_POST['txt_content']:'';
		$subject=isset($_POST['txt_subject']) ? antiData($_POST['txt_subject']):'';
		$json['key']   = PIT_API_KEY;
		$json['member'] = $txt_username;
		$json['content'] = $txt_content;
		$json['subject'] = $subject;
		$json['class_id']=$class_id;
		$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
		$rep = Curl_Post(API_ADD_QUESTION_TO_TEACHER,json_encode($post_data)); 
		if(isset($rep['data'])&&$rep['data']=="success"){
			echo "<script>alert('Thêm mới thành công!')</script>";
		}
	}

	// $max_row=30;
	// $cur_page=isset($_POST['txtCurnpage'])? $_POST['txtCurnpage']:1;
	// echo "_SESSION<pre>";
	// var_dump($_SESSION['USER_LOGIN']);
	// echo "</pre>";
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
	$post_data['data'] = encrypt(json_encode($json,JSON_UNESCAPED_UNICODE),PIT_API_KEY);
	$rep = Curl_Post(API_GET_LIST_QUESTION,json_encode($post_data)); 
	// echo "rep<pre>";
	// var_dump($rep);
	// echo "</pre>";
	$total_rows=isset($rep['list_status']["total"])?(int)$rep['list_status']["total"]:0;
	?>
	<style type="text/css">
		.list_question img {max-width: 600px;}
		.ul_list {list-style: none;padding: 0px; margin:0px;}
		.ul_list li {display: inline-block;padding: 5px 10px;}
		.ul_list li strong.number {cursor: pointer; color: #000; text-decoration: underline;}
	</style>
	<div class="card">
		<div class="row">
			<div class="col-md-9 col-xs-12">
				<div class="tab-content box-card">

					<!-- hiển thị ds -->
					<div class="box">
						<div class="filter">
							<div class="row" style="margin-bottom: 10px;">
								<div class="header col-md-12">
									<h1 class="page-title">Hỏi đáp giáo viên
									</h1>
								</div>
							</div>
							<div class="row" style="margin-bottom: 10px;">
								<div class="col-md-9">
									<ul class="ul_list">
										<li>
											Tổng câu hỏi: 
											
												<strong>
													<?php 
													echo $total_rows; ?>
												</strong>
											
										</li>
										<?php if(!empty($_QuestionStatus)){
											foreach ($_QuestionStatus as $key_question => $value_question) {
												?>
											<li>
												
													<?php echo $value_question; ?>: 
													<a href="#" class="btn_view_question" data-status="<?php echo $key_question; ?>"><strong class="number">
														<?php if(isset($rep['list_status'][$key_question])) echo $rep['list_status'][$key_question]; else echo "0"; ?>
													</strong>
													</a>
										</li>	
										<?php
											}
										} ?>
										
									</ul>
								</div>
								<div class="col-md-3">
									<a href="#comment-tool" class="btn btn-success pull-right btn_add_new_question"><i class="fa fa-plus"></i> Đặt câu hỏi mới</a>
								</div>
							</div>

						</div>
						
						<div class="box_question">
						
		                </div>
		                <!--end phân trang -->


		              </div>
		              <!--end hiển thị ds -->
		              <?php if($packet_status=="running"){ ?>
		              	<div class="comment-tool" id="comment-tool">
		              		<h4 class="name">Nhập nội dung câu hỏi mới:</h4>
		              		<div class="box-comments">
		              			<form method="post" id="frm-comment" class="frm-comment <?php if($username!='') echo "isname";?>">
		              				<input name="txt_username" value="<?php echo $username;?>" type="hidden">

		              				<div class="content-txt">
		              					<textarea name="txt_content" class="txt_content form-control" id="txt_content" required="true" placeholder="Nội dung hỏi đáp ( Ít nhất từ 10 đến 500 ký tự )"/></textarea>
		              					<div class="item-act">
		              						<ul class="list-inline" style="margin-top: 15px;">
		              							<li>
		              								<select class="form-control pull-right" id="txt_subject" name="txt_subject">
		              									<option value="">Chọn môn</option>
		              									<?php 

		              									foreach($_Subjects as $k=>$v) { ?>
		              										<option value="<?php echo $k;?>"><?php echo $v['subject_name'];?></option>
		              									<?php } ?>
		              								</select>
		              							</li>

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
		            <div class="col-md-3 bg-white" >
		            	<?php include_once(COM_PATH."com_frontpage/hocsinh/canhan.php"); ?>
		            </div>
		            <div class="clearfix"><div>

		            </div>
		          <?php }?>
		          <script>

		          	$(document).ready(function(){
		          		CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://ckeditor.com/docs/ckeditor4/4.16.1/examples/assets/plugins/ckeditor_wiris/', 'plugin.js');
		          		CKEDITOR.replace('txt_content',EditOption);

		          		var username="<?php echo $username; ?>";
		          		var status_post='all';
		          		$(".box_question").load("<?php echo ROOTHOST; ?>ajaxs/question_aswers/get_by_status.php",{status_post,username},function(data){
		          		});

		          		$(".btn_view_question").click(function(){
		          			var _this=$(this);
		          			var status_post=_this.attr("data-status");

		          			$(".box_question").load("<?php echo ROOTHOST; ?>ajaxs/question_aswers/get_by_status.php",{status_post,username},function(data){

		          			});
		          			return false;
		          		});

		          	});

		          

		          	$('.submit_frm').click(function(){
		          		var flag=true; var str="";
		          		var editorText = CKEDITOR.instances.txt_content.getData();
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
