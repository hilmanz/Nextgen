<?php
class example_service {
	var $API;

	
	function __construct($API=null){
		$this->API = $API;
		$this->user = $this->API->getUserOnline();
	}
	
	function checkOnline(){
			print_r($this->API->getUserOnline());exit;
	
	}
	
	function getAccessToken(){
		global $CONFIG;
		
		$at = get_access_token($this->user->id,$CONFIG['SERVICE_KEY']);
		return  $this->API->toJson(1,'access',$at);
	}
	
		
}
?>
