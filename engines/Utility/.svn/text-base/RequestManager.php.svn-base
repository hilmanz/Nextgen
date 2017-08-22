<?php
class RequestManager{
	var $requests = array(array());
	function RequestManager($autoClean=false){
		//check dynamic subdomain
		$this->init_subdomain();
		uglify_request($_REQUEST);
		
		$this->requests['POST'] = $_POST;
		$this->requests['GET'] = $_GET;
		$this->requests['SESSION'] = $_SESSION;
		$this->requests['COOKIE'] = $_COOKIE;
		$this->requests['ENV'] = $_ENV;
		$this->requests['SERVER'] = $_SERVER;
		$this->requests['FILE'] =	$_FILES;
		$this->requests['REQUEST'] = $_REQUEST;
		if(strlen(@$_GET['xn'])>0){
			$this->secureURL($_GET['xn']);
		}
		
		//quick fix for handling a very long query string
		parse_str($_SERVER['QUERY_STRING'],$u);
		
		if(isset($u['req'])&&strlen($u['req'])>0){
			$_REQUEST['req'] = $u['req'];
			$_GET['req'] = $u['req'];
		}
		if(strlen(@$_REQUEST['req'])>0){
			$this->decrypt_params($_REQUEST['req']);
		}
		//if($autoClean){clean();}
		//var_dump($this->requests);
	}
	function init_subdomain(){
		global $CONFIG;
		$http_host = $_SERVER['HTTP_HOST'];
		
		if($http_host==null){
			$http_host = $_ENV['HTTP_HOST'];
		}
		if(@eregi($CONFIG['BASE_DOMAIN'],$http_host)){
			$domain_path = explode('.'.$CONFIG['BASE_DOMAIN'],$http_host);
			$subdomain = $domain_path[0];
			$_REQUEST['subdomain'] = $subdomain;
			$_GET['subdomain'] = $subdomain;
		}
	}
	function secureURL($hash){
		$params = json_decode(urldecode64($hash));
		for($i=0;$i<sizeof($params);$i++){
			$this->requests['GET'][$params[$i]->name] = $params[$i]->value;
			$this->requests['REQUEST'][$params[$i]->name] = $params[$i]->value;
		}
		
	}
	function encrypt_params($params){
		if(@$params['subdomain']==null){
			if(@$_REQUEST['subdomain']!=NULL){
				$params['subdomain'] = @$_REQUEST['subdomain'];
			}else{
				$params['subdomain'] = @$_SESSION['subdomain'];
			}
		}else{
			$_SESSION['subdomain'] = @$params['subdomain'];
		}
		$str = http_build_query($params);
		return "req=".urlencode64($str);
	}
	function decrypt_params($params){
		if(strlen($params)>0){
			$str = urldecode64($params);
		
			parse_str($str, $arr);
			
			foreach($arr as $name=>$val){
				if($name=="subdomain"){
					$_SESSION['subdomain'] = $val;
				}
				$this->requests['GET'][$name] = $val;
				$this->requests['REQUEST'][$name] = $val;
			}
		}
	}
	function clean($str){
		global $CONFIG;
		//cleaning up process here
		$str = mysql_escape_string($str);
		
		$str = str_replace("UNION","",$str);
		
		$str = str_replace("SELECT%20","",$str);
		$str = str_replace("CREATE%20TABLE","",$str);
		// $str = str_replace("DELETE%20","",$str);
		$str = str_replace("SHOW%20","",$str);
		$str = str_replace("TRUNCATE%20","",$str);
		$str = str_replace("ALTER%20","",$str);
		$str = str_replace("DROP","",$str);
		$str = str_replace("SLEEP","",$str);
		$str = str_replace("union","",$str);

		$str = str_replace("select%20","",$str);
		$str = str_replace("create%20table","",$str);
		// $str = str_replace("delete%20","",$str);
		$str = str_replace("sleep","",$str);
		$str = str_replace("truncate%20","",$str);
		$str = str_replace("alter%20","",$str);
		$str = str_replace("drop","",$str);
		$str = str_replace("\r\n","<br />",$str);
		$str = str_replace("<script"," ",$str);
		$str = str_replace("&lt;script"," ",$str);
		$str = str_replace("</script"," ",$str);
		$str = str_replace("&lt;/script"," ",$str);
		if(array_key_exists('MEDIUMSECURE',$CONFIG)){
			if(!$CONFIG['MEDIUMSECURE']){
				// $str = str_replace("UPDATE%20","",$str);
				// $str = str_replace("update%20","",$str);
				$str = str_replace("<iframe"," ",$str);
				$str = str_replace("&lt;iframe"," ",$str);
				$str = str_replace("</iframe"," ",$str);
				$str = str_replace("&lt;/iframe"," ",$str);
			}
		}else{
				// $str = str_replace("UPDATE%20","",$str);
				// $str = str_replace("update%20","",$str);
				$str = str_replace("<iframe"," ",$str);
				$str = str_replace("&lt;iframe"," ",$str);
				$str = str_replace("</iframe"," ",$str);
				$str = str_replace("&lt;/iframe"," ",$str);
		}
		//print($str."<br/>");
		$str = $this->cleanXSS($str);
		return $str;
	}
	
	function cleanXSS($val) {
		global $CONFIG;
		//$filter_sql = mysql_escape_string(stripslashes($val));
		$filter_sql = str_replace("\r\n","<br />",$val);
		$filter_sql = str_replace("<script"," ",$filter_sql);
		$filter_sql = str_replace("&lt;script"," ",$filter_sql);
		$filter_sql = str_replace("</script"," ",$filter_sql);
		$filter_sql = str_replace("&lt;/script"," ",$filter_sql);
		if(array_key_exists('MEDIUMSECURE',$CONFIG)){
				if(!$CONFIG['MEDIUMSECURE']){
					$filter_sql = str_replace("<iframe"," ",$filter_sql);
					$filter_sql = str_replace("&lt;iframe"," ",$filter_sql);
					$filter_sql = str_replace("</iframe"," ",$filter_sql);
					$filter_sql = str_replace("&lt;/iframe"," ",$filter_sql);
				}
		}else{
				$filter_sql = str_replace("<iframe"," ",$filter_sql);
				$filter_sql = str_replace("&lt;iframe"," ",$filter_sql);
				$filter_sql = str_replace("</iframe"," ",$filter_sql);
				$filter_sql = str_replace("&lt;/iframe"," ",$filter_sql);
		}
		$filter_sql = $this->xss_clean_second_phase($filter_sql);
		return $filter_sql;
	}

function xss_clean_second_phase($data)
{
	global $CONFIG;
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
		if(array_key_exists('MEDIUMSECURE',$CONFIG)){
			if(!$CONFIG['MEDIUMSECURE']) $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}else $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);
	$data = preg_replace('/insert/i', '', $data);
	// $data = preg_replace('/delete/i', '', $data);
	if(array_key_exists('MEDIUMSECURE',$CONFIG)){
		// if(!$CONFIG['MEDIUMSECURE']) $data = preg_replace('/update/i', '', $data);
	}else {	
		// $data = preg_replace('/update/i', '', $data);
	}
	// we are done...
	return $data;
}
	
	function getParam($name){
		return htmlentities($this->clean(@$this->requests['GET'][$name]));
	}
	function getRequest($name){
		return htmlentities($this->clean(@$this->requests['REQUEST'][$name]));
	}
	function setParam($name,$value){
		$this->requests['GET'][$name] = $value;
	}
	function setParamPost($name,$value){
		$this->requests['POST'][$name] = $value;
		$this->requests['REQUEST'][$name] = $value;
	}
	function getPost($name){
		return htmlentities($this->clean(@$this->requests['POST'][$name]));
	}
	function getCookie($name){
		return $this->requests['COOKIE'][$name];
	}
	function getSession($name){
		return @$this->requests['SESSION'][$name];
	}
	function getFileName($name){
		return $this->requests['FILE'][$name]['name'];
	}
	function getFileTemp($name){
		return $this->requests['FILE'][$name]['tmp_name'];
	}
	function getFileSize($name){
		return $this->requests['FILE'][$name]['file_size'];
	}
	function isPostAvailable(){
		if(sizeof($_POST)>0){
			return true;
		}	
	}
	function isFileExist($name){
		if($this->requests['FILE'][$name]['name']!=NULL&&
		$this->requests['FILE'][$name]['tmp_name']!=NULL)
		{return true;}
	}
	function peek(){
		print "GET :<br/>";
		print_r($_GET);
		print "<br/>POST:<br/>";
		print_r($_POST);
		print "<br/>SESSION:<br/>";
		print_r($_SESSION);
	}
}
?>
