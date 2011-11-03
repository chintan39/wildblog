<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty specialinfoondebug outputfilter plugin
 *
 * File:     outputfilter.specialinfoondebug.php<br>
 * Type:     outputfilter<br>
 * Name:     specialinfoondebug<br>
 * Date:     May 15, 2009<br>
 * Purpose:  Print some debug info about generating the respond.
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','specialinfoondebug');</code>
 *           from application.
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_specialinfoondebug($source, &$smarty)
{
	if (Config::Get("DEBUG_MODE")) {
		
		if (isset(Request::$get['benchmark'])) {
			$source = preg_replace("/<\/body>/", Benchmark::getDisplay() . "</body>", $source);
		}
		
	}
    return $source;
}

?>
