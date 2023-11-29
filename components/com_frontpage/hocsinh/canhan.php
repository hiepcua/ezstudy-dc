<?php 
$total_star = countTotalWallet('ez_wallet_s',$username);
$total_diamond = countTotalWallet('ez_wallet_d',$username);
$packet_user = getInfo("packet");
?>
<div class="box-linhvat">
	<a href="<?php echo ROOTHOST;?>"><img src="<?php echo ROOTHOST;?>images/lv1.png" class="img-linhvat"></a>
</div>
<div class="info-member">
	<h3><?php echo getInfo('fullname');?></h3>
	<p>Lớp <?php echo getInfo('grade');;?></p>
	<div class="row list-bonus">
		<div class="col-md-6">
			<span class="ic ic-star"></span>
			<span class="number"><?php echo $total_star;?></span>
		</div>
		<div class="col-md-6">
			<span class="ic ic-diamond"></span>
			<span class="number"><?php echo $total_diamond;?></span>
		</div>
	</div>
</div>
<?php if($packet_user=="EZ1" || $packet_user=="EZ2") include_once('char_work.php');?>
