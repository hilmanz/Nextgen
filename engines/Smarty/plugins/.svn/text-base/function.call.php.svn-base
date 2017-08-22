<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {call} function plugin
 *
 * Type:     function
 * Name:     call
 * Purpose:  call LEAF component action and assign the output into template
 * @author Hapsoro Renaldy <renaldy at dufronte.com>
 * @param array 
 * @param Smarty
 */
function smarty_function_call($params, &$smarty)
{
  	//var_dump($params); 
	$app = $params['app'];
	return $app->callModule($params);
}
?>