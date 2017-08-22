<?php
include_once "common.php";
//$SMAC_HASH = sha1("smarty_link");
//$SMAC_SECRET = "smarty_link";
//print $SMAC_HASH;
//print $SMAC_SECRET;
//$p = urlencode64('http://a1.twimg.com/profile_images/1540473809/326475609_normal.jpg');
//print $p."<br/>";
$url = urldecode64($_GET['p']);
if(eregi("jpg",$url)){
	$ctype = "image/jpg";
	$ext = ".jpg";
}else if(eregi("gif",$url)){
	$ctype = "image/gif";
	$ext = ".gif";
}else{
	$ctype = "image/png";
	$ext = ".png";
}

$url_file = md5($url);
if(!is_dir('../assets')){
	mkdir('../assets');
}

if(eregi("http://",$url)){
	header("Location:".$url);
	/*
	if(file_exists('../assets/'.$url_file.".".$ext)){
		$resp = file_get_contents('../assets/'.$url_file.".".$ext);
		
	}else{
		
		$c = curl_init();
		//make sure to get timed out after 15 seconds pass.
		curl_setopt($s,CURLOPT_TIMEOUT,15); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        $resp = curl_exec($c);
        curl_close($c);
       
		if(strlen($resp)>0){
			$fp = fopen('../assets/'.$url_file.".".$ext,"w+");
			fwrite($fp,$resp,strlen($resp));
			fclose($fp);
		}
	}
	*/
}
/*
header("Content-Type: $ctype"); 
print $resp;
exit();
*/
?>