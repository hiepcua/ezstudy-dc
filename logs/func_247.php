<?php
function getCauhoi($url,$grade,$subject,$n_unit,$lesson_id){
	$html_string=file_get_contents($url);
	$dom = new DOMDocument();
	@$dom->loadHTML($html_string);
	$arr=$dom->getElementsByTagName('a');
	$n_exer=0;
	foreach($arr as $link) {
		$class=$link->getAttribute('class');
		if(strpos($class,'box-cauhoi')>0){
			$n_exer++;
			$item=array();
			$item['url'] = $link->getAttribute('href');
			$item['title']= $link->textContent;
			$item['guide']=getGuide($item['url'],$lesson_id.'.'.$n_exer);
			$arr_add=array();
			$arr_add['id']=$lesson_id.'.'.$n_exer;
			$arr_add['grade']=$grade;
			$arr_add['subject']=$subject;
			$arr_add['unit']=$n_unit;
			$arr_add['lesson']=$lesson_id;
			$arr_add['title']=strip_tags($item['title']);
			$arr_add['content']=addslashes($item['guide']['quiz']);
			$arr_add['guide']=addslashes($item['guide']['anser']);
			$arr_add['status']='L0';
			SysAdd('ez_grade_subjects_exercise',$arr_add);
		}
	}
}
function getGuide($url,$id){
	$html_string=file_get_contents($url);
	$dom = new DOMDocument();
	@$dom->loadHTML($html_string);
	$tags=$dom->getElementsByTagName('div');
	foreach($tags as $tag) {
		$class=$tag->getAttribute('class');
		if(strpos($class,'item-cauhoi-answer')>0){
			$nds=$tag->getElementsByTagName('div');
			$item=array();
			foreach($nds as $nd){
				$s_class=$nd->getAttribute('class');
				$imgs = $nd->getElementsByTagName('img');
				$dir="asset/$id/";
				if(!is_dir($dir) && $imgs->length>0) mkdir($dir,0755,true);
				$n_img=0;
				foreach($imgs as $img){
					$n_img++;
					$src=$img->getAttribute('src');
					$exten=explode('.',$src);
					$imgLink=$dir.$n_img.'.'.end($exten);
					file_put_contents($imgLink, file_get_contents($src));
					$img->setAttribute('src', $imgLink);
				}
				if(strpos($s_class,'i-content')!==false){
					$item['quiz']= $dom->saveHTML($nd);
				}
				if(strpos($s_class,'i-answer-content')!==false){
					$anser=$nd->getElementsByTagName('p');
					$html="";
					$n=$anser->length ;
					for($i=0;$i<$n-2;$i++){
						$p=$anser[$i];
						$html.=$dom->saveHTML($p);
					}
					$item['anser']=$html;
				}
				if(count($item)==2){
					return $item;
				}
			}
		}
	}
	return '';
}
?>