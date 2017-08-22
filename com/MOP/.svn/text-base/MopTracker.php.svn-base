<?php 
class MopTracker{
	var $strHTML = "";
	function __construct(){
		
	}
	function track($sessId,$PageRef,$ActivityName,$ActivityValue,$CPMOO,$user){
		$params = array('sessId'=>$sessId,"PageRef"=>$PageRef,"ActivityName"=>$ActivityName,"ActivityValue"=>$ActivityValue,"CPMOO"=>$CPMOO,"user"=>$user,"handler"=>session_id());
		$r = urlencode64(serialize($params));
		$this->strHTML.="document.write(\"<img src='img.php?r=".$r."'/>\");";
	}
	function getEmbedScript(){
		
		return $this->strHTML;
	}
	function trackGame($sessId,$PageRef,$ActivityName,$ActivityValue,$CPMOO,$user){
		$params = array('sessId'=>$sessId,"PageRef"=>$PageRef,"ActivityName"=>$ActivityName,"ActivityValue"=>$ActivityValue,"CPMOO"=>$CPMOO,"user"=>$user,"handler"=>session_id());
		$r = urlencode64(serialize($params));
		//$this->strHTML.="document.write('<img src=\'img.php?r=".$r."\'/>');";
		return $r;
	} 
}
?>
