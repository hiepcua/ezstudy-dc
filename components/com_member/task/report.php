<div class="card">
	<div class="box-infomation">
		<h2 class="fw-400 font-lg d-block">Lịch học</h2>
		<div class="row row-box">
			<div class="col-md-4 col-box">
				<div class="item-infomation item-account">
				
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
.widget-schedule .box-tool{
    position: relative;
    display: inline-block;
}
.widget-schedule .box-tool .btn_pre_month{
    position: absolute;
    left: 0px;
    top: 15px;
}
.widget-schedule .box-tool .btn_next_month{
    position: absolute;
    right: 0px;
    top: 15px;
}
.widget-schedule .box-tool h3{
    padding: 0 100px;
}
</style>
<?php 
$_WEEK=array('CN','T2','T3','T4','T5','T6','T7');
$member_user = isset($_GET['member_user'])?antiData($_GET['member_user']):"";
$get_month = isset($_GET['month']) ? antiData($_GET['month']):""; // Cấu trúc Y-m
$get_subject = isset($_GET['subject']) ? antiData($_GET['subject']):"";
$get_work_type = isset($_GET['work_type']) ? antiData($_GET['work_type']):"";
$get_status = isset($_GET['status']) ? antiData($_GET['status']):"";

$cur_month = date('Y-m');
if($get_month!=""){
    $cur_month = date($get_month);
}

// Lấy thông tin học sinh
$memberInfo = array();
$json = array();
$json['key'] = PIT_API_KEY;
$json['username'] = $member_user;
$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
$url = API_GET_MEMBER_INFO;
$reponse_data = Curl_Post($url,json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
    if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
        $memberInfo = $reponse_data['data'][0];
    }
}

$gradeID = $memberInfo['grade'];
// Lấy nhiệm vụ học tập của học sinh trong tháng hiện tại.
$arr_nhiemvu = array();
$json = array();
$json['key'] = PIT_API_KEY;
$json['grade'] = $gradeID;
$json['member_user'] = $member_user;
$json['month_Y_m'] = $cur_month;

if($get_subject!="") $json['subject'] = $get_subject;
if($get_work_type!="") $json['work_type'] = $get_work_type;
if($get_status!="") $json['status'] = $get_status;

$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
$url = API_GET_TASK_MEMBER_WORK1;
$reponse_data = Curl_Post($url,json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
    if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
        $arr_nhiemvu = $reponse_data['data'];
    }
}
?>
<style>
td{vertical-align:middle!important;}
td.date{background:#ffd;cursor:pointer;}
tr.cn td{background:#9e9e9e;}
</style>
<section class="card">
    <div class="card-block">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th class="text-center">Môn</th>
                    <th class="text-center">Loại</th>
                    <th>Tiêu đề</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thời gian</th>
                    <th class="text-center">Số điểm</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php
                $date = strtotime($cur_month); //Current Month Year
                $endDate=strtotime(date("Y-m-t", strtotime($cur_month)));
                while ($date <= $endDate) {
                    $dayofweek = date('w', $date);
                    $bgTr=(int)$dayofweek===0?'class="cn"':'';

                    $day1 = date('d/m/Y',$date);
                    $int_date = $date;

                    $nhiemvu = isset($arr_nhiemvu[$int_date]) ? $arr_nhiemvu[$int_date]:array();
                    $sumNV = isset($nhiemvu) ? count($nhiemvu):0;

                    $date+= 24*3600;
                    if($sumNV < 1){
                        echo '<tr '.$bgTr.'>
                        <td class="text-center btn_add_task date" data-date="'.$int_date.'"><strong>'.$_WEEK[$dayofweek].'</strong><br/>';
                        if($dayofweek!=0){
                            echo $day1;
                        }
                        echo '</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        </tr>';
                    }else{
                        $nhiemvu_subject = array();
                        foreach ($nhiemvu as $key => $value) {
                            $nhiemvu_subject[$value['subject']][] = $value;
                        }

                        $j=0;
                        foreach ($nhiemvu_subject as $key2 => $value2) {
                            $num = count($value2);
                            if(count($value2)>0){
                                $i=0;
                                foreach ($value2 as $k => $v) {
                                    $subject_name = $_Subjects[$v['subject']]['name'];
                                    $link_detail = ROOTHOST.'chi-tiet-nhiem-vu/'.$v['id'];
                                    $link_review = ROOTHOST.'review-work/'.$v['id'];
                                    switch ($v['work_type']) {
                                        case 'quiz':
                                        $work_type = "Quiz";
                                        break;
                                        case 'test':
                                        $work_type = "Exam";
                                        break;
                                        case 'live':
                                        $work_type = "Live";
                                        break;
                                        case 'baitap':
                                        $work_type = "Bài tập";
                                        break;
                                        default:
                                        $work_type = "";
                                        break;
                                    }

                                    switch ($v['status']) {
                                        case '1':
                                        $status = "Đã làm";
                                        break;
                                        case '2':
                                        $status = "Hoàn thành";
                                        break;
                                        case '3':
                                        $status = "Hoàn thành xuất sắc";
                                        break;
                                        default:
                                        $status = "";
                                        break;
                                    }

                                    echo '<tr '.$bgTr.'>';
                                    if($j==0){
                                        echo '<td class="text-center btn_add_task date" data-date="'.$int_date.'" rowspan="'.$sumNV.'"><strong>'.$_WEEK[$dayofweek].'</strong><br/>'.$day1.'</td>';
                                    }
                                    if($i==0){
                                        echo '<td class="text-center" rowspan="'.$num.'">'.$subject_name.'</td>';
                                    }
                                    echo '<td class="text-center">'.$work_type.'</td>';
                                    echo '<td><a href="'.$link_detail.'">'.$v['title'].'</a></td>';
                                    echo '<td class="text-center"><a target="_brank" href="'.$link_review.'">'.$status.'</a></td>';
                                    echo '<td class="text-center"></td>';
                                    echo '<td class="text-center"></td>';
                                   
                                    echo '</tr>';
                                    $i+=1;
                                    $j+=1;
                                }

                            }

                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
