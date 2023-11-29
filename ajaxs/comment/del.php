<?php
session_start();
include_once('../../includes/gfinnit.php');
include_once('../../includes/gfconfig.php');
include_once('../../includes/gffunction.php');
include_once('../../libs/cls.mysql.php');
include_once('../../libs/cls.member.php');
include_once('../../libs/cls.comments.php');
if(!isset($objmem))
    $objmem=new CLS_MEMBER();
$username=isset($_POST['txt_username']) ? addslashes($_POST['txt_username']):'';
$parid=isset($_POST['parid'])? (int)$_POST['parid']:1;
$obj=new CLS_COMMENT();
$userip=get_client_ip();
if(isset($_POST['val'])){
    $type=isset($_POST['type']) ? (int)$_POST['type']:'0';
    $id=(int)$_POST['val'];
    $obj->Delete($id,$userip, $type);
    $trWhere='WHERE 1=1';
    $obj->getComment($trWhere, $parid);
}
?>
<form method="post" id="frm-comment" class="frm-comment <?php if(isset($_SESSION['name_user'])) echo "isname";?>">
    <p class="name-user"><?php if(isset($_SESSION['name_user'])) echo "Name: <b>".$_SESSION['name_user'].'</b>';?></p>
    <input name="txt_username" value="<?php echo $username;?>" type="hidden">
    <input name="txt_parid" value="<?php echo $parid;?>" type="hidden">
    <img src="<?php echo ROOTHOST.THUMB_DEFAULT;?>" class="par-avatar">
    <div class="content-txt">
        <input name="txt_content" class="txt_content form-control" id="#in-comment" required="true"/>
    </div>
</form>
<script>
    $('#frm-comment').submit(function(){
        var form = $('#frm-comment');
        var postData = form.serializeArray();
        var url='<?php echo ROOTHOST;?>ajaxs/comment/add.php';
        $.post(url, postData, function(response_data){
            $('#respon-content').html(response_data);
            $('html, body').animate({ scrollTop: $('#respon-content').height() }, 1200);
        });
        return false;
    })
    $('.frm-comment-child').submit(function(){
        var id = $(this).attr('value');
        var postData = $(this).serializeArray();
        var url='<?php echo ROOTHOST;?>ajaxs/comment/add.php';
        $.post(url, postData, function(response_data){
            $('#respon-content').html(response_data);
            $('#item'+id+' .content-child').show();
        });
        return false;
    })
    $('.ctrl-del').click(function(){
        var val = $(this).attr('value');
        var comment_id = $(this).attr('comid');
        var parid =<?php echo $parid;?>;
        var txt_username ='<?php echo $username;?>';
        var type = $(this).attr('type');// type =1 là child
        var url='<?php echo ROOTHOST;?>ajaxs/comment/del.php';
        $.post(url, {val, type,parid,txt_username}, function(response_data){
            $('#respon-content').html(response_data);
            $('#content-child'+comment_id).show();
        });
        return false;
    })
    $('.ctrl-answer').click(function(){
        var val=$(this).attr('value');
        $('#content-child'+val).show();
    })
</script>