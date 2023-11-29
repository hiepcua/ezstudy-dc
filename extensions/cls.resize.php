<?php
class Image
{
    var $SrcFile = false;
    var $DestFile = false;
    var $Quality = 100;
    var $NewWidth = 0; 
    var $NewHeight = 0;
    var $WidthPercent = 0;
    var $HeightPercent = 0;
	function GetType()
    {
        $arr['mime'] = false;
        $arr = getimagesize($this->SrcFile);
        $type = 0;
        switch($arr['mime'])
        {
            case 'image/pjpeg':
				$type = 1;
                break;
			case 'image/jpeg':
                $type = 1;
                break;
            case 'image/gif':
                $type = 2;
                break;
			case 'image/x-png':
				$type = 3;
                break;
            case 'image/png':
                $type = 3;
                break;
      		case 'application/rar':
                $type = 4;
                break;		
            default:
                $type = 0;
                break;
        }
        if($type > 0)
            return $type;
        else
            die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi Image->GetType()");
    }

	function GetWidth(){
        $arr[0] = 0;
        $arr = getimagesize($this->SrcFile);
        if(intval($arr[0]) > 0)
            return intval($arr[0]);
        else
            die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi Image->GetWidth()");
    }
	function GetHeight() //Hàm l?y chi?u cao c?a ?nh g?c
    {
        $arr[1] = 0;
        $arr = getimagesize($this->SrcFile);
        if(intval($arr[1]) > 0)
            return intval($arr[1]);
        else
            die("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">File nguồn không tồn tại hoặc không phải định dạng cho phép !. Lỗi Image->GetHeight()");
    }
	function LoadImageFromFile()//Ham t?o m?t ?nh vào trong b? nh? t? file ngu?n - tr? v? ð?a ch? vùng nh? ch?a anh dc t?o
    {
        $type = $this->GetType();
        $img = false;
        switch($type)
        {
            case 1:
                $img = imagecreatefromjpeg($this->SrcFile);
                break;
            case 2:
                $img = imagecreatefromgif($this->SrcFile);
                break;
            case 3:
                $img = imagecreatefrompng($this->SrcFile);
                break;
        }
        return $img;
    }
	function NewImage($W, $H){
        if($this->GetType() != 2)
		    @$imgNew = imagecreatetruecolor($W,$H);//Dung cho gif - chua ho tro gif nen gif se khong transfer
        else
            $imgNew = imagecreate($W, $H);
        @$white = imagecolorallocate($imgNew, 255, 255, 255);//Dung cho PNG
        @imagefilledrectangle( $imgNew, 0, 0, $W, $H, $white);//Dung cho PNG
        return $imgNew;
    }
	function CopyImage($Src, $Dest, $Width, $Height) //Hàm copy và resize t? ?nh có ð?a ch? trong b? nh? $Src t?i ?nh có ð?a ch? $Dest
    {
		$cur_w=$this->GetWidth(); $dx=$cur_w/$Width;
		$cur_h=$this->GetHeight(); $dy=$cur_h/$Height;
		$newx=$Width/2;
		$newh=$Height/2;
		if($dx>$dy)
		{
			$Height=$cur_h/$dx;
		}else{
			$Width=$cur_w/$dy;
		}
		$newx-=$Width/2; $newx=ceil($newx);
		$newh-=$Height/2; $newh=ceil($newh);
		imagecopyresized($Dest, $Src,$newx,$newh,0,0, $Width, $Height, $this->GetWidth(), $this->GetHeight());
    }

	function SaveFile($Src, $Dest)//Hàm ghi thành file n?u c?n
    {
        $type = $this->GetType();
        switch($type)
        {
            case 1:
                imagejpeg($Dest, $this->DestFile, $this->Quality);
                break;
            case 2:
                if(function_exists('imagegif')) //PHP < 5 no support
                    imagegif($Dest, $this->DestFile, $this->Quality);
                else
                    imagejpeg($Dest, $this->DestFile, $this->Quality);
                break;
            case 3:
				if(function_exists('function.imagepng'))
				{
					$this->Quality=9;
                	imagepng($Dest, $this->DestFile, $this->Quality);
				}
				else
					imagejpeg($Dest, $this->DestFile, $this->Quality);
                break;
        }
    }
	function FreeMemory($Src, $Dest)//Hàm gi?i phóng b? nh? ch?a h?nh ?nh ngu?n và ðích
    {
        imagedestroy($Src);
        imagedestroy($Dest);
    }
	function SaveFileWH()//Hàm tr? v? file ?nh ðý?c resize v?i Width và Height do ta ch? ð?nh
    {
        $img = false;
        $imgNew = false;
        $img = $this->LoadImageFromFile();
        $imgNew = $this->NewImage($this->NewWidth, $this->NewHeight);
        $this->CopyImage($img, $imgNew, $this->NewWidth, $this->NewHeight);
        $this->SaveFile($img, $imgNew);
        $this->FreeMemory($img, $imgNew);
    }
	function SaveFileW()
    {
        $oldW = $this->GetWidth();
        $oldH = $this->GetHeight();
        $newW = $this->WidthPercent;
        $newH = $newW*($oldH/$oldW);
        $img = false;
        $imgNew = false;
        $img = $this->LoadImageFromFile();
        $imgNew = $this->NewImage($newW, $newH);
        $this->CopyImage($img, $imgNew, $newW, $newH);
        $this->SaveFile($img, $imgNew);
        $this->FreeMemory($img, $imgNew);
    }
	function SaveFileH(){
        $oldW = $this->GetWidth();
        $oldH = $this->GetHeight();
        $newH = $this->HeightPercent;
        $newW = $newH*($oldW/$oldH);
        $img = false;
        $imgNew = false;
        $img = $this->LoadImageFromFile();
        $imgNew = $this->NewImage($newW, $newH);
        $this->CopyImage($img, $imgNew, $newW, $newH);
        $this->SaveFile($img, $imgNew);
        $this->FreeMemory($img, $imgNew);
    }
}
function getImageWidth($FileName){
    if(!file_exists($FileName)) return false;
    $arr = getimagesize($FileName);
    return $arr[0];
}
function getImageHeight($FileName)
{
    if(!file_exists($FileName)) return false;
    $arr = getimagesize($FileName);
    return $arr[1];
}
?>