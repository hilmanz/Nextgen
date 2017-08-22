<?php 
global $ENGINE_PATH;
require_once($ENGINE_PATH.'Utility/nuSOAP/nusoap.php');
include_once $ENGINE_PATH."Utility/Debugger.php";
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
		//$this->Config = $this->getConfig();
	
		//$this->init();	
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
	function init(){

		//phpinfo();

		

		$this->client = new nusoap_client($this->getWSDL(), true);

		$this->client->setUseCURL(false);

		//$this->client->setCurlOption();

		$this->client->setCredentials("kana@es-dm.com","Welc#ome","ntlm");

		$err = $this->client->getError();
		
		
	
		

		if($this->Config['MOP_DEBUG']=='1'&&$this->forceDebug==1){

		

			//do check login first

			$usr = $this->Config['MOP_DEBUG_USR'];

			$pwd = $this->Config['MOP_DEBUG_PWD'];

			

			$rq = $this->CheckLogin();
			
			if($rq==-1){

				$err = $this->client->getError();

				if ($err) {

				//	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

				//	echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->client->getDebug(), ENT_QUOTES) . '</pre>';
					echo "Server is busy, please try again later.";
					exit();

				}

				

			}else{
				//print "naaa";
				//print_r($rq);
				
				$this->session = $rq;

			}

			

		}

	}

	

	function getURL(){

		return $this->Config['MOP_URL'];

	}

	function getWSDL(){

		//print "http://".urlencode("test@es-dm.com").":".urlencode("p@ssw0rd123")."@testing-umild-id.es-dm.com/dm.mopid.webservice/centralwebservice.asmx?WSDL";

		//return "http://".urlencode("test@es-dm.com").":".urlencode("p@ssw0rd123")."@testing-umild-id.es-dm.com/dm.mopid.webservice/centralwebservice.asmx?WSDL";

		//return "testing.mop.es-dm.com";
		
		return $this->Config['MOP_WSDL_URL'];

		//return "http://testing-umild-id.es-dm.com/dm.mopid.webservice/centralwebservice.asmx?WSDL";

	}

	function getTimeOut(){

		return $this->Config['MOP_TIMEOUT'];

	}

	function isDebug(){

		return $this->Config['MOP_DEBUG'];

	}

	


	function getActiveUserName(){
		if(isset($_SESSION['MOP_SESSION'])){
			$user = $_SESSION['MOP_SESSION'];
			return $user['UserProfile']['Login'];
		}else return false;
	}

	function getNickName(){

		

	}

	function setSession($id){

		//$_SESSION['mop_session'] = $id;
		$_SESSION['mop_sess_id'] = $id;
	}

	function getSession(){
		
		return $_SESSION['mop_sess_id'];

	}

	function GetProfile($option,$sessid){

		 //$client = new nusoap_client($this->URI, true,false,false,false,false,60,60);

		 ini_set('max_execution_time',120);

		 $err = $this->client->getError();

			if ($err) {

			//echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

			//echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->client->getDebug(), ENT_QUOTES) . '</pre>';
			echo "Server is busy, please try again later.";
			exit();

			}
		print $sessid;
		$data = $this->client->call('GetProfile',array("option"=>$option,"sessionID"=>$sessid));
		var_dump($data);
		print $this->client->getError();
		if($data['GetProfile']['Result']==1){

			if($data['GetProfile']['OtherName']!=""){

			//	print "nickname -->".$data['GetProfile']['OtherName'];

				$_SESSION['MOP_NICK'] = $data['GetProfile']['OtherName'];

			}else{

				$_SESSION['MOP_NICK'] = $data['GetProfile']['FirstName'];

			}

		 	return $data['GetProfile'];

		 }else{

		 	return -1;

		 }

	}
	function checkReferral($sessid){

		global $CONFIG;
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		//$url = $this->getWSDL();
		$url = $CONFIG['MOP_URL'];
		$call_path = $url."/";
		$req_url = $call_path."CheckReferral?sessionID=".$sessid;
		$track_start = time();
			
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
		/**
		* Set the URL of the page or file to download.
		*/
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");

		/**
		* Ask cURL to return the contents in a variable
		* instead of simply echoing them to the browser.
		*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/**
		* Execute the cURL session
		*/
		$response = curl_exec ($ch);
		// $info = curl_getinfo($ch);
		// $strInfo = "-----\n";
		//foreach($info as $cc=>$dd){
		//	$strInfo.=$cc."->".$dd."\n";
		//}
		//$strInfo.="---------\n";
		/**
		* Close cURL session
		*/
		curl_close ($ch);
		//print $response;
	// pr($info);
		if($response==NULL){return -1;}
		$data = array();
		$data = $this->xml2array($response);
		foreach($data as $name=>$val){
			$data[str_replace("Class","",$name)]=$val;
		}


		$track_end = time();
		//$tracking_time = "CheckReferral -> ".($track_end - $track_start)."s\n".$strInfo;
		//$fp = fopen($CONFIG['LOG_DIR'].'mop_time.log','a+');
		//fwrite($fp,$tracking_time,strlen($tracking_time));
		//fclose($fp);
		$debug->addLog(" - CheckReferral - ",$this->getActiveUserName(),$req_url,
						"CheckReferral",$data['CheckReferral']['SessionID'],$data['CheckReferral']['Result']);
		if($data['CheckReferral']['Result']==1){

		 	return $data['CheckReferral']['SessionID'];

		}else if($data['CheckReferral']['SessionID']!=NULL&&$data['CheckReferral']['SessionID']>0){

			return $data['CheckReferral']['SessionID'];

		}else{
		 	return -1;
		}

	}

	function CheckLogin($username,$pwd){
		
		global $CONFIG;
		//$url = $this->getWSDL();
		$url = $CONFIG['MOP_URL']."/";
		//$call_path = str_replace("?WSDL","/",$url);
		$call_path = $url;
		$req_url = $call_path."CheckLogin?userName=".$username."&password=".$pwd;
		
		//$response = file_get_contents($req_url);
		
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
		/**
		* Set the URL of the page or file to download.
		*/
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");

		/**
		* Ask cURL to return the contents in a variable
		* instead of simply echoing them to the browser.
		*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/**
		* Execute the cURL session
		*/
		$response = curl_exec ($ch);
		
		/**
		* Close cURL session
		*/
		curl_close ($ch);
		//print $response;
		$data = array();
		$data = $this->xml2array($response);
		foreach($data as $name=>$val){
			$data[str_replace("Class","",$name)]=$val;
		}
		
		
		if($data['CheckLogin']['Result']==1){
		 	return $data['CheckLogin']['SessionID'];
		 }else{
		 	return -1;

		}
		 

		$data = $this->client->call('CheckLogin',array("userName"=>$this->Config['MOP_DEBUG_USR'],"password"=>$this->Config['MOP_DEBUG_PWD']));
		
	}
	function track($sessId,$PageRef,$ActivityName,$ActivityValue,$CPMOO,$user){
		global $CONFIG;
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		$track_start = time();
		 ini_set('max_execution_time',120);	

		//$url = $this->getWSDL();
		$url = $CONFIG['MOP_URL'];
		$call_path = $url."/";
		$req_url = $call_path."SendAction2";
		$foo = array("SessionID"=>$sessId,

														"ConsumerID"=>intval($user['ConsumerID']),

														"RegistrationID"=>intval($user['RegistrationID']),

														"ActivityDate"=>date("Y-m-d H:i:s"),

														"ActivityName"=>$ActivityName,

														"ActivityValue"=>$ActivityValue,

														"WebSessionLanguage"=>"",

														"Campaign"=>$CPMOO['Campaign'],

														"Phase"=>$CPMOO['Phase'],

														"Audience"=>$CPMOO['Audience'],

														"MediaCategory"=>$CPMOO['MediaCategory'],

														"OfferCategory"=>$CPMOO['OfferCategory'],

														"OfferCode"=>$CPMOO['OfferCode'],

														"CPAOType"=>$CPMOO['CPAOType'],

														"siteID"=>$CPMOO['siteID']);
		/*$strParams="SessionID=".$sessId;
		foreach($foo as $name=>$val){
				$strParams.="&".$name."=".$val;
		}
		*/
			
		$strParams = http_build_query($foo);
		//print $strParams;
		//$req_url.=$strParams;
		
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
		/**
		* Set the URL of the page or file to download.
		*/
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");
		curl_setopt($ch,CURLOPT_POSTFIELDS,$strParams);


		/**
		* Ask cURL to return the contents in a variable
		* instead of simply echoing them to the browser.
		*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/**
		* Execute the cURL session
		*/
		$response = curl_exec ($ch);
		//$info = curl_getinfo($ch);
		//$strInfo = "-----\n";
		//foreach($info as $cc=>$dd){
		//	$strInfo.=$cc."->".$dd."\n";
		//}
		//$strInfo.="---------\n";
		// echo 'Curl error: ' . curl_error($ch);
		/**
		* Close cURL session
		*/
		curl_close ($ch);
		//print $response;
		$data = array();
		$data = $this->xml2array($response);
		if(is_array($data)){
			foreach($data as $name=>$val){
				$data[str_replace("Class","",$name)]=$val;
			}
		}
		//print $response;
		//var_dump($data);
		if(@$data['SendAction']['SessionID']>0){
			
			$this->setSession($data['SendAction']['SessionID']);

		}
		$debug->addLog($CPMOO['OfferCode']." - ".$ActivityName."-".$ActivityValue,$this->getActiveUserName(),$req_url,
						"SendAction2",$data['SendAction']['SessionID'],$data['SendAction']['Result']);
		$track_end = time();
		//$tracking_time = "track -> ".($track_end - $track_start)."s\n".$strInfo;
		//$fp = fopen($CONFIG['LOG_DIR'].'mop_time.log','a+');
		//fwrite($fp,$tracking_time,strlen($tracking_time));
		//fclose($fp);
		return $data['SendAction'];

	}
	function GetProfile2($option,$sessid){
		global $CONFIG,$CPMOO;
		
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		
		$url = $CONFIG['MOP_URL'];
		$call_path = $url."/";
		$req_url = $call_path."GetProfile2?sessionID=".$sessid;
		
		//$response = file_get_contents($req_url);
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
		/**
		* Set the URL of the page or file to download.
		*/
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");

		/**
		* Ask cURL to return the contents in a variable
		* instead of simply echoing them to the browser.
		*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/**
		* Execute the cURL session
		*/
		$response = curl_exec($ch);
		
		/**
		* Close cURL session
		*/
		curl_close ($ch);
		//print $response;
		$data = array();
		$data = $this->xml2array($response);
		foreach($data as $name=>$val){
			$data[str_replace("Class","",$name)]=$val;
		}
		
		$debug->addLog($CPMOO['Phase']." - GetProfile",$this->getActiveUserName(),$req_url,
						"GetProfile",$data['GetProfile']['SessionID'],$data['GetProfile']['Result']);
		
		if($data['GetProfile']['Result']==1){
		
			if(array_key_exists('OtherName',$data['GetProfile'])){
				
				if($data['GetProfile']['OtherName']!=""){

				//	print "nickname -->".$data['GetProfile']['OtherName'];

					if(array_key_exists('OtherName',$data['GetProfile'])) $_SESSION['MOP_NICK'] = $data['GetProfile']['OtherName'];
					
				}else{

					if(array_key_exists('FirstName',$data['GetProfile'])) $_SESSION['MOP_NICK'] = $data['GetProfile']['FirstName'];

				}
			}
			
		 	return $data['GetProfile'];

		 }else{

		 	return -1;

		 }
		 

	}
	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;


            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
} 
	function XMLToArrayFlat($xml, &$return, $path='', $root=false)
{
    $children = array();
    if ($xml instanceof SimpleXMLElement) {
        $children = $xml->children();
        if ($root){ // we're at root
            $path .= '/'.$xml->getName();
        }
    }
    if ( count($children) == 0 ){
        $return[$path] = (string)$xml;
        return;
    }
    $seen=array();
    foreach ($children as $child => $value) {
        $childname = ($child instanceof SimpleXMLElement)?$child->getName():$child;
        if ( !isset($seen[$childname])){
            $seen[$childname]=0;
        }
        $seen[$childname]++;
        XMLToArrayFlat($value, $return, $path.'/'.$child.'['.$seen[$childname].']');
    }
} 
	function SendAction($pid,$sessid){

		 //$client = new nusoap_client($this->URI, true,false,false,false,false,60,60);

		 ini_set('max_execution_time',120);

		 $err = $this->client->getError();

			if ($err) {

			//echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

			//echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->client->getDebug(), ENT_QUOTES) . '</pre>';
			echo "Server is busy, please try again later.";
			exit();

			}

		$data = $this->client->call('GetProfile',array("option"=>$option,"sessionID"=>$sessid));

		if($data['GetProfile']['Result']==1){

		 	return $data['GetProfile'];

		 }else{

		 	return -1;

		 }

	}

	function getUserInfo(){

		$id = htmlspecialchars(mysql_escape_string($this->getActiveUserID()));

		$this->open();

		$rs = $this->fetch("SELECT * FROM mop_user WHERE registrationID='".$id."' LIMIT 1");

		$this->close();

		$rs['username'] = $_SESSION['MOP_LID'];

		$rs['nickname'] = $_SESSION['MOP_NICK'];

		

		return $rs;

	}

	function updateProfile($data){

		$this->open();

		$RegistrationID = $data['RegistrationID'];

		$FirstName = $data['FirstName'];

		$LastName = $data['LastName'];

		$CityID = trim($data['CityID']);

		$StateID = trim($data['StateID']);

		$Email = $data['Email'];

		$ConsumerID = $data['ConsumerID'];

		$OtherName = $data['OtherName'];

		$rs = $this->fetch("SELECT COUNT(*) as total FROM mop_user WHERE RegistrationID='".$data['RegistrationID']."' LIMIT 1");

		if($rs['total']==0){

			$this->query("INSERT INTO mop_user(RegistrationID,FirstName,LastName,CityID,StateID,Email,ConsumerID,OtherName)

						  VALUES('".$RegistrationID."','".$FirstName."','".$LastName."',

						  		'".$CityID."','".$StateID."','".$Email."','".$ConsumerID."','".$OtherName."')");

		}

		$this->close();

	}

	function getActiveUserID(){

		return $_SESSION['mop_rid'];

	}

	/**

		this method is executed in every pages.

	 */

	function check(){

		$debug = new Debugger();

		$debug->enable(1);

		//check time

		if($this->getTimeElapsed()>=$this->Config['MOP_TIMEOUT']){

			$sess = $this->checkReferral($this->getSession());

			

			

			$debug->addLog("CheckReferral",$this->getActiveUserName(),$this->getWSDL(),"CheckReferral",$sess,$sess);

			if($sess==-1){

				$debug->addLog("Redirect to MOP Site",$this->getActiveUserName(),$this->getWSDL(),"N/A","N/A","N/A");

				//sendRedirect($this->getURL()."?PromoRef=".$this->getPageRef());

				die();

			}else{

				//update session

				$this->setSession($sess);

				$debug->addLog("Reset Time",$this->getActiveUserName(),$this->getWSDL(),"N/A",$sess,$sess);

				//update session time

				$this->resetTime();

				return true;

			}

		}else{

			if($this->getTimeElapsed()==-1){

				$debug->addLog("Redirect to MOP Site",$this->getActiveUserName(),$this->getWSDL(),"N/A","N/A","N/A");

				//sendRedirect($this->getURL()."?PromoRef=".$this->getPageRef());

			}else if($this->getSession()==0){

				$this->setSession(NULL);

				$debug->addLog("Redirect to MOP Site",$this->getActiveUserName(),$this->getWSDL(),"N/A","N/A","N/A");

				//sendRedirect($this->getURL()."?PromoRef=".$this->getPageRef());

			}else{

				//print "ooo".$this->getTimeElapsed();

				return true;

			}

		}

	}

	function getTimeElapsed(){

		//time format here.

		if($_SESSION['mop_session_time']!=NULL){

			$curr = array(date("H"),date("i"),date("s"));

			$foo = explode(":",$_SESSION['mop_session_time']);

			if($curr[0]==$foo[0]){

				return ($curr[1]-$foo[1]);

			}else{

				if($curr[0]>$foo[0]){

					return ($curr[1]+(60-$foo[1]));

				}else{

					return -1;

				}

			}

		}else{

			return -1;

		}

	}

	function resetTime(){

		$_SESSION['mop_session_time'] = date("H:i:s");

	}

	function logout($sessid){
		global $CONFIG;
		
		
		$debug = new Debugger();
		$debug->setDirectory($CONFIG['LOG_DIR']);
		$debug->enable(true);
		
		$url = $CONFIG['MOP_URL'];
		$call_path = $url."/";
		$req_url = $call_path."EndSession?sessionID=".$sessid;
		//print $req_url;
		//$response = file_get_contents($req_url);
		/**
		* Initialize the cURL session
		*/
		$ch = curl_init();
		/**
		* Set the URL of the page or file to download.
		*/
		curl_setopt($ch, CURLOPT_URL,$req_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); 
		curl_setopt($ch, CURLOPT_USERPWD, "hosting\pmimopID:Pm1jkd!");

		/**
		* Ask cURL to return the contents in a variable
		* instead of simply echoing them to the browser.
		*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		/**
		* Execute the cURL session
		*/
		$response = curl_exec($ch);
		
		/**
		* Close cURL session
		*/
		curl_close ($ch);
		//print $response;
		$data = array();
		$data = $this->xml2array($response);
		foreach($data as $name=>$val){
			$data[str_replace("Class","",$name)]=$val;
		}
		
		//var_dump($data);
		//die();
		if($data['EndSession']['Result']=="1"||$data['EndSession']['Result']=="99"){
			$debug->addLog("LOGOUT",$this->getActiveUserName(),$req_url,"EndSession","N/A","N/A");
			
		 	return $data['EndSession'];

		 }else{

		 	return -1;

		 }
		 
		/*
		 ini_set('max_execution_time',120);

		 $err = $this->client->getError();

			if ($err) {

			//echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';

			//echo '<h2>Debug</h2><pre>' . htmlspecialchars($this->client->getDebug(), ENT_QUOTES) . '</pre>';
			echo "Server is busy, please try again later.";
			exit();

			}

		$data = $this->client->call('EndSession',array("sessionID"=>$this->getSession()));

		//print_r($data);

		//if($data['EndSession']['Result']=="1"||$data['EndSession']['Result']=="99"){

			session_destroy();

			return true;

		//}else{

		//	return false;

		//}
		*/
	}

	

	function setPageRef($id){

		$this->pageRef = $id;

	}

	function getPageRef(){

		return $this->pageRef;

	}

	//admin functions

	function admin(){

		

	}
	/***helpers***/
		function xml2ary(&$string) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $string, $vals, $index);
		xml_parser_free($parser);
	
		$mnary=array();
		$ary=&$mnary;
		foreach ($vals as $r) {
			$t=$r['tag'];
			if ($r['type']=='open') {
				if (isset($ary[$t])) {
					if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
					$cv=&$ary[$t][count($ary[$t])-1];
				} else $cv=&$ary[$t];
				if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
				$cv['_c']=array();
				$cv['_c']['_p']=&$ary;
				$ary=&$cv['_c'];
	
			} elseif ($r['type']=='complete') {
				if (isset($ary[$t])) { // same as open
					if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
					$cv=&$ary[$t][count($ary[$t])-1];
				} else $cv=&$ary[$t];
				if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
				$cv['_v']=(isset($r['value']) ? $r['value'] : '');
	
			} elseif ($r['type']=='close') {
				$ary=&$ary['_p'];
			}
		}    
		
		_del_p($mnary);
		return $mnary;
	}
	
	// _Internal: Remove recursion in result array
	function _del_p(&$ary) {
		foreach ($ary as $k=>$v) {
			if ($k==='_p') unset($ary[$k]);
			elseif (is_array($ary[$k])) _del_p($ary[$k]);
		}
	}
	
	// Array to XML
	function ary2xml($cary, $d=0, $forcetag='') {
		$res=array();
		foreach ($cary as $tag=>$r) {
			if (isset($r[0])) {
				$res[]=ary2xml($r, $d, $tag);
			} else {
				if ($forcetag) $tag=$forcetag;
				$sp=str_repeat("\t", $d);
				$res[]="$sp<$tag";
				if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
				$res[]=">".((isset($r['_c'])) ? "\n" : '');
				if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
				elseif (isset($r['_v'])) $res[]=$r['_v'];
				$res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
			}
			
		}
		return implode('', $res);
	}
	
	// Insert element into array
	function ins2ary(&$ary, $element, $pos) {
		$ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
		$ary=array_merge($ar1, array_slice($ary, $pos));
	}
	function getEmbedScript(){
		return "";
	}

}

?>
