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
function smarty_outputfilter_thumbnailpermit($source, &$smarty)
{
	if (preg_match_all('/' . Utilities::string2regexp(substr(DIR_PROJECT_URL_MEDIA_THUMBS, 1)) . '[^\'"]+/', $source, $matches)) {
		foreach ($matches[0] as $k => $m) {
			$path = Utilities::url2path($matches[0][$k]);
			Utilities::createPath($path);
			$thumb = new Thumbnail($path);
			$thumb->createPermitFile();
		}
	}
	return $source;
}

?>
