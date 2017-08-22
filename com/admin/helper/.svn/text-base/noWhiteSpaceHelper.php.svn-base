<?php 

class noWhiteSpaceHelper extends Application{

	var $Request;
	
	function __construct($req){
	$this->Request = $req;
	
	}
	
	function noWhiteSpace($text, $first, $rest){
		if ((preg_match('/\s/',$text)) == 0){
			$countChar = intval(strlen($text));
			$wordWarpLoop = ceil($countChar/$first);
			$cutChar = $first;
			$startChar = 0;
			$addBR='';
			$x = 0;

			while($wordWarpLoop > 0){
				$arrWord[$x] = substr($text,$startChar,$cutChar);
				$wordWarpLoop--;
				if ($x == 0){
					$startChar = $startChar + $first;					
				}else{
					$startChar = $startChar + $rest;					
				}			
				$cutChar = $rest;
				$addBR .= $arrWord[$x]."<br>";
				$x++;
			}
			return $addBR;
		}else{
			return $text;
		}
	}
			
}	

?>

