<?php
class curl_class {

	function get($url){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_TIMEOUT,15);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
		
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		return $response;
	}


	function post($url,$params="",$timeout=15){
		$data_string = http_build_query($params);
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);           
		curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);
		print_r($info);
		curl_close ($ch);
		return $response;
	}

}
