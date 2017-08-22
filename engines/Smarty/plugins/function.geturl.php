<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {geturl} function plugin
 *
 * Type:     function
 * Name:     geturl
 * Purpose:  convert the URL into correct path
 * @author Hapsoro Renaldy <renaldy at dufronte.com>
 * @param array 
 * @param Smarty
 */
function smarty_function_geturl($params, &$smarty)
{
  	//var_dump($params); 
	$url = $params['value'];
	$basepath = $_SERVER["PHP_SELF"];
	$foo = explode("/",$basepath);
	$host = $_SERVER['HTTP_HOST'];
	$app_path = "";
	for($i=(sizeof($foo)-2);$i>=0;$i--){
		$app_path = $foo[$i]."/".$app_path;
	}
	if($_SERVER["HTTPS"]=="on"){
		return "https://".$host.$app_path.$url;
	}else{
		return "http://".$host.$app_path.$url;
	}
    //$smarty->assign("_debug_keys", array_keys($assigned_vars));
}
?>