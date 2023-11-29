<?php 
$type_user = getInfo("utype");
if($type_user=="hocsinh"){
    $username = getInfo("username");
}else{
    $username = $_CHILD_INFO["username"];
}

$_WEEK=array('CN','T2','T3','T4','T5','T6','T7');
$member_user = $username;
$get_month = isset($_GET['month']) ? antiData($_GET['month']):""; // Cấu trúc Y-m
$get_subject = isset($_GET['subject']) ? antiData($_GET['subject']):"";
$get_work_type = isset($_GET['work_type']) ? antiData($_GET['work_type']):"";
$get_status = isset($_GET['status']) ? antiData($_GET['status']):"";

$cur_month = date('Y-m');
if($get_month!=""){
    $cur_month = date($get_month);
}

// Lấy thông tin học sinh
$memberInfo = api_get_member_info($member_user);
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

<table class="table tbl-main">
    <thead>
        <tr>
            <th>Ngày</th>
            <th class="text-left">Thông tin</th>
            <th class="text-center">Trạng thái</th>
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
                                $class = "btn-primary";
                                break;
                                case '2':
                                $status = "Hoàn thành";
                                $class = "btn-done";
                                break;
                                case '3':
                                $status = "Hoàn thành xuất sắc";
                                $class = "btn-done";
                                break;
                                default:
                                $status = "Chưa làm";
                                $class = "btn-main";
                                break;
                            }

                            echo '<tr '.$bgTr.'>';
                            if($j==0){
                                echo '<td class="text-center btn_add_task date" data-date="'.$int_date.'" rowspan="'.$sumNV.'"><strong>'.$_WEEK[$dayofweek].'</strong><br/>'.$day1.'</td>';
                            }
                            //echo '<td><p><a href="'.$link_detail.'">'.$v['title'].'</a></p>
                            echo '<td><p class="title-txt">'.$v['title'].'</p>
                            <ul class="list-inline"><li>Môn: '.$subject_name.'</li><li> Loại: '.$work_type.'<li></ul>
                            </td>';
                            //echo '<td class="text-center"><a target="_brank" href="'.$link_review.'">'.$status.'</a></td>';
                            echo '<td class="text-center"><button class="btn '.$class.'">'.$status.'</button></td>';
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
