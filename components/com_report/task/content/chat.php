<?php 
$cur_child = $filter_username;
?>
<div class="box-chat">
	<div class="chat-form" id="frm-comment">
		<div class="row">
			<div class="col-lg-6 col-xl-4 col-md-6 chat-left scroll-bar border-right-light pl-4 pr-4">
				<div class="section full list-user-chat">
					<ul class="list-group list-group-flush">
						<?php 
						$arr_teacher = api_get_teacher_member($cur_child);
						if(count($arr_teacher)>0){
							$first_user = "";
							foreach ($arr_teacher as $key => $value) {
								if($key==0) $first_user = $value['name'];
								$subject_name = isset($_Conf_Subjects[$value['subject']]) ? $_Conf_Subjects[$value['subject']]['name']:"";
								echo '<li class="bg-transparent list-group-item no-icon pl-0 item-'.$value['name'].'" onclick="show_chat(\''.$value['name'].'\')"><h3 class="fw-700 mb-0 mt-1">'.$value['fullname'].'</h3>
								<span class="d-block">'.$subject_name.'</span>
								</li>';
							}
						}
						?>
					</ul>
				</div>
			</div>

			<div class="col-lg-6 col-xl-8 col-md-6 pl-0 d-none d-lg-block d-md-block">
				<div class="box-chat-wrapper">
					<div class="chat-wrapper pt-0 w-100 position-relative scroll-bar scroll-bar-bottom">
						<div class="chat-body1 p-3 ">
							<div class="messages-content pb-5">
								<div id="respon-content-chat" class="list-comment"></div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>

					<div class="chat-bottom dark-bg p-3 shadow-xss" style="width: 98%;">
						<input name="txt_user" id="txt_user" value="<?php echo $first_user;?>" type="hidden">
						<div class="group-chat">
							<input name="txt_content" type="text"  id="in-comment" required="true" placeholder="Nội dung..">
						</div>          
						<span class="bg-current btn btn-chat" id="submit_frm"><i class="ti-arrow-right text-white"></i></span>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function show_chat(username){
		var data = username;
		$('#txt_user').val(data);
		$('.list-group-flush .list-group-item').removeClass('active');
		$('.item-'+data).addClass('active');
		var url='<?php echo ROOTHOST;?>ajaxs/messenger/load.php';
		$.post(url, {data}, function(response_data){
			$('#respon-content-chat').html(response_data);
		});
	}

	var username = '<?php echo $first_user;?>';
	show_chat(username);
	$('#in-comment').keyup(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code==13) {
			e.preventDefault();
			submit_chat();
			return false;
		}
	});

	function submit_chat(){
		var txt_content = document.getElementById("in-comment").value;
		if(txt_content.length<2){
            $('#in-comment').focus();
            return false;
        }
        if(txt_content=='Null'){
        	alert('Vui lòng nhập nội dung');
        	$('#in-comment').focus();
        	return false;
        }
        
        var txt_user=$('#txt_user').val();
        if(txt_user==''){
        	alert('Chọn tài khoản gửi');
        	$('#txt_user').focus();
        	return false;
        }

        var postData = {txt_content,txt_user};

        var url='<?php echo ROOTHOST;?>ajaxs/messenger/add.php';
        $.post(url, postData, function(response_data){
        	$('#respon-content-chat').append(response_data);
        	$('#in-comment').val('');
        });

        return false;
    }

    $('#submit_frm').click(function(){
    	submit_chat();
    	return false;
    })
</script>