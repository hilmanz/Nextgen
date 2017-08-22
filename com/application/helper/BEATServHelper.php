<?php

class BEATServHelper {
	function __construct($apps){
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
 	
		$this->config =$CONFIG;
		 
	}

	function checkReNLogin(){
			 
			$cred['username'] = $this->apps->_p('username');
			$cred['password'] = $this->apps->_p('password');
		
			return json_decode($this->curlPOST($this->config['BEAT_URL']."login/account/",$cred));
		 
	} 
	
	function checkProfile(){
			  
			return json_decode($this->curlPOST($this->config['BEAT_URL']."my/profile"));
		 
	}
	
	function branddetail(){
			  
			return json_decode($this->curlPOST($this->config['BEAT_URL']."my/branddetail"));
		 
	}
	
	function registration(){
			  
			return json_decode($this->curlPOST($this->config['BEAT_URL']."entourage/register"));
		 
	}
 	
	function offlinebadges(){
			// $promotionalsite = "http://preview.kanadigital.com/marlboro_inorout/public_html/service/";
			
			$promotionalsite	=strip_tags($this->apps->_p('promotionalsite'));
		 	$email	=strip_tags($this->apps->_p('email'));
			if(!$email) $email =  strip_tags($this->apps->_p('registrantmail'));
		 	$data['email']	=$email;
			$event = strip_tags($this->apps->_p('event'));
			if(!$event) $event = "GAMES ".strip_tags($this->apps->_p('gamesid'));
			$data['event']	=$event;
		
		
			// $promotionalsite = "http://preview.kanadigital.com/marlboro_inorout/public_html/service/"; 
			$promotionalsite = "http://localhost/marlboro-in-or-out/trunk/inorout/service/"; 
			
			if($promotionalsite=='marlboro') $promotionalsite = "http://preview.kanadigital.com/marlboro_inorout/public_html/service/"; 
			if($promotionalsite=='amild') $promotionalsite = "http://preview.kanadigital.com/marlboro_inorout/public_html/service/"; 
			  
			
		
			return json_decode($this->curlPOST($promotionalsite."synch/offline",$data));
		 
	}
 
	
	function curlPOST($url,$params="",$timeout=15){
		if($params) $data_string = http_build_query($params);
		$ipuser = sha1($_SERVER['REMOTE_ADDR']);
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);           
		curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		
		// curl_setopt($ch, CURLOPT_COOKIEFILE,	$this->config['LOG_DIR']."cookie{$ipuser}.txt");
		// curl_setopt($ch, CURLOPT_COOKIEJAR,  $this->config['LOG_DIR']."cookie{$ipuser}.txt");			
		
		curl_setopt($ch, CURLOPT_COOKIEFILE,	 "cookie{$ipuser}.txt");
		curl_setopt($ch, CURLOPT_COOKIEJAR,   "cookie{$ipuser}.txt");	
		
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);
		 // pr($response);
		curl_close ($ch);
		return $response;
	}
	
}
