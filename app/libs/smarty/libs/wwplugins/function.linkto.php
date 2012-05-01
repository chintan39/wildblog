<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {linkto} function plugin
 *
 * Type:     function<br>
 * Name:     form_field<br>
 * Date:     May 21, 2002
 * Purpose:  automate form_field generation
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
 * {mailto address="me@domain.com"}
 * {mailto address="me@domain.com" encode="javascript"}
 * {mailto address="me@domain.com" encode="hex"}
 * {mailto address="me@domain.com" subject="Hello to you!"}
 * {mailto address="me@domain.com" cc="you@domain.com,they@domain.com"}
 * {mailto address="me@domain.com" extra='class="mailto"'}
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
function smarty_function_linkto($params, &$smarty)
{
	$outputLink = "";
	
	$defaultValues = array(
		'package' => 'Base', 
		'controller' => '', 
		'action' => '', 
		'dataItem' => null, 
		'filters' => '', 
		'values' => '', 
		'onempty' => '', 
		'regularExpression' => '',
		);
	
	// do the following with all keys from $defaultValues:
	// $package = $params['package']; unset($params['package']); etc.
	foreach ($defaultValues as $k => $v) {
		if (isset($params[$k])) {
			$$k = $params[$k];
			unset($params[$k]);
		} else {
			$$k = $v;
		}
	}
	
	try {
		if ($dataItem) {
			$outputLink = Request::getLinkItem($package, $controller, $action, $dataItem);
		} else if ($regularExpression) {
			// use the rest params
			$outputLink = Request::getLinkItem($package, $controller, $action, $params);
		} else if ($filters && $controller && $action) {
			$outputLink = Request::getLinkFilter($package, $controller, $action, explode("|", $filters), explode("|", $values));
		} else if ($controller && $action) {
			$outputLink = Request::getLinkSimple($package, $controller, $action, $params);
		} else {
			// use the rest params
			$outputLink = Request::getSameLink($params);
		}
	} catch (Exception $e) {
		if ($onempty) {
			$outputLink = $onempty;
		} else {
			throw new Exception($e);
		}
	}
	return $outputLink;
}

/* vim: set expandtab: */

?>
