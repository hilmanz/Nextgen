<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {proxy_image} function plugin
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
function smarty_function_proxy_image($params, &$smarty)
{
	
	//$SMAC_HASH = sha1("smarty_link");
	//$SMAC_SECRET = "smarty_link";
    $url = $params['url'];
    if($params['proxy']==null){
    	$proxy_image_path = "proxy_image.php";
    }else{
    	$proxy_image_path = $params['proxy'];
    }
    //return $SMAC_SECRET."-".$SMAC_HASH."-".$url."-".urlencode64($url);
    return $proxy_image_path."?p=".urlencode64($url);
    //return $SMAC_SECRET."-".$SMAC_HASH;
}

/* vim: set expandtab: */

?>