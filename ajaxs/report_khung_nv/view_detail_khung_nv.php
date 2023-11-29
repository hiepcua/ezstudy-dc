<?php
session_start();
ini_set('display_errors',1);
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_user.php');
require_once(libs_path.'cls.mysql.php');


$id_khung_nv = isset($_POST['id_khung_nv']) ? antiData($_POST['id_khung_nv']):'';
if($id_khung_nv==""){
 echo '<div class="row form-group">Không tìm thấy thông tin Khung nhiệm vụ.</div>'; die(); 
}
// echo "id_khung_nv:$id_khung_nv";
// Lấy thông tin khung nhiệm vụ và danh sách quiz
$khung_nv_info = array();
$data_quiz=array();
$json = array();
$json['key'] = PIT_API_KEY;
$json['id'] = $id_khung_nv;
$post_data['data'] = encrypt(json_encode($json),PIT_API_KEY);
$reponse_data = Curl_Post(API_GET_DETAIL_KHUNG_NV, json_encode($post_data));
if(isset($reponse_data['status']) && $reponse_data['status']=='yes'){
	if(isset($reponse_data['data']) && !empty($reponse_data['data'])){
		$khung_nv_info = $reponse_data['data'];
	}
  if(isset($reponse_data['data_quiz']) && !empty($reponse_data['data_quiz'])){
    $data_quiz = $reponse_data['data_quiz'];
  }
}
// echo "reponse_data<pre>";
// var_dump($reponse_data);
// echo "</pre>";
if(!empty($khung_nv_info)){
  $title=isset($khung_nv_info[0]['title'])?$khung_nv_info[0]['title']:"";
  $content=isset($khung_nv_info[0]['content'])?json_decode($khung_nv_info[0]['content'],true):array();
  $results=isset($khung_nv_info[0]['results'])?json_decode(stripslashes($khung_nv_info[0]['results']),true):array();
  $status=isset($khung_nv_info[0]['status'])?(int)$khung_nv_info[0]['status']:"";
  $time_learn=isset($khung_nv_info[0]['time_learn'])?(int)$khung_nv_info[0]['time_learn']:"0";
  $number_quiz=isset($khung_nv_info[0]['number_quiz'])?(int)$khung_nv_info[0]['number_quiz']:"0";
// echo "results<pre>";
// var_dump($results);
// echo "</pre>";

?>
<style type="text/css">
 
  .color-red {color: red;}
  .color-green {color: green;}
  .bg-green {background: #b2c7b2  !important;}
  .line_through { text-decoration: line-through; }
  .box_tra_loi {border:1px dashed #ddd; padding: 10px;}
</style>
<div class="panel-group" id="accordion">

  <?php if(!empty($data_quiz)){
    $i=0;
    foreach ($data_quiz as $key_quiz => $value_quiz) {
      $i++;
      $title_quiz=isset($value_quiz['title'])?$value_quiz['title']:"";
      $content_quiz=isset($value_quiz['content'])?stripslashes($value_quiz['content']):"";
      $answers_quiz=isset($value_quiz['answers'])?json_decode(stripslashes($value_quiz['answers']),true):array();
      // echo "key_quiz:$key_quiz";
      
      // echo "answers_quiz<pre>";
      // var_dump($answers_quiz);
      // echo "</pre>";
      ?>
      <div class="panel panel-default  ">
      <div class="panel-heading ">
        <h4 class="panel-title ">
         <?php if(isset($results[$key_quiz])) echo "<i class='fa fa-check color-green'></i> Đã làm - "; ?> Câu <?php echo $i; ?> - 
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>"><?php echo $title_quiz; ?>
            <?php if(!empty($answers_quiz))
              {
                $txt='quiz';
              } else $txt='Tự luận'; 
              ?>
            <span class="pull-right"><?php echo $txt; ?></span>
          </a>
        </h4>
      </div>
      <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse  <?php //if($i==1) echo ' in show'; ?>">
        <div class="panel-body">
          <div class="box_cau_hoi">
            <p>Nội dung câu hỏi:</p>
            <div class="title_answer">
              <?php echo $content_quiz; ?>
            </div>
            <?php if(!empty($answers_quiz)){
              foreach ($answers_quiz as $key_answers_quiz => $value_answers_quiz) {
                 $class='';
                 $flag_result=false;
                if(isset($results[$key_quiz][0]) && $results[$key_quiz][0]==$key_answers_quiz && $value_answers_quiz['is_true']=='yes' || $value_answers_quiz['is_true']=='yes'){
                  $class='color-red';
                 $flag_result=true;
                }else if(isset($results[$key_quiz][0]) && $results[$key_quiz][0]==$key_answers_quiz && $value_answers_quiz['is_true']!='yes'){
                  $class='line_through';
                 
                }else {
                  $class='';
                 
                }
                ?>
                <p class="<?php echo $class; ?>">
                  <label>
                    <input type="checkbox" name="chk_<?php echo $key_answers_quiz; ?>" <?php if($flag_result) echo "checked"; ?>>
                  <?php if(isset($value_answers_quiz['cnt'])) echo $value_answers_quiz['cnt']; ?>
                  </label>
                  </p>
              <?php
              }


              if(isset($results[$key_quiz])){
                ?>
                <p>Kết quả: <strong>
                  <?php 
                  if($flag_result) echo "Trả lời Đúng"; else echo "Trả lời Sai";
                  ?>
                </strong>
              </p>
            <?php
            }

            } ?>
          </div>

          <?php if(empty($answers_quiz)){ ?>
          <div class="box_tra_loi">
            <p>Nội dung trả lời:</p>
            <div>
              <?php if(isset($results[$key_quiz])) echo $results[$key_quiz]; ?>
            </div>
          </div>
        <?php } ?>

        </div>
      </div>
    </div>
    <?php
    }
  } ?>
    
   
  </div> 
<?php } ?>