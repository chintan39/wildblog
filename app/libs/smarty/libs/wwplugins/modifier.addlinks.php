<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty addlinks modifier plugin
 *
 * Type:     modifier<br>
 * Name:     addlinks<br>
 * Purpose:  analyze the string and find link functions, these replace with the link itself
 * @link http://code.google.com/p/wildblog/
 * @author   Jan Horak
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_addlinks($string)
{
	$needle = array();
	$replace = array();
	if (preg_match_all(	  '/\{\s*' 
						. 'linkto\s*' 
						. 'package\s*=\s*(\w+)\s*' 
						. 'controller\s*=\s*(\w+)\s*' 
						. 'method\s*=\s*(\w+)\s*' 
						. '([^\}]*)' 
						. '\}/', $string, $matches)) {
		foreach ($matches[0] as $k => $m) {
			$package = $matches[1][$k];
			$controller = $matches[2][$k];
			$method = $matches[3][$k];
			$other = $matches[4][$k];
			$filters = false;
			$values = false;
			if (preg_match('/\s*filters\s*=\s*\'([^\']*)\'\s*values\s*=\s*\'([^\']*)\'\s*/', $other, $params)) {
				$filters = $params[1];
				$values = $params[2];
			}
			if ($filters) {
				$link = Request::getLinkFilter($package, $controller, $method, explode("|", $filters), explode("|", $values));
			} else {
				$link = Request::getLinkSimple($package, $controller, $method);
			}
			$needle[] = $matches[0][$k];
			$replace[] = $link;
		}
    }
    return str_replace($needle, $replace, $string);
}

/* vim: set expandtab: */

?>
