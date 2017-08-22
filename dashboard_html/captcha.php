<?php
	// generate random number and store in session
	session_start();
	
	$randomnr = rand(100000, 999999);
	$_SESSION['codeBookCaptcha'] = md5($randomnr);
 
	//generate image
	$im = imagecreatetruecolor(176, 76);
 
	//colors:
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
 
	imagefilledrectangle($im, 0, 0, 176, 76, $white);
 
	//path to font:
 
	$font = 'Candice.ttf';
 
	//draw text:
	imagettftext($im, 52, 0, -7, 68, $grey, $font, $randomnr);
 
	imagettftext($im, 34, 0, 7, 48, $black, $font, $randomnr);
 
	// prevent client side  caching
	header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
 
	//send image to browser
	header ("Content-type: image/gif");
	imagegif($im);
	imagedestroy($im);
	?>