<?php
session_start();
define('incl_path','global/libs/');
require_once(incl_path.'config-tool.php');
header('Content-type: text/html; charset=utf-8');
header('Pragma: no-cache');
header('Expires: '.gmdate('D, d M Y H:i:s',time()+600).' GMT');
header('Cache-Control: max-age=600');
header('User-Cache-Control: max-age=600');
$req=isset($_GET['req'])?antiData($_GET['req']):'';
$req=str_replace(' ','%2B',$req);
if($req!='') setcookie('RES_USER',$req,time() + (86400 * 30), "/");
define('ISHOME',true);

?>
<!DOCTYPE html>
<html lang='vi'>
<head profile="http://www.w3.org/2005/10/profile">
	<meta charset="utf-8">
	<meta name="google" content="notranslate" />
	<meta http-equiv="Content-Language" content="vi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="referrer" content="no-referrer" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Example</title>
	<link rel="shortcut icon" href="#" type="image/x-icon">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>global/css/style.css?v=1">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>char/themify-icons.css?v=1">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo ROOTHOST_PATH; ?>char/feather.css?v=1">
	<script src='<?php echo ROOTHOST_PATH;?>global/js/jquery-1.11.2.min.js'></script>
    <script src="<?php echo ROOTHOST_PATH;?>global/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML"></script>
	<style type="text/css">/* Chart.js */
@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{animation:chartjs-render-animation 1ms}.chartjs-size-monitor,.chartjs-size-monitor-expand,.chartjs-size-monitor-shrink{position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1}.chartjs-size-monitor-expand>div{position:absolute;width:1000000px;height:1000000px;left:0;top:0}.chartjs-size-monitor-shrink>div{position:absolute;width:200%;height:200%;left:0;top:0}</style>
</head>
<body >
 <div class="card w-100 p-3 border-0 mt-4 rounded-lg bg-white shadow-xs overflow-hidden">
	<div class="card-body p-3">
		<div class="row">                           
			<div class="col-12 p-0 mt-0">
				<div id="chart-round-center"></div>
			</div>
		</div>
		 
	</div>
</div>


<canvas id="lineChart_2" width="452" height="226" style="display: block; width: 452px; height: 226px;" class="chartjs-render-monitor"></canvas>
</body >
  <script src="<?php echo ROOTHOST_PATH;?>global/char/plugin.js"></script>
  <script src="<?php echo ROOTHOST_PATH;?>global/char/apexcharts.min.js"></script>
  <script src="<?php echo ROOTHOST_PATH;?>global/char/chart.js"></script>
  <script src="<?php echo ROOTHOST_PATH;?>global/char/Chart.bundle.min.js"></script>
  <script src="<?php echo ROOTHOST_PATH;?>global/char/chartjs-init.js"></script>