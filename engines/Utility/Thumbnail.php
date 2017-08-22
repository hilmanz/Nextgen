<?php
/**
 *	thumbnail class
 *  purpose : the Class for making a Thumbnail..
 */
 class Thumbnail{
 	var $_GD_Version; //current version of GD
	function Thumbnail(){
		$system = gd_info();
		if(eregi("2\.",$system["GD Version"])){
			$this->_GD_Version = "2";
		}else{
			$this->_GD_Version = "1";
		}
	}
	function getGDVersion(){
		return $this->_GD_Version;
	}
	function _IsJPEG($filename){
		if(eregi("\.jpg|\.jpeg",$filename)){
			return true;
		}
		
	}
	function _IsGIF($filename){
		if(eregi("\.gif",$filename)){
			return true;
		}
		
	}
	function _IsPNG($filename){
		if(eregi("\.png",$filename)){
			return true;
		}
		
	}
	function createThumbnail($filename,$thumb_name,$width,$height){
		if($this->_IsJPEG($filename)){
			 $src_img=imagecreatefromjpeg($filename); 
			 $oldWidth = imageSX($src_img);
			 $oldHeight = imageSY($src_img);
			 //reformat the Width
			 if($oldWidth>$oldHeight){
			 	$newWidth = $width;	
				$newHeight = $oldHeight*($width/$oldWidth);
			 }else{
			 	
				 $newWidth =  $oldWidth*($height/$oldHeight);	
				$newHeight = $height;	
				
			 }	
			if($this->getGDVersion()=="2"){
				$dst_img=ImageCreateTrueColor($newWidth,$newHeight);
		        imagecopyresampled($dst_img,$src_img,0,0,0,0,$newWidth,$newHeight,$oldWidth,$oldHeight); 
			}else{
				$dst_img=ImageCreate($newWidth,$newHeight);
        		imagecopyresized($dst_img,$src_img,0,0,0,0,$newWidth,$newHeight,$oldWidth,$oldHeight); 
			}
			//bungkus !!!
			$ret = imagejpeg($dst_img,$thumb_name); 
			//clean up
			imagedestroy($dst_img);
    		imagedestroy($src_img); 
			return $ret;
		}else if($this->_IsGIF($filename)){
			$src_img=imagecreatefromgif($filename); 
			 $oldWidth = imageSX($src_img);
			 $oldHeight = imageSY($src_img);
			 //reformat the Width
			 if($oldWidth>$oldHeight){
			 	$newWidth = $width;	
				$newHeight = $oldHeight*($width/$oldWidth);
			 }else{
			 	if($height>$oldHeight){
					$newWidth = $width;	
					$newHeight = $oldHeight*($width/$oldWidth);
				}else{
				 	$newWidth =  $oldWidth*($height/$oldHeight);	
					$newHeight = $height;	
				}
			 }	
			
			$dst_img=ImageCreate($newWidth,$newHeight);
        	imagecopyresized($dst_img,$src_img,0,0,0,0,$newWidth,$newHeight,$oldWidth,$oldHeight); 
			//bungkus !!!
			$ret = imagegif($dst_img,$thumb_name); 
			//clean up
			imagedestroy($dst_img);
    		imagedestroy($src_img); 
			return $ret;
		}else if($this->_IsPNG($filename)){
			$src_img=imagecreatefrompng($filename); 
			
			 $oldWidth = imageSX($src_img);
			 $oldHeight = imageSY($src_img);
			 //reformat the Width
			 if($oldWidth>$oldHeight){
			 	$newWidth = $width;	
				$newHeight = $oldHeight*($width/$oldWidth);
			 }else{
			 	if($height>$oldHeight){
					$newWidth = $width;	
					$newHeight = $oldHeight*($width/$oldWidth);
				}else{
				 	$newWidth =  $oldWidth*($height/$oldHeight);	
					$newHeight = $height;	
				}
			 }	
			
			// $dst_img=ImageCreate($newWidth,$newHeight);
        	// imagecopyresized($dst_img,$src_img,0,0,0,0,$newWidth,$newHeight,$oldWidth,$oldHeight); 
			$dst_img=ImageCreateTrueColor($newWidth,$newHeight);
			imagealphablending($dst_img, false);
			imagesavealpha($dst_img,true);
			$transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
			imagefilledrectangle($dst_img, 0, 0, $nWidth, $nHeight, $transparent);
		    imagecopyresampled($dst_img,$src_img,0,0,0,0,$newWidth,$newHeight,$oldWidth,$oldHeight); 
			//bungkus !!!
			$ret = imagePNG($dst_img,$thumb_name); 
			//clean up
			imagedestroy($dst_img);
    		imagedestroy($src_img); 
			return $ret;
		}
	}
 }
?>