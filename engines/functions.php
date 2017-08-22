<?php

/**
 * 
 * check if the url is valid
 * @param $url
 * @return boolean
 */
function isValidUrl($url){
	$url = mysql_escape_string($url);
	$hostname = str_replace("http://","", $url);
	$hostname = str_replace("https://","",$hostname);
	$foo = explode("/",$hostname);
	$hostname = $foo[0];
	//print $hostname."<br/>";
	
	if(checkdnsrr($hostname,"A")){
		return true;
	}else{
		return false;
	}
}


//end dropdown

function clean($str){
	return mysql_escape_string($str);
}
/**
 * 
 * clean string from mysql related keywords
 * @param $str
 */
function cleanString($str){
	$str = eregi_replace("INSERT","", $str);
	$str = eregi_replace("DROP","", $str);
	$str = eregi_replace("SELECT","", $str);
	// $str = eregi_replace("DELETE","",$str);
	$str = eregi_replace("UPDATE","",$str);
	$str = eregi_replace("UNION ALL","",$str);
	$str = eregi_replace("UNION","",$str);
	$str = eregi_replace("WHERE","",$str);
	$str = eregi_replace("AND","",$str);
	$str = eregi_replace("JOIN","",$str);
	return mysql_escape_string($str);
}
function sendRedirect($url){
	print' <meta http-equiv="refresh" content="1;URL='.$url.'" />';

}
function isImage($filename){
	if(eregi("\.jpeg|\.gif|\.jpg|\.png",$filename)){
		return true;
	}	
}
function LoadModule($moduleName,$req){
	global $APP_PATH,$ENGINE_PATH;
	$moduleName = mysql_escape_string($moduleName);
	if(file_exists($APP_PATH.$moduleName."/".$moduleName.".php")){
		include_once $APP_PATH.$moduleName."/".$moduleName.".php";	
		$obj = new $moduleName($req);	
		return $obj;
	}else{
		print "OBJECT NOT FOUND !";
		die();	
	}
}
function isLocal($filename){
	if(is_file($filename)){
		return true;
	}
}
/**
 * fungsi untuk mencari perbedaan tanggal.
 */
function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
{

  /*
  $interval can be:
  yyyy - Number of full years
  q - Number of full quarters
  m - Number of full months
  y - Difference between day numbers
  (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33".
                 The datediff is "-32".)
  d - Number of full days
  w - Number of full weekdays
  ww - Number of full weeks
  h - Number of full hours
  n - Number of full minutes
  s - Number of full seconds (default)
  */

  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds

  switch($interval) {
    case 'yyyy': // Number of full years
    $years_difference = floor($difference / 31536000);
    if (mktime(date("H", $datefrom),
                              date("i", $datefrom),
                              date("s", $datefrom),
                              date("n", $datefrom),
                              date("j", $datefrom),
                              date("Y", $datefrom)+$years_difference) > $dateto) {

    $years_difference--;
    }
    if (mktime(date("H", $dateto),
                              date("i", $dateto),
                              date("s", $dateto),
                              date("n", $dateto),
                              date("j", $dateto),
                              date("Y", $dateto)-($years_difference+1)) > $datefrom) {

    $years_difference++;
    }
    $datediff = $years_difference;
    break;

    case "q": // Number of full quarters
    $quarters_difference = floor($difference / 8035200);
    while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($quarters_difference*3),
                                   date("j", $dateto),
                                   date("Y", $datefrom)) < $dateto) {

    $months_difference++;
    }
    $quarters_difference--;
    $datediff = $quarters_difference;
    break;

    case "m": // Number of full months
    $months_difference = floor($difference / 2678400);
    while (mktime(date("H", $datefrom),
                                   date("i", $datefrom),
                                   date("s", $datefrom),
                                   date("n", $datefrom)+($months_difference),
                                   date("j", $dateto), date("Y", $datefrom)))
                        { // Sunday
    $days_remainder--;
    }
    if ($odd_days > 6) { // Saturday
    $days_remainder--;
    }
    $datediff = ($weeks_difference * 5) + $days_remainder;
    break;

    case "ww": // Number of full weeks
    $datediff = floor($difference / 604800);
    break;

    case "h": // Number of full hours
    $datediff = floor($difference / 3600);
    break;

    case "n": // Number of full minutes
    $datediff = floor($difference / 60);
    break;

    default: // Number of full seconds (default)
    $datediff = $difference;
    break;
  }

  return $datediff;
}

function getRealIP(){
  if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
  {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
  }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
  {
     $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

// Parameters:
// $text = The text that you want to encrypt.
// $key = The key you're using to encrypt.
// $alg = The algorithm.
// $crypt = 1 if you want to crypt, or 0 if you want to decrypt.

function cryptare($text, $key, $alg, $crypt)
{
    $encrypted_data="";
    switch($alg)
    {
        case "3des":
            $td = mcrypt_module_open('tripledes', '', 'ecb', '');
            break;
        case "cast-128":
            $td = mcrypt_module_open('cast-128', '', 'ecb', '');
            break;   
        case "gost":
            $td = mcrypt_module_open('gost', '', 'ecb', '');
            break;   
        case "rijndael-128":
            $td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
            break;       
        case "twofish":
            $td = mcrypt_module_open('twofish', '', 'ecb', '');
            break;   
        case "arcfour":
            $td = mcrypt_module_open('arcfour', '', 'ecb', '');
            break;
        case "cast-256":
            $td = mcrypt_module_open('cast-256', '', 'ecb', '');
            break;   
        case "loki97":
            $td = mcrypt_module_open('loki97', '', 'ecb', '');
            break;       
        case "rijndael-192":
            $td = mcrypt_module_open('rijndael-192', '', 'ecb', '');
            break;
        case "saferplus":
            $td = mcrypt_module_open('saferplus', '', 'ecb', '');
            break;
        case "wake":
            $td = mcrypt_module_open('wake', '', 'ecb', '');
            break;
        case "blowfish-compat":
            $td = mcrypt_module_open('blowfish-compat', '', 'ecb', '');
            break;
        case "des":
            $td = mcrypt_module_open('des', '', 'ecb', '');
            break;
        case "rijndael-256":
            $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
            break;
        case "xtea":
            $td = mcrypt_module_open('xtea', '', 'ecb', '');
            break;
        case "enigma":
            $td = mcrypt_module_open('enigma', '', 'ecb', '');
            break;
        case "rc2":
            $td = mcrypt_module_open('rc2', '', 'ecb', '');
            break;   
        default:
            $td = mcrypt_module_open('blowfish', '', 'ecb', '');
            break;                                           
    }
   
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    @mcrypt_generic_init($td, $key, $iv);
   
    if($crypt)
    {
        $encrypted_data = @mcrypt_generic($td, $text);
    }
    else
    {
        $encrypted_data = @mdecrypt_generic($td, $text);
    }
   
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
   
    return $encrypted_data;
} 
function convertBase64($str){
	$str = str_replace("=",".",$str);
	$str = str_replace("+","-",$str);
	$str = str_replace("/","_",$str);
	return $str;
}
function realBase64($str){
	$str = str_replace(".","=",$str);
	$str = str_replace("-","+",$str);
	$str = str_replace("_","/",$str);
	return $str;
}
function urlencode64($str){
	global $SMAC_HASH;
	$key = $SMAC_HASH;
	$hash = cryptare($str,$key,'des',1);
	$str = convertBase64(base64_encode($hash));
	return $str;
}
function urldecode64($str){
	global $SMAC_HASH;
	$key = $SMAC_HASH;
	$secret = base64_decode(realBase64($str));
	$str = cryptare($secret,$key,'des',0);
	return trim($str);
}
function gettokenize($expiretime=5000,$userid=null){
	if($userid==null) return false;
	$secrettoken['datetime'] = time()+$expiretime;		
	$secrettoken['userid'] = $userid;
	$tokenize = urlencode64(serialize($secrettoken));	
	return $tokenize;
	
}
function cektokenize($tokenize=null,$userid=null){
		if($tokenize==null) return false;
		if($userid==null) return false;
		$arrtoken = unserialize(urldecode64($tokenize));
		if(is_array($arrtoken)){
			if(array_key_exists('datetime',$arrtoken)){
				$subtime = intval($arrtoken['datetime']-time()); 
				if($subtime<=0) return false;
			}else return false;
			
			if(array_key_exists('userid',$arrtoken)){
				if($userid!=$arrtoken['userid']) return false;
			}else return false;	
			
			return true;
		}else return false;
		return false;
		
		
}

function get_correct_utf8_mysql_string($s) 
{ 
    if(empty($s)) return $s; 
    $s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]| 
[\xC2-\xDF][\x80-\xBF]| 
\xE0[\xA0-\xBF][\x80-\xBF]| 
[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}| 
\xED[\x80-\x9F][\x80-\xBF]#x", $s, $m ); 
    return implode("",$m[0]); 
}
function is_token_valid($token){
	$salt = urlencode64($_COOKIE['PHPSESSID']);
	$data = json_decode(urldecode64($_SESSION[$salt]));
	$secret = urlencode64($_COOKIE['PHPSESSID'].$token);
	if($data->token==$token&&$data->secret==$secret){
		$_SESSION[$salt] = null;
		return true;
	}
}
/**
 * SMAC formated number
 * @return String
 */
function smac_number($n){
	$num = abs($n);
	if($num>1000000){
		$str = round($n/1000000)."M";
	}else if($num>1000){
		$str = round($n/1000)."K";
	}else{
		$str = @number_format($n);
	}
	return $str;
}

/**
 * @param $str
 * @param $flag ALPHANUMERIC,CHAR_AND_NUMERIC,CHARACTER_ONLY,NUMERIC_ONLY,EMAIL,NOT_EMPTY
 */
function validate($str,$flag='NOT_EMPTY'){
	switch($flag){
		case 'NOT_EMPTY':
			if(strlen($str)>0){
				return true;
			}
		break;
		case 'EMAIL':	
			if (ereg("^.+@.+..+$", $str)) { 
				return true;
			}
		break;
		case 'NUMERIC_ONLY':
			
			if(eregi("^([0-9]+)$",$str)){
				return true;
			}
		break;
		case 'CHARACTER_ONLY':
			if(eregi("^[A-Za-z\ ]+$",$str)){
				return true;
			}
		break;
		case 'CHAR_AND_NUMBER':
			if(eregi("^[A-Za-z0-9\ ]+$",$str)){
				return true;
			}
		break;
		case 'ALPHANUMERIC':
			if(eregi("^[A-Za-z0-9\+\=\_\-\)\(\*\&\%\$\#\@\!\.\,\:\ ]+$",$str)){
				return true;
			}
		break;
		default:
			return false;
		break;
	}
}
function cleanXSS($val) {
	//$filter_sql = mysql_real_escape_string(stripslashes($val));
	$filter_sql = str_replace("\r\n","<br />",$val);
	$filter_sql = str_replace("<script"," ",$filter_sql);
	$filter_sql = str_replace("&lt;script"," ",$filter_sql);
	$filter_sql = str_replace("</script"," ",$filter_sql);
	$filter_sql = str_replace("&lt;/script"," ",$filter_sql);
	$filter_sql = str_replace("<iframe"," ",$filter_sql);
	$filter_sql = str_replace("&lt;iframe"," ",$filter_sql);
	$filter_sql = str_replace("</iframe"," ",$filter_sql);
	$filter_sql = str_replace("&lt;/iframe"," ",$filter_sql);
	$filter_sql = xss_clean_second_phase($filter_sql);
	return $filter_sql;
}

function xss_clean_second_phase($data)
{
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);
	$data = preg_replace('/insert/i', '', $data);
	// $data = preg_replace('/delete/i', '', $data);
	$data = preg_replace('/update/i', '', $data);
	// we are done...
	return $data;
}
function reformat_rule($str){
	$str = @eregi_replace("(lang\:.*)","",$str);
	$str = translate_rule($str); 
	return $str;
}

/*
function translate_rule($str){
	$str = @eregi_replace("(lang\:.*)","",$str);
	preg_match('/\(.*\)/i', $str, $matches);
	$str2 = $matches[0];
	
	$matches = null;
	$ANDs = str_replace($str2,"",$str);
	
	preg_match('/\-\(.*\)/i', $str2, $matches);
	$negates = $matches[0];
	$matches = null;
	$ORs = str_replace($negates,"",$str2);
	
	preg_match_all('/\-\(([a-zA-Z0-9\'\"\ ]+)\)/i',$negates,$matches);
	$excludes = $matches[1];
	
	$ORs = str_replace(array("(",")"),"",$ORs);
	
	$a_ORs = explode("OR",$ORs);
	
	//reformating
	$rules = "";
	$n=0;
	if(sizeof($a_ORs)>0){
		foreach($a_ORs as $a){
			if(strlen($a)>0){
				if($n==1){
					$rules.=" OR ";
				}
				$rules.=$ANDs." ".trim($a);
				$n=1;
			}
		}
	}
	if(strlen($rules)==0){
		$rules.=$ANDs;
	}
	if(sizeof($excludes)>0){
		$rules.=" Excludes : ";
		$n=0;
		foreach($excludes as $e){
			if(strlen($e)>0){
				if($n==1){
					$rules.=", ";
				}
				$rules.=trim($e);
				$n=1;
			}
		}
	}
	
	return $rules;
}
*/
function translate_rule($txt){
	
	$txt = @eregi_replace("(lang\:.*)","",$txt);
	//<[^>]+>
	preg_match_all('/\([^\)]+\)\ |[A-Za-z0-9\ ]+/i',$txt,$matches);
	$str="";
	//$foo = $matches[0][1];
	$n=0;
	foreach($matches[0] as $match){
		$match = str_replace(array("(",")"),"",$match);
		
		//check if there's OR Operator here.
		if($n==1){
			$str.=" and ";
		}
		if(preg_match('/OR/i',$match,$m)){
			//print $match."-> ada OR".PHP_EOL;
			$str.="(";
			$arr = explode("OR",$match);
			$n=0;
			foreach($arr as $a){
				$a = trim($a);
				preg_match_all('/"[^"]+"|[a-zA-z0-9\ ]+/',$a,$matches2);
				foreach($matches2[0] as $clauses){
					$clauses = trim($clauses);
					
					if($n==1){
						$str.=" OR ";
					}
					if(eregi("\"",$clauses)){
						$str.="{$clauses}";
					}else{
						$foo = explode(" ",$clauses);
						$t = 0;
						if(sizeof($foo)>1){
							$str.="(";
							foreach($foo as $f){
								if($t==1){
									$str.=" and ";	
								}
								$str.="{$f}";
								$t=1;
							}
							$str.=")";
						}else{
							$str.="{$clauses}";
						}
					}
					$n=1;
				}
			}
			$str.=")";
		}else{
			//print $match."-> gak ada OR".PHP_EOL;
			//search for another and terms
			//$str.= $match;
			preg_match_all('/"[^"]+"|[a-zA-z0-9\ ]+/',$match,$matches2);
			foreach($matches2[0] as $clauses){
				$clauses = trim($clauses);
				
				if(eregi("\"",$clauses)){
					$str.="{$clauses}";
				}else{
					$foo = explode(" ",$clauses);
					$t = 0;
					if(sizeof($foo)>1){
						$str.="(";
						foreach($foo as $f){
							if($t==1){
								$str.=" and ";	
							}
							$str.="{$f}";
							$t=1;
						}
						$str.=")";
					}else{
						$str.="{$clauses}";
					}
				}
				
			}
			
		}
		$n=1;
	}
	//print $foo.PHP_EOL;
	
	if(strlen(trim($str))==0){
		return $txt;
	}else{
		return $str;
	}
}
/**
 * convert gmt +0 to gmt +7
 * @param $str
 */
function jakarta_time($str){

	$a = explode(" ",trim($str));
	
	$d = explode("/",$a[0]);
	$t = $a[1];
	$s = $d[2]."-".$d[1]."-".$d[0]." ".$t;
	
	
	
	$st = strtotime($s);
	$st+= 60*60*7;
	$strDate = date("d/m/Y H:i:s",$st);
	return $strDate;
}

function dateUnixToIndo($str){
	if(eregi("-",$str)){
			$a1 = explode("-",$str);
			$formatted = $a1[2].'/'.$a1[1].'/'.$a1[0];
			return $formatted;
	}else{
		return $str;
	}
}
/**
 * secret is a secret hash value generated after the user login into the system.
 * @param $campaign_id
 * @param $user_id
 */
function get_access_token($secret_key,$api_key,$action="/",$unlimited=false){
	$access_token = urlencode64(serialize(array("secret_key"=>$secret_key,
					"api_key"=>$api_key,
					"expired"=>time()+120,
					"unlimited"=>$unlimited,
					'action'=>$action,
					"random"=>rand(100,999))));
	return $access_token;
}
/**
 * extract the access_token and then validate the secret key inside the token 
 * with the existing secret key in current active session
 * @param $access_token
 */
function read_access_token($access_token){
	$now = time();
	$info = unserialize(urldecode64($access_token));
	if($info['unlimited']==false){
		if($info['expired']>$now){
			return $info;
		}
	}else{
		return $info;
	}
}
function get_device($subject,$devices){
	foreach($devices as $device){
		if(eregi($device['descriptor'],$subject)){
			return $device['device_type'];
		}
	}
	return "other";
}
function clean_ascii($output){
	return preg_replace('/[^(\x20-\x7F)]*/','', $output);
}
/**
 * a helper function to help sorting an array based on its key's value
 * @param $a
 * @param $subkey
 */
function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}
function curlGet($url,$params="",$timeout=15){
	
	if(count($params) > 0 && $params!=""){
		$url .= "?".http_build_query($params);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec ($ch);
	$info = curl_getinfo($ch);
	curl_close ($ch);
	return $response;
}
function curlPOST($url,$params="",$timeout=15){
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
	curl_close ($ch);
	return $response;
}
function post_json($url,$data){
	$data_string = json_encode($data);
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);                                                                                                                   
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
function post_xml($url,$xml_string){
	$ch = curl_init($url);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");    
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return array("response"=>$result,"info"=>$info);
}
function text($name,$params=null){
	ob_start();
	include("../config/locale.inc.php");
	ob_end_clean();
	return $LOCALE[$name];
}

/**
 * extract words from romanic charactres
 * @param $str
 * @return array
 */
function extract_words($str){
	$str = strip_tags($str);
	$str = str_replace("RT"," ",$str);
	$str = strtolower($str);
	$str = extract_url($str);
	$str = strip_non_chars($str);
	$str = explode(" ",$str);
	// pr($str);exit;
	return $str;
}

function strip_non_chars($str){
$pattern = "([\~\!\#|$\%\^\&\*\(\)\?\.\,\=\:\;\"\'\*\\\/\[\]\-\_\r\n]+)";
$str = preg_replace($pattern," ",$str);
return $str;
}
function extract_url($str){
$pattern = "((https?|ftp|gopher|telnet|file):((//)|(\\\\))+[\\w\\d:#@%/;$()~_?\\+-=\\\\\\.&]*)";
$str = preg_replace($pattern,"",$str);
return $str;
}

function pr($obj_name){
	print "<pre>";
	print_r($obj_name);
	print "</pre>";
}
function uglify_request($req){
		global $ROUTES;
		
		/*if($_SESSION['_routes']==NULL){
			$_routes = array();
			foreach($ROUTES as $r=>$v){
				$_routes[$v]=explode("/",$v);
			}
			$_SESSION['_routes'] = serialize($_routes);
		}else{
			$_routes = unserialize($_SESSION['_routes']);
		}*/
		$_routes = array();
		$chunk = array();
		if(!$ROUTES) return false;
		foreach($ROUTES as $r=>$v){
			$_routes[$v]=explode("/",$v);
		}
		if(is_array($req)){
			foreach($req as $n=>$v){
				if(!preg_match("/(.jpg|.pjpg|.jpeg|.doc|.xls|.csv|.pdf|.txt|.png|.gif)/i",$n)){
					$chunk = explode("/",trim($n));
				}
				break;
			}
		}
		// pr($_routes);
		
		$n_params = sizeof($chunk);
		if($n_params>0){
			foreach($chunk as $n=>$v){
		
				if($n==1){
					$_REQUEST['page'] = $v;
					$_GET['page'] = $v;
				}else if($n==2){
					$thisNotAct = false;
					foreach($_routes as $r=>$p){
				
						if(preg_match("/\/{$chunk[1]}\/:/i",$r)){
							$n=0;
							foreach($p as $field){
								if($n>1){
									if(is_numeric($v)){
										$_REQUEST[str_replace(":","",$field)] = $v;
										$_GET[str_replace(":","",$field)] = $v;
										$thisNotAct = true;
									}
									
								}
								$n++;
							}
							
						}
					}
					if(!$thisNotAct){
						$_REQUEST['act'] = $v;
						$_GET['act'] = $v;
					}
				}
				if($n>2){
					break;
				}
			}
			$params_left = $n_params-3;
		
			if($params_left>0){
				foreach($_routes as $r=>$p){
					if(sizeof($p)==$n_params&&preg_match("/\/{$chunk[1]}\/{$chunk[2]}/i",$r)){
						$n=0;
						foreach($p as $field){
						
							if(strlen($field)>0){
								if($n==1){
									$_REQUEST['page'] = $chunk[$n];
									$_GET['page'] = $chunk[$n];
								}else if($n==2){
											
									$_REQUEST['act'] = $chunk[$n];
									$_GET['act'] = $chunk[$n];
								}else{
									$_REQUEST[str_replace(":","",$field)] = $chunk[$n];
									$_GET[str_replace(":","",$field)] = $chunk[$n];
								}
							}
							$n++;
						}
					}
				}
			}
		}
}

function wordsum($str=false,$link=""){
		
		if($str==false)  return false;
		$count = array();
		$arrstr = array();
		$rawstr = array();
		
		$str =  preg_replace("/[^a-zA-Z0-9\s]/", " ", $str);
		$str = preg_replace('/\s+/'," ",$str);		
		
		$rawstr = explode(" ",$str);
		if(count($rawstr)>0){
			foreach($rawstr as $key => $val){
				$str = str_replace(array("\r\n", "\r", "\n")," ",$val);
				$str = preg_replace('/\s+/',"",$str);
				if($str!=''){
					if(strlen($str)>1){
						$arrstr[] = strtoupper($str);
						$count[(string)strtoupper($str)] = 0;
					}
				}
				 
				// $a[$key]['len'] = strlen($str);
			}
			if(count($arrstr)>0){
				foreach($arrstr as $val){
					$count[(string)$val]+=1;
				}
				arsort($count);
				 
				foreach($count as $key => $val){
					if($link) $cloud[] = array("text"=>$key,"weight"=>$val,"html"=>array("class"=>"vertical"),"link"=>array("href"=>"{$link}{$key}"));
					else  $cloud[] = array("text"=>$key,"weight"=>$val,"html"=>array("class"=>"vertical") );
					 
				}
				// pr($cloud);
				$cloud = json_encode($cloud);
				return $cloud;
			}
			return false;
		}
		return false;
}
?>