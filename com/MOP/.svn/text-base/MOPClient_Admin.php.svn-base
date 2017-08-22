<?php 
global $ENGINE_PATH;
require_once $ENGINE_PATH.'Utility/nuSOAP/nusoap.php';
include_once $ENGINE_PATH."Utility/Debugger.php";
require_once $ENGINE_PATH."Utility/phpseclib/Math/BigInteger.php";
require_once $ENGINE_PATH."Utility/phpseclib/Crypt/Random.php";
require_once $ENGINE_PATH."Utility/phpseclib/Crypt/Hash.php";
require_once $ENGINE_PATH."Utility/phpseclib/Crypt/RSA.php";

class MOPClient extends SQLData{
	var $View;
	var $Config;
	var $client; //NuSOAP client
	var $msg;
	var $session;
	var $pageRef;
	
	function MOPClient($req,$forceDebug=1){
		parent::SQLData();
		$this->Request = $req;
		$this->forceDebug = $forceDebug;
		$this->View = new BasicView();
	
	}	
	function getConfig(){
		$this->open();
		$rs = $this->fetch("SELECT * FROM mop_config",1);
		for($i=0;$i<sizeof($rs);$i++){
			$list[$rs[$i]['configName']] = $rs[$i]['configValue'];
		}
		//print_r($list);
		$this->close();
			return $list;
	}
	
	function curlGet($req_url){
	
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		return $response;
	
	}
	
	function getsecrettoken($data=false){
		if($data==false) return false;
		
		global $CONFIG;	
		$privatekey = file_get_contents($CONFIG['LOCAL_ASSET'].'key/PublicKey.xml');
		$rsa = new Crypt_RSA();
		$rsa->loadKey($privatekey);
		$rsa->setPublicKey();
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$encryptedeviceid = $rsa->encrypt($data['snum']);
		$headers =  base64_encode($encryptedeviceid);
		return $headers;
		
	}
	
	function curlPOST($url,$params="",$credential=false,$header=true){
		
		global $CONFIG;
	
		
			$privatekey = file_get_contents($CONFIG['LOCAL_ASSET'].'key/PublicKey.xml');
			$rsa = new Crypt_RSA();
			$rsa->loadKey($privatekey);
			$rsa->setPublicKey();
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$encryptedeviceid = $rsa->encrypt($credential['serialNumber']);
			$headers =  base64_encode($encryptedeviceid);
		
		$data_string = http_build_query($params);		
		$ch = curl_init($url);                 
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);           
		curl_setopt($ch,CURLOPT_TIMEOUT,15);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$curlheader = array(             					
				"SecurityToken: ".$headers.""		
			); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $curlheader );
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec ($ch);
		$info = curl_getinfo($ch);		
		curl_close ($ch);
		return $response;
		
	}
	
	function curlXml($url,$param=false,$credential=false,$header=true){
		
		global $CONFIG;
		if(!$param) return false;
		if(!$credential) return false;
		
		if($header){
			
			$soapAction = $param['soapAction'];
			$soapRequest = $param['soapRequest'];
			if(array_key_exists("xml",$param)) $xml = $param['xml'];
			else $xml = "";
			
			$privatekey = file_get_contents($CONFIG['LOCAL_ASSET'].'key/PublicKey.xml');
			$rsa = new Crypt_RSA();
			$rsa->loadKey($privatekey);
			$rsa->setPublicKey();
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$encryptedeviceid = $rsa->encrypt($credential['serialNumber']);
			$headers =  base64_encode($encryptedeviceid);
		}
				
		$url = $CONFIG['MOP_URL'];
				
		$rawxml ='<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
		$rawxml .="<soap:Header>";
		$rawxml .='<SecurityToken xmlns="http://tempuri.org/"><token>'.$headers.'</token></SecurityToken>';
		$rawxml .="</soap:Header>";
		$rawxml .="<soap:Body>";
		$rawxml .='<'.$soapAction.' xmlns="http://tempuri.org/">';
		$rawxml .= $soapRequest;
		$rawxml .="</".$soapAction.">";
		$rawxml .="</soap:Body>";
		$rawxml .="</soap:Envelope>";
		// echo $rawxml;exit;
		$curlheader = array(             
				"Content-type: text/xml;charset=\"utf-8\"", 
				"Accept: text/xml",
				"SOAPAction: \"".$soapAction."\"",				
				"SecurityToken: ".$headers."",		
				"xml: ".$xml."",		
				"Content-length: ".strlen($rawxml).""				
			); 
	
		$ch = curl_init($url);  	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $curlheader );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $rawxml);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);			
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);		
		$body = substr($response, $header_size);		
		curl_close ($ch);
		// ob_clean();
		// header("Content-type: text/xml; charset=utf-8");
		// echo $response;
		// exit;
		return $response;
		
	}
	
	function checkReferral($url=null,$sessid=false,$crendetial=false){
		
		if($sessid==false) return false;
		global $CONFIG;
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		//$url = $this->getWSDL();
		if($url==null)$url = $CONFIG['MOP_URL'];
		$call_path = $url;
		$data['sessionID'] = $sessid;
		$req_url = $call_path."/AdminCheckReferral";
		$track_start = time();
		
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;

	}

	function CheckLogin($url=null,$username,$pwd,$crendetial=false){
		
		global $CONFIG;
	
		if($url==null) $url = $CONFIG['MOP_URL'];
		
		$call_path = $url;
		$data['userName'] = $username;
		$data['password'] = $pwd;
		$req_url = $call_path."/AdminCheckLogin";
		
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;
		 

	
	}
	
	function AdminEndSession($url=null,$sessionid=null,$crendetial=false){
		
		global $CONFIG;
	
		if($sessionid==null) return false;
		if($url==null) $url = $CONFIG['MOP_URL'];
	
		$call_path = $url;
		$data['sessionID'] = $sessionid;
		$req_url = $call_path."/AdminEndSession";
		
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;
		 

	
	}
	
	function registerAdminUser($type="AdminRegisterProfile",$url=null,$sessid=false,$xml=false,$crendetial=false){
		
		if($sessid==false) return false;
		if($xml==false) return false;
		global $CONFIG;
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		
		if($url==null)$url = $CONFIG['MOP_URL'];
		$call_path = $url;
		
		$data['sessionID'] = $sessid;
		$data['xml'] = $xml;
		
		$req_url = $call_path."/".$type;

		$track_start = time();
		
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;

	
	}
	
	
	function getProfileUser($url=null,$sessid=false,$xml=false,$crendetial=false){
		
		global $CONFIG;
		
		if($url==null) $url = $CONFIG['MOP_URL'];
		
		$call_path = $url;
		$req_url = $call_path."/AdminGetProfile";
				
		$data['sessionID'] = $sessid;
		$data['xml'] = $xml;
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;
		 

	
	}

	function searchProfileUser($url=null,$sessid=false,$xml=false,$crendetial=false){
		
		global $CONFIG;
		
		if($url==null) $url = $CONFIG['MOP_URL'];
	
		$call_path = $url;
		$req_url = $call_path."/AdminSearchProfile";
				
		$data['sessionID'] = $sessid;
		$data['xml'] = $xml;
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;
		 

	
	}
			
	function AdminGetProfileonGiid($url=null,$sessid=false,$xml=false,$crendetial=false){
		
		global $CONFIG;
	
		if($url==null) $url = $CONFIG['MOP_URL'];
		
		$call_path = $url;
		$req_url = $call_path."/AdminGetProfileOnGIID";
				
		$data['sessionID'] = $sessid;
		$data['xml'] = $xml;
	
		$response = $this->curlPOST($req_url,$data,$crendetial);
		
		return $response;
		 

	
	}	
	

	
	function registerDeviceAdmin($url=null,$credential=false){
		
		global $CONFIG;
		if($credential==null) return false;		
		if($url==null) $url = $CONFIG['MOP_URL'];
				
			
		$xmlformat ="<userName>".$credential['userName']."</userName>";
		$xmlformat .="<password>".$credential['password']."</password>";
		$xmlformat .="<deviceId>".$credential['deviceId']."</deviceId>";
		$xmlformat .="<deviceDescription>".$credential['deviceDescription']."</deviceDescription>";
		$xmlformat .="<serialNumber>".$credential['serialNumber']."</serialNumber>";
			
		$data['soapAction'] = "AdminRegisterDevice";		
		$data['soapRequest'] = $xmlformat;
		
		$response = $this->curlXml($url,$data,$credential);

		return $response;
		 

	
	}
		
}

?>
