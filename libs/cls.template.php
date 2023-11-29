 <?php
class CLS_TEMPLATE{
	private $objmysql=null;
	public function CLS_TEMPLATE(){
		$this->objmysql=new CLS_MYSQL;
	}
	public function Load_Extension(){
		define("EDIT_FULL_PATH",EDI_PATH."innovar/scripts/innovaeditor.js");
	}
	public function Load_lang_default(){
		define('CURENT_LANG','en');
		require_once(LAG_PATH.CURENT_LANG.'/english.php');
	}
	public function Load_defaul_tem($level=1){
        if($level==1) return 'default';
        else return 'member';
    }
	public function WapperTem(){
		//if(md5(sha1($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_HOSaT']))!=IGF_LICENSE) return;
		if($this->error()) return;
		require_once(THIS_TEM_PATH.'home.php');
	}
	// Test template
	private function error(){
		if(!is_file(THIS_TEM_PATH.'template.xml')){
			echo 'template.xml is not exist';
			return false;
		}
		if(!is_file(THIS_TEM_PATH."home.php")){
			echo 'home.php is not exist';
			return false;
		}
	}
	// Check Module
	public function isModule($position){
		$sql="SELECT * FROM tbl_modules WHERE `isactive`=1 AND `position`='$position' ORDER BY `order`,`title`";
		$this->objmysql->Query($sql);
		if($this->objmysql->Num_rows()>0)
			return true;
		else
			return false;
	}
	// Load Module
	public function loadModule($position,$site="site"){
		$position=trim($position);
		$site=trim($site);
		$sql="SELECT `mod_id`,`type`,`mnuids` FROM `tbl_modules` WHERE `isactive`=1 AND `position`='$position' ORDER BY `order`";
		$this->objmysql->Query($sql);
		while($rows=$this->objmysql->Fetch_Assoc()){
			$mnus=$rows['mnuids'];
			if(trim($rows['mnuids'])=='')
				continue;
			if($rows['mnuids']!='all'){
				$mnus=explode(',',$mnus);
				$cur_menu=$_SESSION['CUR_MENU'];
				if(!in_array($cur_menu, $mnus))
				continue;
			}
			if(is_file(MOD_PATH.'mod_'.trim($rows['type']).'/layout.php')==true)
				include(MOD_PATH.'mod_'.trim($rows['type']).'/layout.php');
			else
				echo '<br> Module isn not exist!';
		}
	}
	public function isFrontpage(){
		$flag=true;
		if(isset($_GET['com']))
			$flag=false;
		return $flag;
	}
	function loadComponent($level=1){
		$com='';
		if(isset($_GET['com']))	$com=addslashes($_GET['com']);
		if(!is_dir(COM_PATH.'com_'.$com))
			if($level==1) $com='frontpage';
        else $com='frontpage_member';
		include(COM_PATH.'com_'.$com.'/layout.php');
	}
	function getFullURL(){
		echo $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
	}
    function updateVisited(){
        // kiểm tra session view
        if(isset($_SESSION['VIEW'])){
            // update action time
            $mysql=new CLS_MYSQL;
            $adate=time();
            $sql="UPDATE tbl_visit  SET `adate`='$adate',`isonline`=1 WHERE `ses_id`='{$_SESSION['VIEW']}'";
            $mysql->Exec($sql);
            // autologout
            $sql="UPDATE tbl_visit  SET `isonline`=0 WHERE (adate+10*60)<$adate";
            $mysql->Exec($sql);
        }else{
            // thêm session mới
            $_SESSION['VIEW']=session_id();
            $date=date('Y-m-d H:i:s');
            $adate=time(); // mktime
            $sql="INSERT INTO tbl_visit (`ses_id`,`cdate`,`adate`) VALUES ('{$_SESSION['VIEW']}','{$date}','{$adate}')";
            $mysql=new CLS_MYSQL;
            $mysql->Exec($sql);
        }
    }
    function show_visitecount(){
        $isonline=0;
        $today=0;
        $month=0;
        $total=0;
        $mysql=new CLS_MYSQL;
        $sql="SELECT count(*) as num FROM tbl_visit WHERE isonline=1";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $isonline=$r['num'];

        $thisday=date('d'); $thismonth=date('m'); $thisyear=date('Y');
        $sql="SELECT count(*) as num FROM tbl_visit WHERE DAY(cdate)=$thisday AND MONTH(cdate)=$thismonth AND YEAR(cdate)=$thisyear";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $today=$r['num'];

        $sql="SELECT count(*) as num FROM tbl_visit WHERE MONTH(cdate)=$thismonth AND YEAR(cdate)=$thisyear";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $month=$r['num'];

        $sql="SELECT count(*) as num FROM tbl_visit ";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $total=$r['num'];

        $sql="SELECT count(*) as num FROM tbl_post WHERE isactive=1 ";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $total_post=$r['num'];

        $sql="SELECT count(*) as num FROM tbl_comment WHERE isactive=1 ";
        $mysql->Query($sql);
        $r=$mysql->Fetch_Assoc();
        $total_cmt=$r['num'];

         echo "<ul>
			<li>Đang online : <strong>$isonline</strong></li>
			<li>Ngày hôm nay : <strong>$today</strong></li>
			<li>Trong tháng này: <strong>$month</strong></li>
			<li>Tổng số lượt truy cập : <strong>$total</strong></li>


		</ul>";
    }
}
?>