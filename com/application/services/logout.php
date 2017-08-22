<?php
class logout extends ServiceAPI{
		
	function beforeFilter(){
	 
		// $this->deviceMopHelper = $this->useHelper('deviceMopHelper'); 
		
	}
	
	 
	
	function forces(){
		// $this->deviceMopHelper->endsession();
		//  
		
		$data['message'] = "success logout";
		$data['code'] = "1";
		$data['result'] = true;
		session_destroy();
		return $data;
	} 
	
	 
}
?>