<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {frozbite_call} function plugin
 *
 * Type:     function<br>
 * Name:     frozbite_call<br>
 * Purpose:  call frozbite component action and assign the output into template<br>
 * @author Hapsoro Renaldy <renaldy at omni-visual.com>
 * @param array 
 * @param Smarty
 */
function smarty_function_frozbite_call($params, &$smarty)
{
  	//var_dump($params); 
	$obj = $params['object'];
	if($obj!=null){
	//print $obj->callFunction($params);
		foreach($params as $name => $value){
			if(!is_object($value)){
				$obj->setRequest($name,$value,"GET");
			}
		}
		$obj->setRequest("idle","true","GET");
		$obj->performIdle();
	}
    //$smarty->assign("_debug_keys", array_keys($assigned_vars));
}
?>