<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty addautolinks outputfilter plugin
 *
 * File:     outputfilter.addautolinks.php<br>
 * Type:     outputfilter<br>
 * Name:     specialinfoondebug<br>
 * Date:     May 15, 2009<br>
 * Purpose:  Print some debug info about generating the respond.
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','addautolinks');</code>
 *           from application.
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_addautolinks($source, &$smarty)
{
	
	if ($smarty->mime_type != 'text/html') {
		return $source;
	}
	
	$storedPatterns = array();
	$patterns = array();
	$replaces = array();
	
	// item link
	if (preg_match_all("/autolink:(\w+)::(\w+)::(\w+)::(\d{1,9})/", $source, $matches)) {
		foreach ($matches[0] as $key => $m) {
			$pattern = 'autolink:' . $matches[1][$key] . '::' . $matches[2][$key] 
							. '::' . $matches[3][$key] . '::' . $matches[4][$key];
			if (!array_key_exists($pattern, $storedPatterns)) {
				$modelName = $matches[1][$key] . $matches[2][$key] . "Model";
				$storedPatterns[$pattern] = 1;
				$patterns[] = $pattern;
				$replaces[] = Request::getLinkItem($matches[1][$key], $matches[2][$key], $matches[3][$key], new $modelName($matches[4][$key]));
			}
		}
	}
	
	if ($replaces) {
		$source = str_replace($patterns, $replaces, $source);
	}
	
	$storedPatterns = array();
	$patterns = array();
	$replaces = array();
	
	// simple link
	if (preg_match_all("/autolink:(\w+)::(\w+)::(\w+)/", $source, $matches)) {
		foreach ($matches[0] as $key => $m) {
			$pattern = 'autolink:' . $matches[1][$key] . '::' . $matches[2][$key] . '::' . $matches[3][$key];
			if (!array_key_exists($pattern, $storedPatterns)) {
				$storedPatterns[$pattern] = 1;
				$patterns[] = $pattern;
				$replaces[] = Request::getLinkSimple($matches[1][$key], $matches[2][$key], $matches[3][$key]);
			}
		}
	}
	
	if ($replaces) {
		$source = str_replace($patterns, $replaces, $source);
	}
	
	// add absolute path to anchors on the page (href="#something.. becomes href="{path}#something...)
	$source = preg_replace('/href\s*=\s*"#/i', 'href="' . Request::getSameLink() . '#', $source);
	
	return $source; 
}

?>
