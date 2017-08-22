<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {secureurl} function plugin
 *
 * Type:     function<br>
 * Name:     mailto<br>
 * Date:     May 21, 2002
 * Purpose:  automate mailto address link creation, and optionally
 *           encode them.<br>
 * Input:<br>
 *         - address = e-mail address
 *         - text = (optional) text to display, default is address
 *         - encode = (optional) can be one of:
 *                * none : no encoding (default)
 *                * javascript : encode with javascript
 *                * javascript_charcode : encode with javascript charcode
 *                * hex : encode with hexidecimal (no javascript)
 *         - cc = (optional) address(es) to carbon copy
 *         - bcc = (optional) address(es) to blind carbon copy
 *         - subject = (optional) e-mail subject
 *         - newsgroups = (optional) newsgroup(s) to post to
 *         - followupto = (optional) address(es) to follow up to
 *         - extra = (optional) extra tags for the href link
 *
 * Examples:
 * <pre>
 * {secureurl url="me@domain.com"}
 * </pre>
 * @link http://smarty.php.net/manual/en/language.function.mailto.php {mailto}
 *          (Smarty online manual)
 * @version  1.2
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author   credits to Jason Sweat (added cc, bcc and subject functionality)
 * @param    array
 * @param    Smarty
 * @return   string
 */
function smarty_function_secureurl($params, &$smarty)
{
    $url = $params['url'];
    $arr = explode("?",$url);
    $p = array();
    $n=0;
    if($params['inline']=='true'){
    	$arr = explode("?",$url);
    	$url = $arr[0];
    	$str = explode("&",$arr[1]);
    	for($i=0;$i<sizeof($str);$i++){
    		$s = explode("=",$str[$i]);
    		$p[$i]['name'] = $s[0];
    		$p[$i]['value'] = $s[1];
    	}
    }else{
    	$url = $params['url'];
	    foreach($params as $name=>$val){
	    	if($name!='url'){
	    		$p[$n]['name'] = htmlspecialchars($name);
	    		$p[$n]['value'] = htmlspecialchars($val);
	    		$n++;	
	    	}
	    }
    }
   	$json = json_encode($p);
   	
	$sec_url = $url."?xn=".urlencode64($json);
	return $sec_url;
}

/* vim: set expandtab: */

?>
